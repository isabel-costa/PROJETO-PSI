<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use Yii;
use common\models\Prescricao;

class PrescricaoController extends ActiveController
{
    public $modelClass = 'common\models\Prescricao';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBasicAuth::class,
            'only' => ['index', 'view'],
            'auth' => function ($username, $password) {
                $user = \common\models\User::findByUsername($username);
                if ($user && $user->validatePassword($password)) {
                    return $user;
                }
                return null;
            },
        ];

        return $behaviors;
    }

    /**
     * GET /prescricoes
     * Mostra todas as prescrições do paciente autenticado
     */
    public function actionIndex()
    {
        $pacienteId = Yii::$app->user->identity->paciente->id;

        return Prescricao::find()->where(['paciente_id' => $pacienteId])->orderBy(['data_prescricao' => SORT_DESC])->all();
    }

    /**
     * GET /prescricoes/{id}
     * Detalhes de 1 prescrição
     */
    public function actionView($id)
    {
        $pacienteId = Yii::$app->user->identity->paciente->id;

        $prescricao = Prescricao::find()
            ->where([
                'id' => $id,
                'paciente_id' => $pacienteId
            ])
            ->one();

        if (!$prescricao) {
            throw new \yii\web\NotFoundHttpException("Prescrição não encontrada.");
        }

        return $prescricao;
    }
}
