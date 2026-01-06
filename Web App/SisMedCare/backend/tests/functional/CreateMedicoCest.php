<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;

/**
 * Class CreateMedicoCest
 */
class CreateMedicoCest
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

    private function loginAdmin(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');
        $I->fillField('input[name="LoginForm[username]"]', 'admin');
        $I->fillField('input[name="LoginForm[password]"]', 'password123');
        $I->click('button[type=submit]');
        $I->see('Sair');
    }

    public function adminAcedePaginaCriarMedico(FunctionalTester $I)
    {
        $this->loginAdmin($I);

        $I->amOnPage('/medico/create');
        $I->see('Criar Médico', 'h1');
    }

    public function adminCriaMedicoComSucesso(FunctionalTester $I)
    {
        $this->loginAdmin($I);

        $I->amOnPage('/medico/create');

        $I->fillField('input[name="Medico[username]"]', 'medico_teste');
        $I->fillField('input[name="Medico[email]"]', 'medico_teste@test.com');
        $I->fillField('input[name="Medico[password]"]', '123456');
        $I->fillField('input[name="Medico[nome_completo]"]', 'Dr. João Silva');
        $I->fillField('input[name="Medico[especialidade]"]', 'Cardiologia');
        $I->fillField('input[name="Medico[nif]"]', '989123456');
        $I->fillField('input[name="Medico[telemovel]"]', '942123456');
        $I->fillField('input[name="Medico[cedula_numero]"]', '790912');
        $I->fillField('input[name="Medico[horario_trabalho]"]', '09:00 - 19:00');

        $I->click('button[type=submit]');
        $I->see('Dr. João Silva');
    }
}
