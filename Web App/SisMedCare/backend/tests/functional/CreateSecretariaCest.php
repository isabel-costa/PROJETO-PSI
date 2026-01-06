<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;

/**
 * Class CreateSecretariaCest
 */
class CreateSecretariaCest
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

    public function adminAcedePaginaCriarSecretaria(FunctionalTester $I)
    {
        $this->loginAdmin($I);

        $I->amOnPage('/user/create');
        $I->see('Criar Secretária', 'h1');
    }

    public function adminCriaSecretariaComSucesso(FunctionalTester $I)
    {
        $this->loginAdmin($I);

        $I->amOnPage('/user/create');

        $I->fillField('input[name="User[username]"]', 'secretaria_teste');
        $I->fillField('input[name="User[email]"]', 'secretariateste@test.com');
        $I->fillField('input[name="User[password]"]', 'password123456');

        $I->click('button[type=submit]');
        $I->see('secretaria_teste');
    }
}
