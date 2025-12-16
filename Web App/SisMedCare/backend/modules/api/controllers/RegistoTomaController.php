<?php

namespace backend\modules\api\controllers;

use Yii;
use yii\rest\Controller;
use common\models\RegistoToma;

class RegistoTomaController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBasicAuth::class,
            'only' => ['pendentes', 'tomadas', 'marcar'],
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
     * Retorna as tomas pendentes (foi_tomado = 0) do paciente
     */
    public function actionPendentes()
    {
        $pacienteId = Yii::$app->user->identity->paciente->id;
        $tomas = RegistoToma::find()->where(['paciente_id' => $pacienteId, 'foi_tomado' => 0])->orderBy(['data_toma' => SORT_DESC])->all();

        return $tomas;
    }

    /**
     * Retorna as tomas já tomadas (foi_tomado = 1) do paciente
     */
    public function actionTomadas()
    {
        $pacienteId = Yii::$app->user->identity->paciente->id;
        $tomas = RegistoToma::find()->where(['paciente_id' => $pacienteId, 'foi_tomado' => 1])->orderBy(['data_toma' => SORT_DESC])->all();

        return $tomas;
    }

    public function actionMarcar($id)
    {
        $pacienteId = Yii::$app->user->identity->paciente->id;

        $toma = RegistoToma::findOne([
            'id' => $id,
            'paciente_id' => $pacienteId
        ]);

        if (!$toma) {
            throw new \yii\web\NotFoundHttpException('Registo Toma não encontrado');
        }

        $toma->foi_tomado = 1;

        if ($toma->save(false)) {
            return ['success' => true];
        }

        return ['success' => false];
    }
}
