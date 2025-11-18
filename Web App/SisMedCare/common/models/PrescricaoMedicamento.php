<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "prescricoes_medicamentos".
 *
 * @property int $id
 * @property int|null $prescricao_id
 * @property int|null $medicamento_id
 * @property string|null $posologia
 * @property string|null $frequencia
 * @property int|null $duracao_dias
 * @property string|null $instrucoes
 *
 * @property Medicamento $medicamento
 * @property Prescricao $prescricao
 * @property RegistoToma[] $registoToma
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
            [['medicamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Medicamentos::class, 'targetAttribute' => ['medicamento_id' => 'id']],
            [['prescricao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Prescricoes::class, 'targetAttribute' => ['prescricao_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
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
        return $this->hasOne(Medicamento::class, ['id' => 'medicamento_id']);
    }

    /**
     * Gets query for [[Prescricao]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescricao()
    {
        return $this->hasOne(Prescricao::class, ['id' => 'prescricao_id']);
    }

    /**
     * Gets query for [[RegistosTomas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegistosTomas()
    {
        return $this->hasMany(RegistoToma::class, ['prescricao_medicamento_id' => 'id']);
    }

}
