<?php

namespace common\models;

use yii\db\ActiveRecord;

class Medico extends ActiveRecord
{
    // common/models/Medico.php

    public static function medicos()
    {
        return 'medicos';
    }

    // ... dentro da classe Medico
    public function rules()
    {
        return [
            [['nome_completo', 'especialidade', 'nif', 'telemovel', 'cedula_numero', 'horario_trabalho', 'user_id'], 'required'], // adiciona user_id
            [['horario_trabalho'], 'string'],
            [['nome_completo', 'especialidade', 'nif', 'telemovel', 'cedula_numero'], 'string', 'max' => 255],
            [['user_id'], 'integer'],
        ];
    }

    // Relação com User (opcional, ajuda muito)
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
