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

        $I->fillField('input[name="LoginForm[username]"]', 'admin');
        $I->fillField('input[name="LoginForm[password]"]', 'password123');

        $I->click('button[type=submit]');

        $I->see('Sair');
        $I->dontSeeLink('Signup');
    }

    public function loginSecretaria(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');

        $I->fillField('input[name="LoginForm[username]"]', 'secretaria1');
        $I->fillField('input[name="LoginForm[password]"]', 'password123');

        $I->click('button[type=submit]');

        $I->see('Sair');
        $I->dontSeeLink('Signup');
    }
}
