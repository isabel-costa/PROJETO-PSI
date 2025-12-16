<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "prescricoes_medicamentos".
 *
 * @property int $id
 * @property int $prescricao_id
 * @property int $medicamento_id
 * @property string $posologia
 * @property string $frequencia
 * @property int $duracao_dias
 * @property string $instrucoes
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
            [['prescricao_id', 'medicamento_id', 'posologia', 'frequencia', 'duracao_dias', 'instrucoes'], 'required'],
            [['prescricao_id', 'medicamento_id', 'duracao_dias'], 'integer'],
            [['posologia'], 'string', 'max' => 500],
            [['frequencia'], 'string', 'max' => 50],
            [['instrucoes'], 'string', 'max' => 255],
            [['medicamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Medicamento::class, 'targetAttribute' => ['medicamento_id' => 'id']],
            [['prescricao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Prescricao::class, 'targetAttribute' => ['prescricao_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'prescricao_id' => 'ID Prescrição',
            'medicamento_id' => 'ID Medicamento',
            'posologia' => 'Posologia',
            'frequencia' => 'Frequência',
            'duracao_dias' => 'Duração (Dias)',
            'instrucoes' => 'Instruções',
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
        return $this->hasMany(RegistoToma::class, ['registo_toma_id' => 'id']);
    }

    public function fields()
    {
        $fields = parent::fields();
        unset($fields['medicamento_id'], $fields['prescricao_id']);

        $fields['medicamento'] = function ($model) {
            return $model->medicamento ? [
                'nome' => $model->medicamento->nome,
                'descricao' => $model->medicamento->descricao,
                'dosagem' => $model->medicamento->dosagem,
                'fabricante' => $model->medicamento->fabricante,
            ] : null;
        };

        $fields['prescricao'] = function ($model) {
            return $model->prescricao ? [
                'data_prescricao' => $model->prescricao->data_prescricao,
                'observacoes' => $model->prescricao->observacoes,
            ] : null;
        };

        return $fields;
    }
}
