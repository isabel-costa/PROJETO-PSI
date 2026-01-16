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
            'class' => \yii\filters\auth\HttpBearerAuth::class,
            'only' => ['index', 'medico'],
        ];

        return $behaviors;
    }

    /**
     * Mostra médicos por especialidade
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

    public function actionMedico()
    {
        $medicos = Medico::find()->all();

        return array_map(fn($m) => [
            'id' => $m->id,
            'nome' => $m->nome_completo,
            'especialidade' => $m->especialidade,
        ], $medicos);
    }
}
