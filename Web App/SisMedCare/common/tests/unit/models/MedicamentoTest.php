<?php

namespace common\tests\unit\models;

use common\models\Medicamento;
use Codeception\Test\Unit;

class MedicamentoTest extends Unit
{
    protected function criarMedicamento($nome = 'Paracetamol')
    {
        $medicamento = new Medicamento();
        $medicamento->nome = $nome;
        $medicamento->descricao = 'Analgésico e antipirético';
        $medicamento->dosagem = '500mg';
        $medicamento->fabricante = 'Fabricante X';
        return $medicamento;
    }

    public function testCamposObrigatorios()
    {
        $medicamento = new Medicamento();

        $this->assertFalse($medicamento->validate(['nome']));
        $this->assertFalse($medicamento->validate(['descricao']));
        $this->assertFalse($medicamento->validate(['dosagem']));
        $this->assertFalse($medicamento->validate(['fabricante']));
    }

    public function testNomeMaxLength()
    {
        $medicamento = $this->criarMedicamento();
        $medicamento->nome = str_repeat('a', 151);

        $this->assertFalse($medicamento->validate(['nome']));
    }

    public function testDescricaoMaxLength()
    {
        $medicamento = $this->criarMedicamento();
        $medicamento->descricao = str_repeat('a', 256);

        $this->assertFalse($medicamento->validate(['descricao']));
    }

    public function testDosagemMaxLength()
    {
        $medicamento = $this->criarMedicamento();
        $medicamento->dosagem = str_repeat('a', 51);

        $this->assertFalse($medicamento->validate(['dosagem']));
    }

    public function testFabricanteMaxLength()
    {
        $medicamento = $this->criarMedicamento();
        $medicamento->fabricante = str_repeat('a', 101);

        $this->assertFalse($medicamento->validate(['fabricante']));
    }

    public function testNomeUnico()
    {
        $medicamento1 = $this->criarMedicamento('Paracetamol');
        $medicamento1->save(false);
        $medicamento2 = $this->criarMedicamento('Paracetamol');

        $this->assertFalse($medicamento2->validate(['nome']));
    }

    public function testMedicamentoValido()
    {
        $medicamento = $this->criarMedicamento('Ibuprofeno');

        $this->assertTrue($medicamento->validate());
    }
}
