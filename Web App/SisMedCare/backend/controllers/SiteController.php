<?php

namespace backend\controllers;

use common\models\Consulta;
use common\models\LoginForm;
use common\models\Medicamento;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'except' => ['login', 'error'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function () {
                            $auth = Yii::$app->authManager;
                            $roles = $auth->getRolesByUser(Yii::$app->user->id);
                            return isset($roles['admin']) || isset($roles['secretary']);
                        }
                    ],
                ],
                'denyCallback' => function () {
                    Yii::$app->user->logout();
                    Yii::$app->session->setFlash('error', 'Apenas administradores e secretárias podem aceder ao Backend.');
                    return Yii::$app->response->redirect(['site/login']);
                }
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // --- PROTEÇÃO DO DASHBOARD ADMIN ---
        if (Yii::$app->user->can('viewDoctors')) {
            $medicosCount = User::countByRole('doctor');
        } else {
            $medicosCount = null;
        }

        if (Yii::$app->user->can('viewSecretaries')) {
            $secretariasCount = User::countByRole('secretary');
        } else {
            $secretariasCount = null;
        }

        if (Yii::$app->user->can('viewMedicines')) {
            $medicamentosCount = Medicamento::find()->count();
        } else {
            $medicamentosCount = null;
        }

        // --- PROTEÇÃO DO DASHBOARD SECRETÁRIA ---
        if (Yii::$app->user->can('viewAppointmentRequests')) {
            $pedidosAprovados   = Consulta::countEstado('aprovado');
            $pedidosRejeitados  = Consulta::countEstado('rejeitado');
            $pedidosPendentes   = Consulta::countEstado('pendente');
        } else {
            $pedidosAprovados = $pedidosRejeitados = $pedidosPendentes = null;
        }

        // --- GRÁFICO ---
        if (Yii::$app->user->can('viewAppointmentRequests')) {
            $consultasPorMes = Consulta::consultasPorMes();
            $labels = array_column($consultasPorMes, 'mes');
            $values = array_column($consultasPorMes, 'count');
        } else {
            $labels = $values = [];
        }

        return $this->render('index', compact(
            'medicosCount',
            'secretariasCount',
            'medicamentosCount',
            'pedidosAprovados',
            'pedidosRejeitados',
            'pedidosPendentes',
            'labels',
            'values'
        ));
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
