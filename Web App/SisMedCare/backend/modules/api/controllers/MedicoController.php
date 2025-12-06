<?php

namespace backend\modules\api\controllers;

use common\models\User;
use yii\rest\Controller;
use common\models\Medico;

class MedicoController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBasicAuth::class,
            'only' => ['index'],
            'auth' => function ($username, $password) {
                $user = User::findOne(['username' => $username]);
                if ($user && $user->validatePassword($password)) {
                    return $user;
                }
                return null;
            },
        ];

        return $behaviors;
    }

    /**
     * Lista de médicos por especialidade
     * GET /medico?especialidade=Cardiologia
     */
    public function actionIndex($especialidade = null)
    {
        $query = Medico::find();

        if ($especialidade) {
            $query->andWhere(['especialidade' => $especialidade]);
        }

        $medicos = $query->all();

        $result = [];
        foreach ($medicos as $medico) {
            $result[] = [
                'id' => $medico->id,
                'nome' => $medico->nome_completo,
                'especialidade' => $medico->especialidade,
            ];
        }

        return $result;
    }
}
