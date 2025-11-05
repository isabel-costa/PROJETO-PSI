<?php
$this->title = 'Painel de Administração';
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>
<div class="container-fluid">

    <div class="row mb-4">
        <div class="col-lg-12">
            <?= \hail812\adminlte\widgets\Alert::widget([
                'type' => 'info',
                'body' => '
                    <h3>Painel de Administração</h3>
                    <p>Bem-vindo à área de administração do sistema. 
                    Aqui pode gerir <strong>médicos</strong>, <strong>secretárias</strong> e <strong>medicação</strong>, 
                    bem como consultar estatísticas e informações do funcionamento da clínica.</p>
                ',
            ]) ?>
        </div>
    </div>

    <!-- Mantém os widgets, mas mais abaixo -->
    <div class="row">
        <div class="col-lg-6">
            <?= \hail812\adminlte\widgets\Callout::widget([
                'type' => 'success',
                'head' => 'Dica Rápida',
                'body' => 'Utilize o menu lateral para aceder às secções de gestão e configurações do sistema.'
            ]) ?>
        </div>
    </div>

    <!-- Widgets de informação (mantidos para futuro uso) -->
    <div class="row mt-4">
        <div class="col-12 col-sm-6 col-md-3">
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
    </div>

    <!-- Pequenos blocos decorativos -->
    <div class="row mt-4">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => '3',
                'text' => 'Pedidos pendentes',
                'icon' => 'fas fa-calendar-check',
                'theme' => 'warning',
            ]) ?>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\SmallBox::widget([
                'title' => '8',
                'text' => 'Novas mensagens',
                'icon' => 'far fa-envelope',
                'theme' => 'gradient-info',
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
</div>
