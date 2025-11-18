<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use common\models\User;
use common\models\Consulta;
use common\models\Medicamento;

$this->title = 'Painel de Administração';
$this->params['breadcrumbs'] = [['label' => $this->title]];

$user = Yii::$app->user->identity;
$auth = Yii::$app->authManager;
$userRoles = $user ? $auth->getRolesByUser($user->id) : [];
$roleNames = array_keys($userRoles);

/* ===========================
   CONTAGENS PARA O DASHBOARD
   =========================== */

// Médicos
$medicosCount = Yii::$app->authManager
    ->getUserIdsByRole('doctor');
$medicosCount = count($medicosCount);

// Secretárias
$secretariasCount = Yii::$app->authManager
    ->getUserIdsByRole('secretary');
$secretariasCount = count($secretariasCount);

// Medicamentos registados
$medicamentosCount = \common\models\Medicamento::find()->count();

// Estado das consultas
$pedidosAprovados = Consulta::find()->where(['estado' => 'aprovado'])->count();
$pedidosRejeitados = Consulta::find()->where(['estado' => 'rejeitado'])->count();
$pedidosPendentes = Consulta::find()->where(['estado' => 'pendente'])->count();

/* ===========================
   CONSULTAS POR MÊS (GRÁFICO)
   =========================== */

$consultasPorMes = Consulta::find()
    ->select([
        "mes" => "DATE_FORMAT(data_consulta, '%Y-%b')",
        "count" => "COUNT(*)"
    ])
    ->groupBy("YEAR(data_consulta), MONTH(data_consulta)")
    ->orderBy("MIN(data_consulta)")
    ->asArray()
    ->all();

$labels = array_column($consultasPorMes, 'mes');
$values = array_column($consultasPorMes, 'count');

?>
<div class="container-fluid">

    <h5 class="mb-4">Bem-vindo(a), <?= Html::encode($user->username) ?> 👋</h5>

    <?php if (in_array('admin', $roleNames)): ?>
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
                    'theme' => 'primary',
                    'icon' => 'fas fa-calendar-check',
                ]) ?>
            </div>
        </div>

        <!-- GRÁFICO CONSULTAS POR MÊS -->
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

<!-- SCRIPT DO GRÁFICO -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>
