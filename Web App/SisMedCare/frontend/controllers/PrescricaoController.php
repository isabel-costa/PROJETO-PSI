<?php

namespace frontend\controllers;

use common\models\Prescricao;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PrescricaoController implements the CRUD actions for Prescricao model.
 */
class PrescricaoController extends Controller
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
     * Lists all Prescricao models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Prescricao::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'prescricao_id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Prescricao model.
     * @param int $prescricao_id Prescricao ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($prescricao_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($prescricao_id),
        ]);
    }

    /**
     * Creates a new Prescricao model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Prescricao();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'prescricao_id' => $model->prescricao_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Prescricao model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $prescricao_id Prescricao ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($prescricao_id)
    {
        $model = $this->findModel($prescricao_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'prescricao_id' => $model->prescricao_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Prescricao model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $prescricao_id Prescricao ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($prescricao_id)
    {
        $this->findModel($prescricao_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Prescricao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $prescricao_id Prescricao ID
     * @return Prescricao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($prescricao_id)
    {
        if (($model = Prescricao::findOne(['prescricao_id' => $prescricao_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
