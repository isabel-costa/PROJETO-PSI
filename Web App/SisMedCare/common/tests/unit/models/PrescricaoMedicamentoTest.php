<?php

namespace common\tests\unit\models;

use common\models\PrescricaoMedicamento;
use common\models\Prescricao;
use common\models\Medicamento;
use common\models\Consulta;
use common\models\Medico;
use common\models\Paciente;
use Codeception\Test\Unit;

class PrescricaoMedicamentoTest extends Unit
{
    protected function criarMedicamento()
    {
        $medicamento = new Medicamento();
        $medicamento->nome = 'Paracetamol ' . uniqid();
        $medicamento->descricao = 'Analgésico';
        $medicamento->dosagem = '500mg';
        $medicamento->fabricante = 'Farmácia X';
        $medicamento->save(false);

        return $medicamento;
    }

    protected function criarPrescricao()
    {
        $prescricao = new Prescricao();

        $prescricao->id = 1;
        $prescricao->consulta_id = 1;
        $prescricao->medico_id = 1;
        $prescricao->paciente_id = 1;

        $prescricao->data_prescricao = '2026-01-05 11:00:00';
        $prescricao->observacoes = 'Observações teste';

        return $prescricao;
    }

    public function testCamposObrigatorios()
    {
        $pm = new PrescricaoMedicamento();

        $this->assertFalse($pm->validate(['prescricao_id']));
        $this->assertFalse($pm->validate(['medicamento_id']));
        $this->assertFalse($pm->validate(['posologia']));
        $this->assertFalse($pm->validate(['frequencia']));
        $this->assertFalse($pm->validate(['duracao_dias']));
        $this->assertFalse($pm->validate(['instrucoes']));
    }

    public function testMaxLengthCamposTexto()
    {
        $pm = new PrescricaoMedicamento();
        $pm->posologia = str_repeat('a', 501);
        $pm->frequencia = str_repeat('a', 51);
        $pm->instrucoes = str_repeat('a', 256);

        $this->assertFalse($pm->validate(['posologia']));
        $this->assertFalse($pm->validate(['frequencia']));
        $this->assertFalse($pm->validate(['instrucoes']));
    }

    public function testCamposInteirosInvalidos()
    {
        $pm = new PrescricaoMedicamento();
        $pm->prescricao_id = 'abc';
        $pm->medicamento_id = 'xyz';
        $pm->duracao_dias = 'dez';

        $this->assertFalse($pm->validate(['prescricao_id']));
        $this->assertFalse($pm->validate(['medicamento_id']));
        $this->assertFalse($pm->validate(['duracao_dias']));
    }

    public function testPrescricaoMedicamentoValido()
    {
        $prescricao = $this->criarPrescricao();

        $pm = new PrescricaoMedicamento();
        $pm->prescricao_id = $prescricao->id;
        $pm->medicamento_id = 1;
        $pm->posologia = '1 comprimido';
        $pm->frequencia = '3 vezes por dia';
        $pm->duracao_dias = 7;
        $pm->instrucoes = 'Tomar após refeições';

        $this->assertTrue(
            $pm->validate([
                'posologia',
                'frequencia',
                'duracao_dias',
                'instrucoes'
            ])
        );
    }
}
