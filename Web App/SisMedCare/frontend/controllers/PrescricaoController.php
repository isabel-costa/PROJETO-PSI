<?php

namespace frontend\controllers;

use Yii;

use common\models\Prescricao;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Paciente;

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
    public function actionIndex($paciente_id = null)
    {
        if ($paciente_id !== null) {
            $paciente = Paciente::findOne($paciente_id);

            $dataProvider = new ActiveDataProvider([
                'query' => Prescricao::find()
                    ->where(['paciente_id' => $paciente_id])
                    ->with(['consulta', 'medico']),
                'pagination' => ['pageSize' => 20],
                'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            ]);

            return $this->render('index', [
                'paciente' => $paciente,
                'dataProvider' => $dataProvider,
            ]);
        }

        // Lista de pacientes com pelo menos uma prescrição
        $listaPacientes = new ActiveDataProvider([
            'query' => Prescricao::find()
                ->joinWith('paciente')
                ->groupBy('paciente_id')
                ->select(['prescricoes.*', 'COUNT(prescricoes.id) AS total']),
            'pagination' => false,
        ]);

        return $this->render('index', [
            'listaPacientes' => $listaPacientes,
        ]);
    }

    /**
     * Displays a single Prescricao model.
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
     * Creates a new Prescricao model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($consulta_id)
    {
        $model = new \common\models\Prescricao();
        
        $consulta = \common\models\Consulta::findOne($consulta_id);
        if (!$consulta) {
            throw new \yii\web\NotFoundHttpException('Consulta não encontrada.');
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->medico_id = $consulta->medico_id;
                $model->paciente_id = $consulta->paciente_id;
                $model->consulta_id = $consulta->id;

                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'consulta' => $consulta,
        ]);
    }
    
    /**
     * Finds the Prescricao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Prescricao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Prescricao::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('A Prescrição solicitada não existe.');
    }
}
