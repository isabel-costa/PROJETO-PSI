<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use common\models\Consulta;

$this->title = 'Painel de Administração';

$user = Yii::$app->user->identity;
$auth = Yii::$app->authManager;
$userRoles = $user ? $auth->getRolesByUser($user->id) : [];
$roleNames = array_keys($userRoles);

?>
<div class="container-fluid" style="margin-top: -100px;">

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
        <div class="consulta-grid-wrapper" style="max-height:800px; overflow-y:auto;">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
        'layout' => "{items}\n{pager}",
        'pager' => [
            'class' => \yii\bootstrap5\LinkPager::class,
            'options' => ['class' => 'pagination pagination-smc justify-content-center mt-3'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link'],
            'disabledListItemSubTagOptions' => ['class' => 'page-link'],
            'prevPageLabel' => '<i class="bi bi-chevron-left"></i>',
            'nextPageLabel' => '<i class="bi bi-chevron-right"></i>',
            'firstPageLabel' => '<i class="bi bi-skip-start"></i>',
            'lastPageLabel' => '<i class="bi bi-skip-end"></i>',
        ],
        'columns' => [

            [
                'attribute' => 'data_consulta',
                'label' => 'Data / Hora',
                'format' => ['datetime'],
                'contentOptions' => ['class' => 'fw-semibold'],
            ],

            [
                'label' => 'Paciente',
                'value' => 'paciente.nome_completo',
            ],

            [
            'label' => 'Médico',
            'value' => 'medico.nome_completo',
            ],

            [
                'attribute' => 'estado',
                'format' => 'raw',
                'value' => function ($model) {
                    $estado = strtolower(trim($model->estado));
                    switch ($estado) {
                        case 'agendada':
                            return '<span class="badge bg-success">Agendada</span>';
                        case 'pendente':
                            return '<span class="badge bg-warning text-dark">Pendente</span>';
                        case 'realizada':
                            return '<span class="badge bg-success">Realizada</span>';
                        case 'cancelada':
                            return '<span class="badge bg-danger">Cancelada</span>';
                        default:
                            return '<span class="badge bg-secondary">' . ($model->estado ?: '—') . '</span>';
                    }
                },
            ],

            [
                'attribute' => 'observacoes',
                'value' => fn($model) => $model->observacoes ?: '—',
                'contentOptions' => ['class' => 'text-muted'],
            ],
        ],
    ]); ?>
    </div>
</div>
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
