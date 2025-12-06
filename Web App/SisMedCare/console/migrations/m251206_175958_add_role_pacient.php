<?php

use yii\db\Migration;

class m251206_175958_add_role_pacient extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $auth = Yii::$app->authManager;

        // Verifica se a role 'pacient' já existe
        if (!$auth->getRole('pacient')) {
            $role = $auth->createRole('pacient');
            $role->description = 'Paciente';
            $auth->add($role);
            echo "Role 'pacient' criada com sucesso.\n";
        } else {
            echo "Role 'pacient' já existe.\n";
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $auth = Yii::$app->authManager;

        // Remove a role se existir
        $role = $auth->getRole('pacient');
        if ($role) {
            $auth->remove($role);
            echo "Role 'pacient' removida com sucesso.\n";
        } else {
            echo "Role 'pacient' não existe.\n";
        }
    }
}
