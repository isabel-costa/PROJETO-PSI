<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "prescricoes_medicamentos".
 *
 * @property int $prescricao_medicamento_id
 * @property int|null $prescricao_id
 * @property int|null $medicamento_id
 * @property string|null $posologia
 * @property string|null $frequencia
 * @property int|null $duracao_dias
 * @property string|null $instrucoes
 *
 * @property Medicamento $medicamento
 * @property Prescricao $prescricao
 * @property RegistosToma[] $registosTomas
 */
class PrescricaoMedicamento extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'prescricoes_medicamentos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['prescricao_id', 'medicamento_id', 'posologia', 'frequencia', 'duracao_dias', 'instrucoes'], 'default', 'value' => null],
            [['prescricao_id', 'medicamento_id', 'duracao_dias'], 'integer'],
            [['posologia'], 'string'],
            [['frequencia'], 'string', 'max' => 50],
            [['instrucoes'], 'string', 'max' => 255],
            [['medicamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Medicamento::class, 'targetAttribute' => ['medicamento_id' => 'medicamento_id']],
            [['prescricao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Prescrico::class, 'targetAttribute' => ['prescricao_id' => 'prescricao_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prescricao_medicamento_id' => 'Prescricao Medicamento ID',
            'prescricao_id' => 'Prescricao ID',
            'medicamento_id' => 'Medicamento ID',
            'posologia' => 'Posologia',
            'frequencia' => 'Frequencia',
            'duracao_dias' => 'Duracao Dias',
            'instrucoes' => 'Instrucoes',
        ];
    }

    /**
     * Gets query for [[Medicamento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMedicamento()
    {
        return $this->hasOne(Medicamento::class, ['medicamento_id' => 'medicamento_id']);
    }

    /**
     * Gets query for [[Prescricao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescricao()
    {
        return $this->hasOne(Prescrico::class, ['prescricao_id' => 'prescricao_id']);
    }

    /**
     * Gets query for [[RegistosTomas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegistosTomas()
    {
        return $this->hasMany(RegistosToma::class, ['prescricao_medicamento_id' => 'prescricao_medicamento_id']);
    }

}
