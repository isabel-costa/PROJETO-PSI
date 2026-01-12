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
 * @property string $data_consulta
 * @property string $estado
 * @property string|null $observacoes
 * @property string $criado_em
 *
 * @property Medico $medico
 * @property Paciente $paciente
 * @property Prescricao[] $prescricao
 */
class Consulta extends \yii\db\ActiveRecord
{

    const ESTADO_PENDENTE  = 'pendente';
    const ESTADO_AGENDADA  = 'agendada';
    const ESTADO_CONCLUIDA = 'concluida';
    const ESTADO_CANCELADA = 'cancelada';

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
            [['paciente_id', 'medico_id', 'observacoes'], 'default', 'value' => null],
            [['data_consulta', 'estado'], 'required'],
            [['paciente_id', 'medico_id'], 'integer'],
            [['criado_em'], 'safe'],
            ['estado', 'in', 'range' => [
                self::ESTADO_PENDENTE,
                self::ESTADO_AGENDADA,
                self::ESTADO_CONCLUIDA,
                self::ESTADO_CANCELADA,
            ]],
            ['data_consulta', 'validateDataConsulta'],
            [['observacoes'], 'string', 'max' => 255],
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
            'id' => 'ID Consulta',
            'paciente_id' => 'ID Paciente',
            'medico_id' => 'ID Médico',
            'data_consulta' => 'Data da Consulta',
            'estado' => 'Estado',
            'observacoes' => 'Observações',
            'criado_em' => 'Criado Em',
        ];
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord && empty($this->estado)) {
            $this->estado = self::ESTADO_PENDENTE;
        }
        return parent::beforeValidate();
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

    public static function countEstadoPorMedico($estado, $medicoId)
    {
        return self::find()
            ->where([
                'estado' => $estado,
                'medico_id' => $medicoId
            ])
            ->count();
    }

    public static function countHojePorMedico($medicoId)
    {
        return self::find()
            ->where([
                'medico_id' => $medicoId,
                'DATE(data_consulta)' => date('Y-m-d')
            ])
            ->count();
    }

    public static function consultasPorMesPorMedico($medicoId)
    {
        return self::find()
            ->select([
                "mes" => "DATE_FORMAT(data_consulta, '%Y-%b')",
                "count" => "COUNT(*)"
            ])
            ->where(['medico_id' => $medicoId])
            ->groupBy("YEAR(data_consulta), MONTH(data_consulta)")
            ->orderBy("MIN(data_consulta)")
            ->asArray()
            ->all();
    }

    public function validateDataConsulta($attribute)
    {
        if (empty($this->$attribute) || empty($this->medico_id)) {
            return;
        }

        $timestamp = strtotime($this->$attribute);

        // Impossibilitar marcação de consultas ao domingo
        if (date('w', $timestamp) == 0) {
            $this->addError($attribute, 'Não é possível marcar consultas ao domingo.');
            return;
        }

        // Impossibilitar marcação de consultas fora do horário de trabalho do médico
        $medico = $this->medico;

        if (!$medico || empty($medico->horario_trabalho)) {
            $this->addError($attribute, 'Horário do médico não definido.');
            return;
        }

        // Ex: "9:00 - 19:00"
        [$inicio, $fim] = array_map('trim', explode('-', $medico->horario_trabalho));

        $data = date('Y-m-d', $timestamp);

        $inicioHorarioMedico = strtotime("$data $inicio");
        $fimHorarioMedico    = strtotime("$data $fim");

        if ($timestamp < $inicioHorarioMedico || $timestamp > $fimHorarioMedico) {
            $this->addError(
                $attribute,
                "Consulta fora do horário do médico ({$medico->horario_trabalho})."
            );
            return;
        }

        // Impossibilitar marcação de consultas duplicadas (mesmo médico, data e hora)
        $existe = self::find()
            ->andWhere([
                'medico_id' => $this->medico_id,
                'data_consulta' => $this->$attribute,
            ])
            ->andWhere(['<>', 'id', $this->id])
            ->exists();

        if ($existe) {
            $this->addError(
                $attribute,
                'Já existe uma consulta marcada para este médico nesta data e hora.'
            );
        }
    }
}
