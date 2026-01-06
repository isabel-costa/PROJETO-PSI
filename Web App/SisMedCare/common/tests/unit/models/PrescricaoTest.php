<?php

namespace common\tests\unit\models;

use common\models\Prescricao;
use common\models\Consulta;
use common\models\Medico;
use common\models\Paciente;
use Codeception\Test\Unit;

class PrescricaoTest extends Unit
{
    protected function criarMedicoFake()
    {
        $medico = new Medico();
        $medico->id = 1;
        $medico->nome_completo = 'Dr. Fulano';
        $medico->cedula_numero = '12345';
        $medico->horario_trabalho = '9:00 - 19:00';
        return $medico;
    }

    protected function criarPacienteFake()
    {
        $paciente = new Paciente();
        $paciente->id = 1;
        $paciente->nome_completo = 'Paciente Teste';
        $paciente->numero_utente = '67890';
        return $paciente;
    }

    protected function criarConsultaFake($estado = Consulta::ESTADO_AGENDADA, $data = '2026-01-05 10:00:00')
    {
        $consulta = new Consulta();
        $consulta->id = 1;
        $consulta->medico_id = 1;
        $consulta->data_consulta = $data;
        $consulta->estado = $estado;
        $consulta->populateRelation('medico', $this->criarMedicoFake());
        return $consulta;
    }

    public function testCamposObrigatorios()
    {
        $prescricao = new Prescricao();

        $this->assertFalse($prescricao->validate(['consulta_id']));
        $this->assertFalse($prescricao->validate(['medico_id']));
        $this->assertFalse($prescricao->validate(['paciente_id']));
        $this->assertFalse($prescricao->validate(['data_prescricao']));
        $this->assertFalse($prescricao->validate(['observacoes']));
    }

    public function testConsultaDeveEstarAgendada()
    {
        $prescricao = new Prescricao();
        $prescricao->consulta_id = 1;
        $prescricao->medico_id = 1;
        $prescricao->paciente_id = 1;
        $prescricao->data_prescricao = '2026-01-05 11:00:00';
        $prescricao->observacoes = 'Teste';

        $consulta = $this->criarConsultaFake(Consulta::ESTADO_PENDENTE); // não agendada
        $prescricao->populateRelation('consulta', $consulta);

        $this->assertFalse($prescricao->validate(['consulta_id']));
    }

    public function testDataPrescricaoNaoPodeSerAntesConsulta()
    {
        $prescricao = new Prescricao();
        $prescricao->consulta_id = 1;
        $prescricao->medico_id = 1;
        $prescricao->paciente_id = 1;
        $prescricao->data_prescricao = '2026-01-04 10:00:00'; // antes da consulta
        $prescricao->observacoes = 'Teste';

        $consulta = $this->criarConsultaFake(Consulta::ESTADO_AGENDADA, '2026-01-05 10:00:00');
        $prescricao->populateRelation('consulta', $consulta);

        $this->assertFalse($prescricao->validate(['data_prescricao']));
    }

    public function testPrescricaoValida()
    {
        $prescricao = new Prescricao();
        $prescricao->consulta_id = 1;
        $prescricao->medico_id = 1;
        $prescricao->paciente_id = 1;
        $prescricao->data_prescricao = '2026-01-05 11:00:00'; // após a consulta
        $prescricao->observacoes = 'Tomar 1 comprimido';

        $consulta = $this->criarConsultaFake(Consulta::ESTADO_AGENDADA, '2026-01-05 10:00:00');
        $prescricao->populateRelation('consulta', $consulta);
        $prescricao->populateRelation('medico', $this->criarMedicoFake());
        $prescricao->populateRelation('paciente', $this->criarPacienteFake());

        $this->assertTrue($prescricao->validate());
    }
}
