<?php

namespace common\tests\unit\models;

use common\models\Consulta;
use common\models\Medico;
use Codeception\Test\Unit;

class ConsultaTest extends Unit
{
    protected function criarMedicoFake($horario = '9:00 - 19:00')
    {
        $medico = new Medico();
        $medico->id = 1;
        $medico->horario_trabalho = $horario;
        return $medico;
    }

    public function testEstadoInvalido()
    {
        $consulta = new Consulta();
        $consulta->medico_id = 1;
        $consulta->data_consulta = '2026-01-05 10:00:00';
        $consulta->estado = 'Inventado';

        $this->assertFalse($consulta->validate(['estado']));
    }

    public function testEstadoValido()
    {
        $consulta = new Consulta();
        $consulta->medico_id = 1;
        $consulta->data_consulta = '2026-01-05 10:00:00';
        $consulta->estado = Consulta::ESTADO_AGENDADA;

        $this->assertTrue($consulta->validate(['estado']));
    }

    public function testEstadoInicial()
    {
        $consulta = new Consulta();
        $consulta->medico_id = 1;
        $consulta->data_consulta = '2026-01-05 10:00:00';

        $consulta->validate();

        $this->assertEquals(Consulta::ESTADO_PENDENTE, $consulta->estado);
    }

    public function testNaoPermiteConsultaAoDomingo()
    {
        $consulta = new Consulta();
        $consulta->medico_id = 1;
        $consulta->data_consulta = '2026-01-04 10:00:00';

        $consulta->populateRelation('medico', $this->criarMedicoFake());

        $this->assertFalse($consulta->validate(['data_consulta']));
    }

    public function testNaoPermiteConsultaForaDoHorario()
    {
        $consulta = new Consulta();
        $consulta->medico_id = 1;
        $consulta->data_consulta = '2026-01-05 20:00:00';

        $consulta->populateRelation('medico', $this->criarMedicoFake('9:00 - 19:00'));

        $this->assertFalse($consulta->validate(['data_consulta']));
    }

    public function testPermiteConsultaDentroDoHorario()
    {
        $consulta = new Consulta();
        $consulta->medico_id = 1;
        $consulta->data_consulta = '2026-01-05 10:00:00';

        $consulta->populateRelation('medico', $this->criarMedicoFake('9:00 - 19:00'));

        $this->assertTrue($consulta->validate(['data_consulta']));
    }
}
