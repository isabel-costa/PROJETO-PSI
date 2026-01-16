<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use Yii;
use common\models\PrescricaoMedicamento;
use yii\web\NotFoundHttpException;

class PrescricaoMedicamentoController extends ActiveController
{
    public $modelClass = 'common\models\PrescricaoMedicamento';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::class,
            'only' => ['index', 'view', 'prescricao'],
        ];

        return $behaviors;
    }

    /**
     * GET /prescricaomedicamento
     * Mostra todas as prescrições-medicamentos do paciente autenticado
     */
    public function actionIndex()
    {
        $pacienteId = Yii::$app->user->identity->paciente->id;

        return PrescricaoMedicamento::find()->joinWith('prescricao')->where(['prescricao.paciente_id' => $pacienteId])->orderBy(['prescricao.data_prescricao' => SORT_DESC])->all();
    }

    /**
     * GET /prescricaomedicamento/view/{prescricao_id}
     * Mostra todos os PrescricaoMedicamento de uma prescrição específica
     */
    public function actionView($id)
    {
        $pacienteId = Yii::$app->user->identity->paciente->id;

        $prescricaoMedicamento = PrescricaoMedicamento::find()
            ->where([
                'id' => $id,
                'paciente_id' => $pacienteId
            ])
            ->one();

        if (!$prescricaoMedicamento) {
            throw new \yii\web\NotFoundHttpException("Prescrição medicamento não encontrada.");
        }

        return $prescricaoMedicamento;
    }

    public function actionPrescricao($prescricao_id)
    {
        $itens = PrescricaoMedicamento::find()->where(['prescricao_id' => $prescricao_id])->all();

        if (!$itens) {
            throw new NotFoundHttpException("Nenhuma prescrição medicamento encontrada para esta prescrição.");
        }

        return $itens;
    }

}
