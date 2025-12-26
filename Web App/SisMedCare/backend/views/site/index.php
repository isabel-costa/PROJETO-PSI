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

    <h5 class="mb-4">Bem-vindo(a), <?= Html::encode($user->username) ?> 👋</h5>

    <?php if (in_array('admin', $roleNames)): ?>

        <!-- INFOBOXES ADMIN -->
        <div class="row mt-4">
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Médicos registados',
                    'number' => $medicosCount,
                    'icon' => 'fas fa-user-md',
                    'theme' => 'primary',
                ]) ?>
            </div>

            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Secretárias registadas',
                    'number' => $secretariasCount,
                    'theme' => 'info',
                    'icon' => 'fas fa-user-tie',
                ]) ?>
            </div>

            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Medicamentos registados',
                    'number' => $medicamentosCount,
                    'theme' => 'gradient-success',
                    'icon' => 'fas fa-pills',
                ]) ?>
            </div>
        </div>

    <?php elseif (in_array('secretary', $roleNames)): ?>

        <!-- INFOBOXES SECRETARIA -->
        <div class="row mt-4">
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Pedidos aprovados',
                    'number' => $pedidosAprovados,
                    'theme' => 'primary',
                    'icon' => 'fas fa-check',
                ]) ?>
            </div>

            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Pedidos rejeitados',
                    'number' => $pedidosRejeitados,
                    'theme' => 'info',
                    'icon' => 'fas fa-times',
                ]) ?>
            </div>

            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Pedidos pendentes',
                    'number' => $pedidosPendentes,
                    'theme' => 'info',
                    'icon' => 'fas fa-calendar-check',
                ]) ?>
            </div>
        </div>

        <!-- GRÁFICO – CONSULTAS POR MÊS -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">Consultas realizadas por mês</h5>
            </div>
            <div class="card-body">
                <canvas id="graficoConsultas"></canvas>
            </div>
        </div>

    <?php else: ?>
        <div class="alert alert-warning mt-5 text-center">
            <h4>Sem permissões específicas</h4>
        </div>
    <?php endif; ?>
</div>

<!-- SCRIPT DO CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php if (in_array('admin', $roleNames) || in_array('secretary', $roleNames)): ?>
    <script>
        const ctx = document.getElementById('graficoConsultas').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Consultas',
                    data: <?= json_encode($values) ?>,
                    borderWidth: 2,
                    tension: 0.3,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
<?php endif; ?>
