<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "medicamentos".
 *
 * @property int $id
 * @property string $nome
 * @property string|null $descricao
 * @property string|null $dosagem
 * @property string|null $fabricante
 *
 * @property PrescricaoMedicamento[] $prescricaoMedicamento
 */
class Medicamento extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'medicamentos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descricao', 'dosagem', 'fabricante'], 'default', 'value' => null],
            [['nome'], 'required'],
            [['nome'], 'string', 'max' => 150],
            [['descricao'], 'string', 'max' => 255],
            [['dosagem'], 'string', 'max' => 50],
            [['fabricante'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID Medicamento',
            'nome' => 'Nome',
            'descricao' => 'Descrição',
            'dosagem' => 'Dosagem',
            'fabricante' => 'Fabricante',
        ];
    }

    /**
     * Gets query for [[PrescricoesMedicamentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescricoesMedicamentos()
    {
        return $this->hasMany(PrescricaoMedicamento::class, ['medicamento_id' => 'id']);
    }

}
