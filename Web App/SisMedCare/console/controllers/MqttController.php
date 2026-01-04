<?php

namespace console\controllers;

use yii\console\Controller;
use common\components\MqttService;
use Yii;

class MqttController extends Controller
{
    // Subscreve no tópico 'pedidos-de-consulta'
    public function actionNovosPedidosConsulta()
    {
        $mqtt = new MqttService();

        $mqtt->subscribe('pedidos-de-consulta', function ($topic, $message) {
            echo "Nova mensagem: $message\n";

            Yii::$app->cache->set('novo_pedido_consulta', [
                'mensagem' => $message,
                'timestamp' => time(),
            ], 20);
        });
    }
}