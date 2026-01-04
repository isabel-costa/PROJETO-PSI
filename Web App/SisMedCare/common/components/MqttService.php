<?php
namespace common\components;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class MqttService
{
    private $client;

    public function __construct()
    {
        $this->client = new MqttClient('localhost', 1883, 'yii2-client-' . uniqid());
    }

    public function publish(string $topic, string $message)
    {
        $settings = (new ConnectionSettings)
            ->setUsername(null)
            ->setPassword(null);

        $this->client->connect($settings, true);
        $this->client->publish($topic, $message, 0);
        $this->client->disconnect();
    }

    public function subscribe(string $topic, callable $callback)
    {
        $settings = (new ConnectionSettings)
            ->setUsername(null)
            ->setPassword(null);

        $this->client->connect($settings, true);

        $this->client->subscribe($topic, function ($topic, $message) use ($callback) {
            $callback($topic, $message);
        }, 0);

        $this->client->loop(true);

        $this->client->disconnect();
    }
}