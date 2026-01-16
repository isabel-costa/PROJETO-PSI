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
    public $total;

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
            [['consulta_id', 'medico_id', 'paciente_id', 'data_prescricao', 'observacoes'], 'required'],
            [['consulta_id', 'medico_id', 'paciente_id'], 'integer'],
            [['data_prescricao'], 'safe'],
            [['observacoes'], 'string', 'max' => 255],
            [['consulta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Consulta::class, 'targetAttribute' => ['consulta_id' => 'id']],
            [['medico_id'], 'exist', 'skipOnError' => true, 'targetClass' => Medico::class, 'targetAttribute' => ['medico_id' => 'id']],
            [['paciente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paciente::class, 'targetAttribute' => ['paciente_id' => 'id']],
            [['data_prescricao'], 'validateDataPrescricao'],
            [['consulta_id'], 'validateConsultaAgendada'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID Prescrição',
            'consulta_id' => 'ID Consulta',
            'medico_id' => 'ID Médico',
            'paciente_id' => 'ID Paciente',
            'data_prescricao' => 'Data da Prescrição',
            'observacoes' => 'Observações',
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
        return $this->hasMany(PrescricaoMedicamento::class, ['prescricao_medicamento_id' => 'id']);
    }

    public static function countPorMedico($medicoId)
    {
        return self::find()->where(['medico_id' => $medicoId])->count();
    }

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['medico_id']);

        $fields['medico'] = function ($model) {
            return $model->medico ? [
                'nome' => $model->medico->nome_completo,
            ] : null;
        };

        return $fields;
    }

    public function validateDataPrescricao($attribute)
    {
        if (!$this->consulta) {
            return;
        }

        $dataConsulta = strtotime($this->consulta->data_consulta);
        $dataPrescricao = strtotime($this->$attribute);

        if ($dataPrescricao < $dataConsulta) {
            $this->addError(
                $attribute,
                'A prescrição não pode ser criada antes da data e hora da consulta.'
            );
        }
    }

    public function validateConsultaAgendada($attribute)
    {
        if (!$this->consulta) {
            return;
        }

        if ($this->consulta->estado !== Consulta::ESTADO_AGENDADA) {
            $this->addError(
                $attribute,
                'Só é possível criar prescrições para consultas com estado "agendada".'
            );
        }
    }
}
