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
        unset($fields['medico_id'], $fields['paciente_id'], $fields['consulta_id']);

        $fields['consulta'] = function ($model) {
            return $model->consulta ? [
                'data_consulta' => $model->consulta->data_consulta,
                'estado' => $model->consulta->estado,
                'observacoes' => $model->consulta->observacoes,
            ] : null;
        };

        $fields['medico'] = function ($model) {
            return $model->medico ? [
                'nome' => $model->medico->nome_completo,
                'cedula_numero' => $model->medico->cedula_numero,
            ] : null;
        };

        $fields['paciente'] = function ($model) {
            return $model->paciente ? [
                'nome' => $model->paciente->nome_completo,
                'numero_utente' => $model->paciente->numero_utente,
            ] : null;
        };

        return $fields;
    }

    /*public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->medico_id = Yii::$app->user->identity->medico->id;

                if (!$this->paciente_id && Yii::$app->request->post('paciente_id')) {
                    $this->paciente_id = Yii::$app->request->post('paciente_id');
                }

                if (!$this->consulta_id && Yii::$app->request->post('consulta_id')) {
                    $this->consulta_id = Yii::$app->request->post('consulta_id');
                }
            }
            return true;
        }
        return false;
    }*/
}
