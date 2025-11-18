<?php

namespace common\models;

use common\models\Medico;
use Yii;

/**
 * This is the model class for table "consultas".
 *
 * @property int $id
 * @property int|null $paciente_id
 * @property int|null $medico_id
 * @property string|null $data_consulta
 * @property string|null $estado
 * @property string|null $observacoes
 * @property string|null $criado_em
 *
 * @property Medico $medico
 * @property Paciente $paciente
 * @property Prescricao[] $prescricao
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
     * Gets query for [[Prescricoes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPrescricoes()
    {
        return $this->hasMany(Prescricao::class, ['consulta_id' => 'id']);
    }

    public static function countEstado($estado)
    {
        return self::find()->where(['estado' => $estado])->count();
    }

    public static function consultasPorMes()
    {
        return self::find()
            ->select([
                "mes" => "DATE_FORMAT(data_consulta, '%Y-%b')",
                "count" => "COUNT(*)"
            ])
            ->groupBy("YEAR(data_consulta), MONTH(data_consulta)")
            ->orderBy("MIN(data_consulta)")
            ->asArray()
            ->all();
    }
}
