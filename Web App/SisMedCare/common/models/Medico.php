<?php

namespace common\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "medicos".
 *
 * @property int $id
 * @property int $user_id
 * @property string $nome_completo
 * @property string|null $especialidade
 * @property string|null $nif
 * @property string|null $email
 * @property string|null $telemovel
 * @property string|null $cedula_numero
 * @property string|null $horario_trabalho
 *
 * @property Consulta[] $consulta
 * @property Prescricao[] $prescricao
 * @property User $user
 */
class Medico extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'medicos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['especialidade', 'nif', 'email', 'telemovel', 'cedula_numero', 'horario_trabalho'], 'default', 'value' => null],
            [['user_id', 'nome_completo'], 'required'],
            [['user_id'], 'integer'],
            [['nome_completo'], 'string', 'max' => 150],
            [['especialidade', 'email'], 'string', 'max' => 100],
            [['nif'], 'string', 'max' => 9],
            [['telemovel'], 'string', 'max' => 15],
            [['cedula_numero'], 'string', 'max' => 20],
            [['horario_trabalho'], 'string', 'max' => 50],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'nome_completo' => 'Nome Completo',
            'especialidade' => 'Especialidade',
            'nif' => 'Nif',
            'email' => 'Email',
            'telemovel' => 'Telemovel',
            'cedula_numero' => 'Cedula Numero',
            'horario_trabalho' => 'Horario Trabalho',
        ];
    }

    /**
     * Gets query for [[Consultas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConsultas()
    {
        return $this->hasMany(Consultas::class, ['medico_id' => 'id']);
    }

    /**
     * Gets query for [[Prescricoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescricoes()
    {
        return $this->hasMany(Prescricoes::class, ['medico_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
