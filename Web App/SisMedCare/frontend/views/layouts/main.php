<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

/* Register external CSS/JS via Yii view methods */
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css');
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css');
$this->registerJsFile('https://code.jquery.com/jquery-3.6.0.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', ['position' => \yii\web\View::POS_END]);
// Per-page CSS
$this->registerCssFile('@web/css/site.css');
// Main template stylesheet (registered instead of static link)
$this->registerCssFile('@web/css/style.css');



AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header>
        <?php
        /* navbar */
        NavBar::begin([
            /* Logo do site */
                'brandLabel' => Html::img('@web/img/SisMedCare_Logo.png', ['alt' => 'SisMedCare', 'height' => '45']),
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                        'class' => 'navbar navbar-expand-md navbar-dark navbar-smc fixed-top',
                ],
        ]);
        /* Botões de navegação */
        $menuItems = [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'Contact', 'url' => ['/site/contact'], 'linkOptions' => ['class' => 'nav-link me-5']],
        ];

        echo Nav::widget([
                'options' => ['class' => 'navbar-nav ms-auto mb-2 mb-md-0'],
                'items' => $menuItems,
        ]);
        /* Botão de login */
        if (Yii::$app->user->isGuest) {
            echo Html::tag('div',Html::a('Login',['/site/login'],['class' => ['btn btn-link login text-decoration-none']]),['class' => ['d-flex']]);
        } else {
            echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
                    . Html::submitButton(
                            'Logout (' . Yii::$app->user->identity->username . ')',
                            ['class' => 'btn btn-link logout text-decoration-none']
                    )
                    . Html::endForm();
        }
        NavBar::end();
        ?>
    </header>

    <main role="main" class="flex-shrink-0">
        <div class="container">
            <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <!-- Footer -->
     <footer>
        <div class="container-fluid footer-smc text-dark mt-5 py-2">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <div class="col-lg-3 col-md-6 text-center">
                            <?= Html::img('@web/img/SisMedCare_Logo.png', ['class' => 'img-fluid footer-img', 'alt' => 'Logo SisMedCare']) ?>
                        </div>
                        <br>
                        <p class="mb-4">No dolore ipsum accusam no lorem. Invidunt sed clita kasd clita et et dolor sed dolor</p>
                        <h6 class="cor-secundaria-smc text-uppercase mt-4 mb-3">Redes Sociais</h6>
                        <div class="d-flex gap-2">
                        <a class="social-icon twitter" href="#"><i class="fab fa-twitter"></i></a>
                        <a class="social-icon facebook" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="social-icon linkedin" href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a class="social-icon instagram" href="#"><i class="fab fa-instagram"></i></a>
                    </div>

                    </div>
                    <div class="col-lg-3 col-md-6 separador-centrar-footer">
                        <h4 class="d-inline-block cor-secundaria-smc text-uppercase mb-4">SisMedCare</h4>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-dark mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Home</a>
                            <a class="text-dark mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Contacte-nos</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h4 class="d-inline-block cor-secundaria-smc text-uppercase mb-4">Contacte-nos</h4>
                        <p class="mb-2"><i class="fa fa-map-marker-alt cor-primaria-smc me-3"></i>Rua das acacias, Leiria, Portugal</p>
                        <p class="mb-2"><i class="fa fa-envelope cor-primaria-smc me-3"></i>estudante@my.ipleiria.pt</p>
                        <p class="mb-0"><i class="fa fa-phone-alt cor-primaria-smc me-3"></i>+351 915249823</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid footer-smc text-dark py-4">
            <div class="container text-center">
                <div class="row g-5">
                    <div class="separator-rights-reserved md-3"></div>
                    <p class="mb-md-0 mt-3">&copy; <a class="cor-secundaria-smc" href="#" style="margin-top: 50px;">2025</a> SisMedCare</p>
                </div>
            </div>
        </div>
        </Footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();