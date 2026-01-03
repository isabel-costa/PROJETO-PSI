<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $paciente common\models\Paciente|null */
/* @var $dataProvider yii\data\ActiveDataProvider|null */
/* @var $listaPacientes yii\data\ActiveDataProvider|null */

$this->title = 'Prescrições';
$this->params['breadcrumbs'][] = $this->title;

/* Bootstrap Icons */
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>

<?php if (isset($paciente)): ?>

    <div class="prescricao-grid-container container">

        <h1 class="mb-4 prescricao-grid-title">Prescrições – <?= Html::encode($paciente->nome_completo) ?></h1>

        <div class="prescricao-grid-wrapper">
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
                ],
                'columns' => [
                    [
                        'label' => 'Data da Consulta',
                        'value' => fn($model) => Yii::$app->formatter->asDatetime($model->consulta->data_consulta),
                        'contentOptions' => ['class' => 'fw-semibold'],
                    ],
                    [
                        'label' => 'Médico',
                        'value' => 'medico.nome_completo',
                    ],
                    [
                        'attribute' => 'observacoes',
                        'value' => fn($model) => $model->observacoes ?: '—',
                        'contentOptions' => ['class' => 'text-muted'],
                    ],
                    [
                        'label' => 'Ações',
                        'format' => 'raw',
                        'headerOptions' => ['class' => 'text-center d-flex align-items-center justify-content-center'],
                        'contentOptions' => ['class' => 'text-center d-flex align-items-center justify-content-center gap-1'],
                        'value' => function($model) {
                            return
                                Html::a('<i class="bi bi-eye"></i>', ['view', 'id' => $model->id], ['class' => 'btn btn-sm btn-outline-secondary']) .
                                Html::a('<i class="bi bi-journal-medical"></i>', ['prescricao-medicamento/index', 'prescricao_id' => $model->id], ['class' => 'btn btn-sm btn-outline-success']);
                        },
                    ],
                ],
            ]) ?>
        </div>

    </div>

    <!-- Botão fora do fundo verde -->
    <div class="container text-center mt-3">
        <?= Html::a('← Voltar à lista de pacientes', ['index'], ['class' => 'btn btn-success']) ?>
    </div>

<?php else: ?>

    <div class="prescricao-grid-container container">

        <h1 class="mb-4 prescricao-grid-title">Pacientes com Prescrições</h1>

        <div class="prescricao-grid-wrapper">
            <?= GridView::widget([
                'dataProvider' => $listaPacientes,
                'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
                'layout' => "{items}\n{pager}",
                'pager' => [
                    'class' => \yii\bootstrap5\LinkPager::class,
                    'options' => ['class' => 'pagination pagination-smc justify-content-center mt-3'],
                    'linkContainerOptions' => ['class' => 'page-item'],
                    'linkOptions' => ['class' => 'page-link'],
                    'disabledListItemSubTagOptions' => ['class' => 'page-link'],
                ],
                'columns' => [
                    [
                        'label' => 'Paciente',
                        'value' => fn($model) => $model->paciente->nome_completo,
                        'contentOptions' => ['class' => 'fw-semibold'],
                    ],
                    [
                        'label' => 'Quantidade de Prescrições',
                        'value' => fn($model) => $model->total,
                    ],
                    [
                        'label' => 'Ações',
                        'format' => 'raw',
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                        'value' => fn($model) =>
                            Html::a('<i class="bi bi-eye"></i> Ver', ['index', 'paciente_id' => $model->paciente_id], ['class' => 'btn btn-sm btn-outline-success']),
                    ],
                ],
            ]) ?>
        </div>

    </div>

<?php endif; ?>
