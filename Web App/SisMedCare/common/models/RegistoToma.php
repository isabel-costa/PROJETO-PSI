<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "registos_tomas".
 *
 * @property int $id
 * @property int $paciente_id
 * @property int $prescricao_medicamento_id
 * @property string $data_toma
 * @property int $foi_tomado
 *
 * @property Paciente $paciente
 * @property PrescricaoMedicamento $prescricaoMedicamento
 */
class RegistoToma extends \yii\db\ActiveRecord
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
            [['foi_tomado'], 'default', 'value' => 0],
            [['paciente_id', 'prescricao_medicamento_id', 'data_toma'], 'required'],
            [['paciente_id', 'prescricao_medicamento_id', 'foi_tomado'], 'integer'],
            [['data_toma'], 'safe'],
            [['paciente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paciente::class, 'targetAttribute' => ['paciente_id' => 'id']],
            [['prescricao_medicamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => PrescricaoMedicamento::class, 'targetAttribute' => ['prescricao_medicamento_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID Registo Toma',
            'paciente_id' => 'ID Paciente',
            'prescricao_medicamento_id' => 'ID Prescrição Medicamento',
            'data_toma' => 'Data da Toma',
            'foi_tomado' => 'Foi Tomado?',
        ];
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
     * Gets query for [[PrescricaoMedicamento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescricaoMedicamento()
    {
        return $this->hasOne(PrescricaoMedicamento::class, ['id' => 'prescricao_medicamento_id']);
    }

}
