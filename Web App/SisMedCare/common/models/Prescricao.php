<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "prescricoes".
 *
 * @property int $id
 * @property int|null $consulta_id
 * @property int|null $medico_id
 * @property int|null $paciente_id
 * @property string|null $data_prescricao
 * @property string|null $observacoes
 *
 * @property Consulta $consulta
 * @property Medico $medico
 * @property Paciente $paciente
 * @property PrescricaoMedicamento[] $prescricoesMedicamentos
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
            [['consulta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Consultas::class, 'targetAttribute' => ['consulta_id' => 'id']],
            [['medico_id'], 'exist', 'skipOnError' => true, 'targetClass' => Medicos::class, 'targetAttribute' => ['medico_id' => 'id']],
            [['paciente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pacientes::class, 'targetAttribute' => ['paciente_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
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
        return $this->hasOne(Consulta::class, ['id' => 'consulta_id']);
    }

    /**
     * Gets query for [[Medico]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedico()
    {
        return $this->hasOne(Medico::class, ['id' => 'medico_id']);
    }

    /**
     * Gets query for [[Paciente]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaciente()
    {
        return $this->hasOne(Paciente::class, ['id' => 'paciente_id']);
    }

    /**
     * Gets query for [[PrescricoesMedicamentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescricoesMedicamentos()
    {
        return $this->hasMany(PrescricaoMedicamento::class, ['prescricao_id' => 'id']);
    }

}
