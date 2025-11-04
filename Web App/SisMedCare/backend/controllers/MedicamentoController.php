<?php

namespace backend\controllers;

use common\models\Medicamento;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MedicamentoController implements the CRUD actions for Medicamento model.
 */
class MedicamentoController extends Controller
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
     * Lists all Medicamento models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Medicamento::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'medicamento_id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Medicamento model.
     * @param int $medicamento_id Medicamento ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($medicamento_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($medicamento_id),
        ]);
    }

    /**
     * Creates a new Medicamento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Medicamento();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'medicamento_id' => $model->medicamento_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Medicamento model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $medicamento_id Medicamento ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($medicamento_id)
    {
        $model = $this->findModel($medicamento_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'medicamento_id' => $model->medicamento_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Medicamento model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $medicamento_id Medicamento ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($medicamento_id)
    {
        $this->findModel($medicamento_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Medicamento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $medicamento_id Medicamento ID
     * @return Medicamento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($medicamento_id)
    {
        if (($model = Medicamento::findOne(['medicamento_id' => $medicamento_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
