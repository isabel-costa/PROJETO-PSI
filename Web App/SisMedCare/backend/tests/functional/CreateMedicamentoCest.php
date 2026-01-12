<?php

declare(strict_types=1);

namespace backend\tests\Functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;

final class CreateMedicamentoCest
{
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php',
            ],
        ];
    }

    private function loginAdmin(FunctionalTester $I): void
    {
        $I->amOnPage('/site/login');
        $I->fillField('input[name="LoginForm[username]"]', 'admin');
        $I->fillField('input[name="LoginForm[password]"]', 'password123');
        $I->click('button[type=submit]');
        $I->see('Sair');
    }

    public function adminAcedePaginaCriarMedicamento(FunctionalTester $I): void
    {
        $this->loginAdmin($I);

        $I->amOnPage('/medicamento/create');
        $I->see('Criar Medicamento', 'h1');
    }

    public function adminCriaMedicamentoComSucesso(FunctionalTester $I): void
    {
        $this->loginAdmin($I);

        $I->amOnPage('/medicamento/create');

        $I->fillField('input[name="Medicamento[nome]"]', 'Clavamox');
        $I->fillField('input[name="Medicamento[descricao]"]', 'Anti-inflamatório');
        $I->fillField('input[name="Medicamento[dosagem]"]', '400mg');
        $I->fillField('input[name="Medicamento[fabricante]"]', 'Farmácia Teste');

        $I->click('button[type=submit]');

        $I->see('Clavamox');
        $I->see('Anti-inflamatório');
    }
}
