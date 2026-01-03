<?php

use common\models\PrescricaoMedicamento;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Prescrição de Medicamentos';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>

<div class="prescricao-medicamento-container container">

    <h1 class="prescricao-medicamento-title mb-4 text-center"><?= Html::encode($this->title) ?></h1>

    <div class="prescricao-medicamento-wrapper">
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
                    'label' => 'Data da Prescrição',
                    'value' => fn($model) => Yii::$app->formatter->asDatetime($model->prescricao->data_prescricao),
                    'contentOptions' => ['class' => 'text-center align-middle'],
                ],
                [
                    'label' => 'Nome do Medicamento',
                    'value' => 'medicamento.nome',
                    'contentOptions' => ['class' => 'fw-semibold'],
                ],
                'posologia:ntext',
                'frequencia',
                'duracao_dias',
                'instrucoes',
                [
                    'class' => ActionColumn::class,
                    'header' => 'Detalhes',
                    'template' => '{view}',
                    'contentOptions' => ['class' => 'd-flex justify-content-center align-items-center'],
                    'buttons' => [
                        'view' => fn($url, $model) => Html::a(
                            '<i class="bi bi-eye"></i> Ver',
                            $url,
                            ['class' => 'btn btn-sm btn-outline-primary']
                        ),
                    ],
                ],
            ],
        ]); ?>
    </div>

</div>

<div class="container text-center mt-4">
    <?= Html::a('Criar Prescrição de Medicamentos', ['create'], ['class' => 'btn btn-success btn-lg']) ?>
</div>
