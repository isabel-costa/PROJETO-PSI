<?php

namespace frontend\controllers;

use Yii;

use common\models\Consulta;
use common\models\Medico;
use yii\web\ForbiddenHttpException;
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
        $userId = Yii::$app->user->id;

        $medico = Medico::find()
            ->where(['user_id' => $userId])
            ->one();

        if (!$medico) {
            throw new NotFoundHttpException('Médico não encontrado para este utilizador.');
        }

        // =============================
        // Atualiza consultas agendadas automaticamente após 3h
        // =============================
        $consultasAgendadas = Consulta::find()
            ->where(['estado' => 'agendada', 'medico_id' => $medico->id])
            ->all();

        foreach ($consultasAgendadas as $consulta) {
            $horarioConsulta = strtotime($consulta->data_consulta); 
            if (time() > $horarioConsulta + 3 * 3600) { 
                $consulta->estado = 'concluida';
                $consulta->save(false);
            }
        }

        // =============================
        // DataProvider para mostrar as consultas
        // =============================
        $dataProvider = new ActiveDataProvider([
            'query' => Consulta::find()->where(['medico_id' => $medico->id]),
        ]);

        $user = Yii::$app->user->identity;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'user' => $user,
        ]);
    }

    /**
     * Displays a single Consulta model.
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
     * Updates an existing Consulta model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
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
     * Finds the Consulta model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
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
}
