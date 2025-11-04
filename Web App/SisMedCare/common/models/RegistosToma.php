<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "registos_tomas".
 *
 * @property int $registo_toma_id
 * @property int $paciente_id
 * @property int $prescricao_medicamento_id
 * @property string|null $data_toma
 * @property int|null $foi_tomado
 *
 * @property Paciente $paciente
 * @property PrescricaoMedicamento $prescricaoMedicamento
 */
class RegistosToma extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registos_tomas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data_toma'], 'default', 'value' => null],
            [['foi_tomado'], 'default', 'value' => 0],
            [['paciente_id', 'prescricao_medicamento_id'], 'required'],
            [['paciente_id', 'prescricao_medicamento_id', 'foi_tomado'], 'integer'],
            [['data_toma'], 'safe'],
            [['paciente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paciente::class, 'targetAttribute' => ['paciente_id' => 'paciente_id']],
            [['prescricao_medicamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => PrescricoesMedicamento::class, 'targetAttribute' => ['prescricao_medicamento_id' => 'prescricao_medicamento_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'registo_toma_id' => 'Registo Toma ID',
            'paciente_id' => 'Paciente ID',
            'prescricao_medicamento_id' => 'Prescricao Medicamento ID',
            'data_toma' => 'Data Toma',
            'foi_tomado' => 'Foi Tomado',
        ];
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
     * Gets query for [[PrescricaoMedicamento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescricaoMedicamento()
    {
        return $this->hasOne(PrescricoesMedicamento::class, ['prescricao_medicamento_id' => 'prescricao_medicamento_id']);
    }

}
