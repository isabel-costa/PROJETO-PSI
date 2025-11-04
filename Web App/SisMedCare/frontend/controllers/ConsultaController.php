<?php

namespace frontend\controllers;

use common\models\Consulta;
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
                    'consulta_id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Consulta model.
     * @param int $consulta_id Consulta ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($consulta_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($consulta_id),
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
                return $this->redirect(['view', 'consulta_id' => $model->consulta_id]);
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
     * @param int $consulta_id Consulta ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($consulta_id)
    {
        $model = $this->findModel($consulta_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'consulta_id' => $model->consulta_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Consulta model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $consulta_id Consulta ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($consulta_id)
    {
        $this->findModel($consulta_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Consulta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $consulta_id Consulta ID
     * @return Consulta the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($consulta_id)
    {
        if (($model = Consulta::findOne(['consulta_id' => $consulta_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
