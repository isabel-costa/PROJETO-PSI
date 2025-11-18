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
    public function actionCreateAdmin($username, $email, $password)
    {
        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->status = 10; // 10 = ativo
        $user->created_at = time();
        $user->updated_at = time();

        if ($user->save()) {
            echo "✅ Utilizador criado com sucesso!\n";

            // Atribuir role admin
            $auth = Yii::$app->authManager;
            $adminRole = $auth->getRole('admin');
            if ($adminRole) {
                $auth->assign($adminRole, $user->id);
                echo "✅ Role 'admin' atribuída.\n";
            } else {
                echo "⚠️ Role 'admin' não existe. Corre 'php yii rbac/init'\n";
            }
        } else {
            print_r($user->getErrors());
        }
    }

    public function actionCreateSecretary($username, $email, $password)
    {
        $user = new \common\models\User();
        $user->username = $username;
        $user->email = $email;
        $user->setPassword($password);
        $user->generateAuthKey();
        $user->status = 10; // ativo
        $user->created_at = time();
        $user->updated_at = time();

        if ($user->save()) {
            echo "✅ Secretária criada com sucesso!\n";

            // Atribuir role secretary
            $auth = Yii::$app->authManager;
            $role = $auth->getRole('secretary');
            if ($role) {
                $auth->assign($role, $user->id);
                echo "✅ Role 'secretary' atribuída.\n";
            } else {
                echo "⚠️ Role 'secretary' não existe. Corre 'php yii rbac/init'\n";
            }
        } else {
            print_r($user->getErrors());
        }
    }
}
