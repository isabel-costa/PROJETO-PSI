<?php

return [
    [
        'id' => 1,
        'username' => 'medico1',
        'email' => 'medico1@example.com',
        'auth_key' => 'n-OOLtSj-6pBqUxcQ9uzv7nM1Nw-sqBX',
        'password_hash' => Yii::$app->security->generatePasswordHash('password123'),
        'status' => 10,
        'created_at' => time(),
        'updated_at' => time(),
    ],
];
