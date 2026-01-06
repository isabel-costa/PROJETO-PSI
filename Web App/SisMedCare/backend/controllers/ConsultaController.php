<?php

namespace backend\controllers;

use common\models\Consulta;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ConsultaController implements the CRUD actions for Consulta model.
 */
class ConsultaController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Consulta models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Consulta::find(),
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

        $dataProviderPendentes = new ActiveDataProvider([
            'query' => Consulta::find()
                ->where(['estado' => 'pendente'])
                ->orderBy(['data_consulta' => SORT_ASC]),
            'pagination' => ['pageSize' => 10],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'dataProviderPendentes' => $dataProviderPendentes,
        ]);
    }

    /**
     * Displays a single Consulta model.
     * @param int $id ID Consulta
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
     * Creates a new Consulta model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Consulta();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Consulta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID Consulta
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Consulta model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID Consulta
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Consulta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID Consulta
     * @return Consulta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Consulta::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionAprovarConsulta($id)
    {
        $model = Consulta::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException("Consulta não encontrada.");
        }

        $model->estado = 'agendada';

        if ($model->save(false, ['estado'])) {
            Yii::$app->session->setFlash(
                'consulta-success',
                'Consulta aprovada com sucesso.'
            );
        } else {
            Yii::$app->session->setFlash(
                'consulta-error',
                'Erro ao aprovar a consulta.'
            );
        }
        return $this->redirect(['index']);
    }

    public function actionCheckNovosPedidos()
    {
        $dados = \Yii::$app->cache->get('novo_pedido_consulta');

        if ($dados) {
            \Yii::$app->cache->delete('novo_pedido_consulta');

            return $this->asJson([
                'novo' => true,
                'mensagem' => $dados['mensagem'],
            ]);
        }

        return $this->asJson(['novo' => false]);
    }


}
