<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;
use common\fixtures\UserFixture;

class LoginCest
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

    public function _before(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');
        $I->see('Iniciar Sessão');
    }

    protected function formParams($login, $password)
    {
        return [
            'LoginForm[username]' => $login,
            'LoginForm[password]' => $password,
        ];
    }

    public function checkEmpty(FunctionalTester $I)
    {
        $I->submitForm('#login-form', $this->formParams('', ''));

        $I->see('Username cannot be blank.');
        $I->see('Password cannot be blank.');
    }

    public function checkWrongPassword(FunctionalTester $I)
    {
        $I->submitForm('#login-form', $this->formParams('medico1', 'wrong'));

        $I->see('Incorrect username or password.');
    }

    public function checkInactiveAccount(FunctionalTester $I)
    {
        $I->submitForm('#login-form', $this->formParams('test.test', 'Test1234'));

        $I->see('Incorrect username or password.');
    }

    public function checkValidLogin(FunctionalTester $I)
    {
        $I->submitForm('#login-form', $this->formParams('medico1', 'password123'));

        $I->see('Logout');
        $I->dontSeeLink('Iniciar Sessão');
    }
}
