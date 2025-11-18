<?php

namespace backend\controllers;

use common\models\Medico;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MedicoController implements the CRUD actions for Medico model.
 */
class MedicoController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'only' => ['index', 'view', 'create', 'update', 'delete'],
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'], // apenas utilizadores autenticados
                            'matchCallback' => function ($rule, $action) {
                                $auth = Yii::$app->authManager;
                                $roles = $auth->getRolesByUser(Yii::$app->user->id);
                                return isset($roles['admin']); // só admins
                            },
                        ],
                    ],
                    'denyCallback' => function () {
                        Yii::$app->session->setFlash('error', 'Acesso negado. Apenas administradores podem gerir médicos.');
                        return Yii::$app->response->redirect(['site/index']);
                    },
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Medico models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Medico::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Medico model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Medico model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Medico();

        if ($model->load(Yii::$app->request->post())) {

            // Criar utilizador
            $user = new User();
            $user->username = $model->username;
            $user->email = $model->email;
            $user->setPassword($model->password);
            $user->generateAuthKey();
            $user->status = 10;

            if ($user->save()) {

                Yii::$app->authManager->assign(
                    Yii::$app->authManager->getRole('doctor'),
                    $user->id
                );

                // Associar ao médico
                $model->user_id = $user->id;

                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }

            Yii::$app->session->setFlash('error', 'Erro ao criar o médico.');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Medico model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $user = $model->user; // vai buscar o User associado

        if ($this->request->isPost) {
            $post = Yii::$app->request->post();

            // Atualizar Medico
            if ($model->load($post) && $model->save()) {

                // Atualizar User associado
                if ($user && $user->load($post)) {
                    $user->save(false);
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'user' => $user,
        ]);
    }

    /**
     * Deletes an existing Medico model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // Apagar o user associado
        if ($model->user) {
            $model->user->delete();
        }

        // Apagar o médico
        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Medico model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Medico the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Medico::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
