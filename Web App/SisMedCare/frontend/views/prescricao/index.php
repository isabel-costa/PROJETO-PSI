<?php

use common\models\Prescricao;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\Paciente $paciente */

$this->title = 'Prescrições – ' . $paciente->nome_completo;
$this->params['breadcrumbs'][] = ['label' => 'Prescrições', 'url' => ['view']];
$this->params['breadcrumbs'][] = $this->title;

/* Bootstrap Icons */
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>

<div class="prescricao-index container consulta-grid-container">

    <h1 class="mb-4 consulta-grid-title"><?= Html::encode($this->title) ?></h1>

    <div class="consulta-grid-wrapper">
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
                    'label' => 'Data da Consulta',
                    'value' => fn(Prescricao $model) =>
                        Yii::$app->formatter->asDatetime($model->consulta->data_consulta),
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
                    'label' => 'Medicação',
                    'format' => 'raw',
                    'contentOptions' => ['class' => 'text-center'],
                    'headerOptions' => ['class' => 'text-center'],
                    'value' => fn(Prescricao $model) => Html::tag(
                        'div',
                        Html::a(
                            '<i class="bi bi-journal-medical"></i> Ver',
                            ['prescricao-medicamento/index', 'prescricao_id' => $model->id],
                            ['class' => 'btn btn-sm btn-outline-success']
                        ),
                        ['class' => 'd-flex justify-content-center align-items-center h-100']
                    ),
                ],
            ],
        ]) ?>
    </div>

</div>
