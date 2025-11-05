<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Botão do menu lateral -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= Url::home() ?>" class="nav-link">Início</a>
        </li>
    </ul>

    <!-- Botões à direita -->
    <ul class="navbar-nav ml-auto">
        <?php if (!Yii::$app->user->isGuest): ?>
            <li class="nav-item">
                <?= Html::a('<i class="fas fa-sign-out-alt"></i> Sair', ['/site/logout'], [
                    'class' => 'nav-link',
                    'data-method' => 'post'
                ]) ?>
            </li>
        <?php endif; ?>
    </ul>
</nav>
