<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pacientes".
 *
 * @property int $id
 * @property int $user_id
 * @property string $nome_completo
 * @property string $data_nascimento
 * @property string $sexo
 * @property string $numero_utente
 * @property string $telemovel
 * @property string $morada
 * @property float|null $altura
 * @property float|null $peso
 * @property string|null $alergias
 * @property string|null $doencas_cronicas
 * @property string $data_registo
 *
 * @property Consulta[] $consulta
 * @property Prescricao[] $prescricao
 * @property RegistoToma[] $registoToma
 * @property User $user
 */
class Paciente extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pacientes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'nome_completo', 'data_nascimento', 'sexo', 'numero_utente', 'telemovel', 'morada'], 'required'],
            [['altura', 'peso', 'alergias', 'doencas_cronicas'], 'default', 'value' => null],
            [['user_id', 'numero_utente'], 'integer'],
            [['altura', 'peso'], 'number'],
            [['data_nascimento', 'data_registo'], 'safe'],
            [['nome_completo', 'morada'], 'string', 'max' => 255],
            [['sexo'], 'string', 'max' => 1],
            [['telemovel'], 'string', 'max' => 20],
            [['alergias', 'doencas_cronicas'], 'string', 'max' => 255],

            [['numero_utente'], 'unique', 'message' => 'Este número de utente já está registado.'],
            [['telemovel'], 'unique', 'message' => 'Este número de telemóvel já está registado.'],

            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID Paciente',
            'user_id' => 'ID Utilizador',
            'nome_completo' => 'Nome Completo',
            'data_nascimento' => 'Data de Nascimento',
            'sexo' => 'Sexo',
            'numero_utente' => 'Número de Utente',
            'telemovel' => 'Telemóvel',
            'morada' => 'Morada',
            'altura' => 'Altura',
            'peso' => 'Peso',
            'alergias' => 'Alergias',
            'doencas_cronicas' => 'Doenças Crónicas',
            'data_registo' => 'Data de Registo',
        ];
    }

    /**
     * Gets query for [[Consultas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConsultas()
    {
        return $this->hasMany(Consulta::class, ['paciente_id' => 'id']);
    }

    /**
     * Gets query for [[Prescricoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescricoes()
    {
        return $this->hasMany(Prescricao::class, ['paciente_id' => 'id']);
    }

    /**
     * Gets query for [[RegistosTomas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegistosTomas()
    {
        return $this->hasMany(RegistoToma::class, ['paciente_id' => 'id']);
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
