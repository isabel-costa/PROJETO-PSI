<?php

namespace backend\modules\api\controllers;

use common\models\Medico;
use yii\filters\auth\HttpHeaderAuth;
use yii\rest\ActiveController;
use Yii;
use common\models\Consulta;

class ConsultaController extends ActiveController
{
    public $modelClass = 'common\models\Consulta';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBasicAuth::class,
            'only' => ['view', 'futuras', 'passadas', 'solicitar', 'delete'],
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

    public function actionView($id)
    {
        $pacienteId = Yii::$app->user->identity->paciente->id;
        $consulta = Consulta::find()->where(['id' => $id, 'paciente_id' => $pacienteId])->with(['medico', 'prescricoes'])->one();

        if (!$consulta) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Consulta não encontrada'];
        }

        return [
            'id' => $consulta->id,
            'paciente_id' => $consulta->paciente_id,
            'medico' => [
                'id' => $consulta->medico->id,
                'nome_completo' => $consulta->medico->nome_completo,
                'especialidade' => $consulta->medico->especialidade,
            ],
            'data_consulta' => $consulta->data_consulta,
            'estado' => $consulta->estado,
            'observacoes' => $consulta->observacoes,
            'prescricoes' => array_map(function($p) {
                return [
                    'id' => $p->id,
                    'medicamento' => $p->medicamento,
                    'dosagem' => $p->dosagem,
                    'frequencia' => $p->frequencia
                ];
            }, $consulta->prescricoes),
        ];
    }

    public function actionFuturas()
    {
        $userId = Yii::$app->user->identity->paciente->id;
        return Consulta::find()->where(['paciente_id' => $userId])->andWhere(['>', 'data_consulta', date('Y-m-d H:i:s')])->all();
    }

    public function actionPassadas()
    {
        $userId = Yii::$app->user->identity->paciente->id;
        return Consulta::find()->where(['paciente_id' => $userId])->andWhere(['<', 'data_consulta', date('Y-m-d H:i:s')])->all();
    }

    public function actionSolicitar()
    {
        $request = Yii::$app->request->post();

        if (empty($request['medico_id']) || empty($request['data_consulta'])) {
            return ['error' => 'medico_id e data_consulta são obrigatórios'];
        }

        $medico = Medico::findOne($request['medico_id']);
        if (!$medico) {
            return ['error' => 'Médico inválido'];
        }

        $consulta = new Consulta();
        $consulta->paciente_id = Yii::$app->user->identity->paciente->id;
        $consulta->medico_id = $medico->id;
        $consulta->data_consulta = $request['data_consulta'];

        if (!$consulta->save()) {
            return ['error' => $consulta->getErrors()];
        }

        return [
            'success' => true,
            'message' => 'Pedido de consulta criado com sucesso',
            'consulta_id' => $consulta->id,
            'estado' => $consulta->estado,
            'medico' => [
                'id' => $medico->id,
                'nome_completo' => $medico->nome_completo,
                'especialidade' => $medico->especialidade
            ],
            'data_consulta' => $consulta->data_consulta
        ];
    }

    /**
     * Cancelar um pedido de consulta
     * DELETE /consulta/{id}
     */
    public function actionDelete($id)
    {
        $consulta = Consulta::findOne([
            'id' => $id,
            'paciente_id' => Yii::$app->user->identity->paciente->id,
            'estado' => Consulta::ESTADO_PENDENTE
        ]);

        if (!$consulta) {
            return ['error' => 'Consulta não encontrada ou não pode ser cancelada'];
        }

        $consulta->delete();

        return [
            'success' => true,
            'message' => 'Pedido de consulta cancelado com sucesso'
        ];
    }
}
