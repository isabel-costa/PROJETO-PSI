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
            'only' => ['pendentes', 'tomadas'],
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
        $hoje = date('Y-m-d');
        $tomas = RegistoToma::find()->where(['paciente_id' => $pacienteId, 'foi_tomado' => 0])->andWhere(['data_toma' => $hoje])->all();

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

    public function actionMarcar()
    {
        $pacienteId = Yii::$app->user->identity->id;
        $params = Yii::$app->request->post();

        if (!isset($params['registo_toma_id'])) {
            throw new \yii\web\BadRequestHttpException('registo_toma_id é obrigatório');
        }

        $toma = RegistoToma::findOne([
            'id' => $params['registo_toma_id'],
            'paciente_id' => $pacienteId
        ]);

        if (!$toma) {
            throw new \yii\web\NotFoundHttpException('Registo Toma não encontrado');
        }

        $toma->foi_tomado = 1;

        if ($toma->save()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'errors' => $toma->errors];
        }
    }
}
