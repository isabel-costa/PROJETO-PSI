<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\User;

class UserController extends Controller
{
    /**
     * Cria um utilizador admin inicial.
     * Exemplo:
     * php yii user/create-admin admin admin@example.com password123
     */

    public function actionCreateUser($username, $email, $password)
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->status = 10;
        $user->created_at = time();
        $user->updated_at = time();

        if ($user->save()) {
            echo "✅ Utilizador criado com sucesso!\n";
            echo "🆔 ID: {$user->id}\n";
            echo "🔑 Token de acesso: {$user->access_token}\n";
        } else {
            echo "❌ Erro ao criar utilizador:\n";
            print_r($user->getErrors());
        }
    }

    public function actionCreateAdmin($username, $email, $password)
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->status = 10;
        $user->created_at = time();
        $user->updated_at = time();

        if ($user->save()) {
            echo "✅ Utilizador criado com sucesso!\n";

            // Atribuir a role admin
            $auth = Yii::$app->authManager;
            $adminRole = $auth->getRole('admin');
            if ($adminRole) {
                $auth->assign($adminRole, $user->id);
                echo "✅ Role 'admin' atribuída com sucesso.\n";
            } else {
                echo "⚠️ Role 'admin' ainda não existe. Corre 'php yii rbac/init'\n";
            }
        } else {
            print_r($user->getErrors());
        }
    }
}