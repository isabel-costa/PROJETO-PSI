<?php

namespace common\models;

use common\models\Consulta;
use common\models\User;
use Yii;

/**
 * This is the model class for table "pacientes".
 *
 * @property int $paciente_id
 * @property int $user_id
 * @property string $nome_completo
 * @property string|null $data_nascimento
 * @property string|null $sexo
 * @property string|null $nif
 * @property string|null $email
 * @property string|null $telemovel
 * @property string|null $morada
 * @property float|null $altura
 * @property float|null $peso
 * @property string|null $alergias
 * @property string|null $doencas_cronicas
 * @property string|null $data_registo
 *
 * @property Consulta[] $consultas
 * @property Prescricao[] $prescricos
 * @property RegistosToma[] $registosTomas
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
            [['data_nascimento', 'sexo', 'nif', 'email', 'telemovel', 'morada', 'altura', 'peso', 'alergias', 'doencas_cronicas'], 'default', 'value' => null],
            [['user_id', 'nome_completo'], 'required'],
            [['user_id'], 'integer'],
            [['data_nascimento', 'data_registo'], 'safe'],
            [['altura', 'peso'], 'number'],
            [['nome_completo', 'morada'], 'string', 'max' => 150],
            [['sexo'], 'string', 'max' => 1],
            [['nif'], 'string', 'max' => 9],
            [['email'], 'string', 'max' => 100],
            [['telemovel'], 'string', 'max' => 15],
            [['alergias', 'doencas_cronicas'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'paciente_id' => 'Paciente ID',
            'user_id' => 'User ID',
            'nome_completo' => 'Nome Completo',
            'data_nascimento' => 'Data Nascimento',
            'sexo' => 'Sexo',
            'nif' => 'Nif',
            'email' => 'Email',
            'telemovel' => 'Telemovel',
            'morada' => 'Morada',
            'altura' => 'Altura',
            'peso' => 'Peso',
            'alergias' => 'Alergias',
            'doencas_cronicas' => 'Doencas Cronicas',
            'data_registo' => 'Data Registo',
        ];
    }

    /**
     * Gets query for [[Consultas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConsultas()
    {
        return $this->hasMany(Consulta::class, ['paciente_id' => 'paciente_id']);
    }

    /**
     * Gets query for [[Prescricos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescricos()
    {
        return $this->hasMany(Prescricao::class, ['paciente_id' => 'paciente_id']);
    }

    /**
     * Gets query for [[RegistosTomas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegistosTomas()
    {
        return $this->hasMany(RegistosToma::class, ['paciente_id' => 'paciente_id']);
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
