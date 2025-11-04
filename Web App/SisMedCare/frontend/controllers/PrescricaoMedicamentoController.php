<?php

namespace frontend\controllers;

use common\models\PrescricaoMedicamento;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PrescricaoMedicamentoController implements the CRUD actions for PrescricaoMedicamento model.
 */
class PrescricaoMedicamentoController extends Controller
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
     * Lists all PrescricaoMedicamento models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => PrescricaoMedicamento::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'prescricao_medicamento_id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PrescricaoMedicamento model.
     * @param int $prescricao_medicamento_id Prescricao Medicamento ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prescricao_medicamento_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prescricao_medicamento_id),
        ]);
    }

    /**
     * Creates a new PrescricaoMedicamento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PrescricaoMedicamento();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'prescricao_medicamento_id' => $model->prescricao_medicamento_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PrescricaoMedicamento model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prescricao_medicamento_id Prescricao Medicamento ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prescricao_medicamento_id)
    {
        $model = $this->findModel($prescricao_medicamento_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'prescricao_medicamento_id' => $model->prescricao_medicamento_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PrescricaoMedicamento model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prescricao_medicamento_id Prescricao Medicamento ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prescricao_medicamento_id)
    {
        $this->findModel($prescricao_medicamento_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PrescricaoMedicamento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prescricao_medicamento_id Prescricao Medicamento ID
     * @return PrescricaoMedicamento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prescricao_medicamento_id)
    {
        if (($model = PrescricaoMedicamento::findOne(['prescricao_medicamento_id' => $prescricao_medicamento_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
