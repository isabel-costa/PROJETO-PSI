<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;

/**
 * Class LoginCest
 */
class LoginCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php',
            ],
        ];
    }

    public function loginAdmin(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');

        $I->see('Iniciar Sessão', 'p');

        $I->fillField('input[name="LoginForm[username]"]', 'admin');
        $I->fillField('input[name="LoginForm[password]"]', 'password123');

        $I->seeElement('#loginform-rememberme');
        $I->see('Remember Me');
        $I->checkOption('#loginform-rememberme');

        $I->click('button[type=submit]');

        $I->see('Sair');
        $I->dontSeeLink('Iniciar Sessão');
    }

    public function loginSecretaria(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');

        $I->see('Iniciar Sessão', 'p');

        $I->fillField('input[name="LoginForm[username]"]', 'secretaria1');
        $I->fillField('input[name="LoginForm[password]"]', 'password123');

        $I->seeElement('#loginform-rememberme');
        $I->see('Remember Me');
        $I->checkOption('#loginform-rememberme');

        $I->click('button[type=submit]');

        $I->see('Sair');
        $I->dontSeeLink('Iniciar Sessão');
    }

    public function loginRoleSemAcesso(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');

        $I->see('Iniciar Sessão', 'p');

        $I->fillField('input[name="LoginForm[username]"]', 'medico1');
        $I->fillField('input[name="LoginForm[password]"]', 'password123');

        $I->seeElement('#loginform-rememberme');
        $I->see('Remember Me');
        $I->checkOption('#loginform-rememberme');

        $I->click('button[type=submit]');

        $I->see('Apenas administradores e secretárias podem aceder ao Backend.');
    }

    public function loginUserInexistente(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');

        $I->see('Iniciar Sessão', 'p');

        $I->fillField('input[name="LoginForm[username]"]', 'user_not_exist');
        $I->fillField('input[name="LoginForm[password]"]', 'password_not_exist');

        $I->seeElement('#loginform-rememberme');
        $I->see('Remember Me');
        $I->checkOption('#loginform-rememberme');

        $I->click('button[type=submit]');

        $I->see('Incorrect username or password.');
    }

    public function loginSemCampos(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');

        $I->see('Iniciar Sessão', 'p');

        $I->fillField('input[name="LoginForm[username]"]', '');
        $I->fillField('input[name="LoginForm[password]"]', '');

        $I->seeElement('#loginform-rememberme');
        $I->see('Remember Me');
        $I->checkOption('#loginform-rememberme');

        $I->click('button[type=submit]');

        $I->see('Username cannot be blank.');
        $I->see('Password cannot be blank.');
    }
}
