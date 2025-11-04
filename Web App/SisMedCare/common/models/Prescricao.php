<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "prescricoes".
 *
 * @property int $prescricao_id
 * @property int|null $consulta_id
 * @property int|null $medico_id
 * @property int|null $paciente_id
 * @property string|null $data_prescricao
 * @property string|null $observacoes
 *
 * @property Consulta $consulta
 * @property Medico $medico
 * @property Paciente $paciente
 * @property PrescricaoMedicamento[] $prescricaoMedicamento
 */
class Prescricao extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prescricoes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['consulta_id', 'medico_id', 'paciente_id', 'data_prescricao', 'observacoes'], 'default', 'value' => null],
            [['consulta_id', 'medico_id', 'paciente_id'], 'integer'],
            [['data_prescricao'], 'safe'],
            [['observacoes'], 'string', 'max' => 255],
            [['consulta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Consulta::class, 'targetAttribute' => ['consulta_id' => 'consulta_id']],
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
            'prescricao_id' => 'Prescricao ID',
            'consulta_id' => 'Consulta ID',
            'medico_id' => 'Medico ID',
            'paciente_id' => 'Paciente ID',
            'data_prescricao' => 'Data Prescricao',
            'observacoes' => 'Observacoes',
        ];
    }

    /**
     * Gets query for [[Consulta]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getConsulta()
    {
        return $this->hasOne(Consulta::class, ['consulta_id' => 'consulta_id']);
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
     * Gets query for [[PrescricoesMedicamentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescricoesMedicamentos()
    {
        return $this->hasMany(PrescricaoMedicamento::class, ['prescricao_id' => 'prescricao_id']);
    }

}
