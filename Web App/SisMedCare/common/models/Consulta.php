<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "consultas".
 *
 * @property int $consulta_id
 * @property int|null $paciente_id
 * @property int|null $medico_id
 * @property string|null $data_consulta
 * @property string|null $estado
 * @property string|null $observacoes
 * @property string|null $criado_em
 *
 * @property Medico $medico
 * @property Paciente $paciente
 * @property Prescricao[] $prescricos
 */
class Consulta extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'consultas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['paciente_id', 'medico_id', 'data_consulta', 'estado', 'observacoes'], 'default', 'value' => null],
            [['paciente_id', 'medico_id'], 'integer'],
            [['data_consulta', 'criado_em'], 'safe'],
            [['estado'], 'string', 'max' => 20],
            [['observacoes'], 'string', 'max' => 255],
            [['medico_id'], 'exist', 'skipOnError' => true, 'targetClass' => Medico::class, 'targetAttribute' => ['medico_id' => 'medico_id']],
            [['paciente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paciente::class, 'targetAttribute' => ['paciente_id' => 'paciente_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'consulta_id' => 'Consulta ID',
            'paciente_id' => 'Paciente ID',
            'medico_id' => 'Medico ID',
            'data_consulta' => 'Data Consulta',
            'estado' => 'Estado',
            'observacoes' => 'Observacoes',
            'criado_em' => 'Criado Em',
        ];
    }

    /**
     * Gets query for [[Medico]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedico()
    {
        return $this->hasOne(Medico::class, ['medico_id' => 'medico_id']);
    }

    /**
     * Gets query for [[Paciente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaciente()
    {
        return $this->hasOne(Paciente::class, ['paciente_id' => 'paciente_id']);
    }

    /**
     * Gets query for [[Prescricos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescricos()
    {
        return $this->hasMany(Prescrico::class, ['consulta_id' => 'consulta_id']);
    }

}
