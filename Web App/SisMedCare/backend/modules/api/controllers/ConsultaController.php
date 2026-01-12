<?php

namespace backend\modules\api\controllers;

use common\models\Medico;
use yii\filters\auth\HttpHeaderAuth;
use yii\rest\ActiveController;
use Yii;
use common\models\Consulta;
use common\components\MqttService;

class ConsultaController extends ActiveController
{
    public $modelClass = 'common\models\Consulta';

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBasicAuth::class,
            'only' => ['view', 'futuras', 'passadas', 'solicitar', 'delete', 'update'],
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
        
    public function actions()
    {
        $actions = parent::actions();

            unset($actions['update']);

        return $actions;
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
        $consulta->estado = 'pendente';

        if (!$consulta->save()) {
            return ['error' => $consulta->getErrors()];
        }

        if (!$consulta->save()) {
        return ['error' => $consulta->getErrors()];
        }

         // Publish de mensagem no tópico pedidos-de-consulta
        $mqtt = new MqttService();
        $formatted = "\ntopico: pedidos-de-consulta\n\n" .
            "consulta_id: {$consulta->id}\n" .
            "paciente_id: {$consulta->paciente_id}\n" .
            "data_consulta: {$consulta->data_consulta}\n\n";
        $mqtt->publish('pedidos-de-consulta', $formatted);

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

    /** Atualizar uma consulta
     * PUT /consulta/{id}
     */
    public function actionUpdate($id)
    {
        $consulta = Consulta::findOne($id);

        if (!$consulta) {
            Yii::$app->response->statusCode = 404;
            return ['error' => 'Consulta não encontrada'];
        }

        $request = Yii::$app->request->bodyParams;
        $fields = ['data_consulta', 'estado', 'observacoes'];

        // Só atualiza os campos permitidos
        foreach ($fields as $field) {
            if (isset($request[$field])) {
                $consulta->$field = $request[$field];
            }
        }

        if (!$consulta->save()) {
            Yii::$app->response->statusCode = 400;
            return [
                'error' => 'Erro ao atualizar consulta',
                'details' => $consulta->getErrors()
            ];
        }

        return ['success' => true, 'message' => 'Consulta atualizada com sucesso'];
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
