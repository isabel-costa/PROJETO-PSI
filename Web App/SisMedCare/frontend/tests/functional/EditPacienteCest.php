<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;
use common\fixtures\UserFixture;
use common\fixtures\PacienteFixture;

class EditPacienteCest
{
    /**
     * Load fixtures before db transaction begin
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php',
            ],
            'paciente' => [
                'class' => PacienteFixture::class,
                'dataFile' => codecept_data_dir() . 'paciente_data.php',
            ],
        ];
    }

    public function _before(FunctionalTester $I)
    {
        // Login como médico
        $I->amOnPage('/site/login');
        $I->fillField('input[name="LoginForm[username]"]', 'medico1');
        $I->fillField('input[name="LoginForm[password]"]', 'password123');
        $I->click('button[type=submit]');
        $I->see('Logout (medico1)');
    }

    public function editPacienteSucesso(FunctionalTester $I)
    {
        // Assume que o paciente com id 1 existe no fixture
        $I->amOnPage('/paciente/update?id=1');

        // Verifica se os campos estão presentes
        $I->seeElement('input[name="Paciente[altura]"]');
        $I->seeElement('input[name="Paciente[peso]"]');
        $I->seeElement('input[name="Paciente[doencas_cronicas]"]');
        $I->seeElement('input[name="Paciente[alergias]"]');

        // Preenche os campos
        $I->fillField('input[name="Paciente[altura]"]', '175');
        $I->fillField('input[name="Paciente[peso]"]', '70');
        $I->fillField('input[name="Paciente[doencas_cronicas]"]', 'Hipertensão');
        $I->fillField('input[name="Paciente[alergias]"]', 'Nenhuma');

        // Submete o formulário
        $I->click('Guardar');

        // Verifica mensagem de sucesso ou redirecionamento
        $I->see('Paciente atualizado com sucesso'); // Ajuste conforme sua flash message
        $I->seeInCurrentUrl('/paciente/view'); // Se redireciona para view
    }
}
