<?php

use yii\helpers\Html;

$this->title = 'Painel de Administração';
$this->params['breadcrumbs'] = [['label' => $this->title]];

$user = Yii::$app->user->identity;
$auth = Yii::$app->authManager;
$userRoles = $user ? $auth->getRolesByUser($user->id) : [];
$roleNames = array_keys($userRoles);
?>
<div class="container-fluid">

    <h5 class="mb-4">Bem-vindo, <?= Html::encode($user->username) ?> 👋</h5>

    <?php if (in_array('admin', $roleNames)): ?>
        <div class="row mt-4">
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Médicos registados',
                    'number' => '12',
                    'icon' => 'fas fa-user-md',
                    'theme' => 'primary',
                ]) ?>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Secretárias registadas',
                    'number' => '4',
                    'theme' => 'info',
                    'icon' => 'fas fa-user-tie',
                ]) ?>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Medicamentos registados',
                    'number' => '128',
                    'theme' => 'gradient-success',
                    'icon' => 'fas fa-pills',
                ]) ?>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\SmallBox::widget([
                    'title' => '5',
                    'text' => 'Novos registos',
                    'icon' => 'fas fa-user-plus',
                    'theme' => 'gradient-success',
                ]) ?>
            </div>
        </div>
    <?php elseif (in_array('secretary', $roleNames)): ?>
        <div class="row mt-4">
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Pedidos aprovados',
                    'number' => '4',
                    'theme' => 'primary',
                    'icon' => 'fas fa-check',
                ]) ?>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Pedidos rejeitados',
                    'number' => '4',
                    'theme' => 'info',
                    'icon' => 'fas fa-times',
                ]) ?>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Pacientes registados',
                    'number' => '4',
                    'theme' => 'gradient-success',
                    'icon' => 'fas fa-user',
                ]) ?>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\SmallBox::widget([
                    'title' => '3',
                    'text' => 'Pedidos pendentes',
                    'icon' => 'fas fa-calendar-check',
                    'theme' => 'warning',
                ]) ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning mt-5 text-center">
            <h4>Sem permissões específicas</h4>
        </div>
    <?php endif; ?>
</div>
