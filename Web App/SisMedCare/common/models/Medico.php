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
 * @property string $especialidade
 * @property string $nif
 * @property string $telemovel
 * @property string $cedula_numero
 * @property string $horario_trabalho
 *
 * @property Consulta[] $consulta
 * @property Prescricao[] $prescricao
 * @property User $user
 */
class Medico extends \yii\db\ActiveRecord
{
    public $username;
    public $email;
    public $password;

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
            [['user_id', 'nome_completo', 'especialidade', 'nif', 'telemovel', 'cedula_numero', 'horario_trabalho'], 'required'],
            [['user_id'], 'integer'],
            [['nome_completo'], 'string', 'max' => 150],
            [['especialidade'], 'string', 'max' => 100],
            [['nif'], 'string', 'max' => 9],
            [['telemovel'], 'string', 'max' => 15],
            [['cedula_numero'], 'string', 'max' => 20],
            [['horario_trabalho'], 'string', 'max' => 50],
            [['username', 'email', 'password'], 'safe'],

            [['nif'], 'unique', 'message' => 'Já existe um médico com este NIF.'],
            [['telemovel'], 'unique', 'message' => 'Já existe um médico com este número de telemóvel.'],
            [['cedula_numero'], 'unique', 'message' => 'Já existe um médico com este número de cédula.'],

            ['email', 'validateEmailUnique'],
            ['username', 'validateUsernameUnique'],

            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID Médico',
            'user_id' => 'ID Utilizador',
            'nome_completo' => 'Nome Completo',
            'especialidade' => 'Especialidade',
            'nif' => 'NIF',
            'telemovel' => 'Telemóvel',
            'cedula_numero' => 'Número de Cédula',
            'horario_trabalho' => 'Horário de Trabalho',
        ];
    }

    /**
     * Gets query for [[Consultas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConsultas()
    {
        return $this->hasMany(Consulta::class, ['medico_id' => 'id']);
    }

    /**
     * Gets query for [[Prescricoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescricoes()
    {
        return $this->hasMany(Prescricao::class, ['medico_id' => 'id']);
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

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($this->user) {

            // Atualiza username e email
            $this->user->username = $this->username;
            $this->user->email = $this->email;

            // Se a password foi preenchida → gera hash
            if (!empty($this->password)) {
                $this->user->setPassword($this->password);
            }

            $this->user->save(false);
        }

        return true;
    }

    public function validateEmailUnique($attribute, $params)
    {
        if ($this->email && User::find()->where(['email' => $this->email])->andWhere(['!=', 'id', $this->user_id])->exists()) {
            $this->addError($attribute, 'Já existe um utilizador com este email.');
        }
    }

    public function validateUsernameUnique($attribute, $params)
    {
        if ($this->username && User::find()->where(['username' => $this->username])->andWhere(['!=', 'id', $this->user_id])->exists()) {
            $this->addError($attribute, 'Já existe um utilizador com este username.');
        }
    }
}
