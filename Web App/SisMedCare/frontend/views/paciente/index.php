<?php

use common\models\Paciente;
use yii\helpers\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Lista de Pacientes';
$previousUrl = Yii::$app->request->referrer ?: ['/site/index'];

/* Bootstrap Icons (caso não estejam no layout) */
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>

<p>
    <?= Html::a('<span style="display:inline-block; transform: rotate(180deg); margin-right: 6px;">⤷</span> Voltar',$previousUrl,['class' => 'btn-voltar-smc']) ?>
</p>
<div class="paciente-index container paciente-grid-container">

    <h1 class="mb-4 paciente-grid-title"><?= Html::encode($this->title) ?></h1>

    <div class="paciente-grid-wrapper">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-hover align-middle paciente-table mb-0'],
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
                'nome_completo',
                [
                    'attribute' => 'data_nascimento',
                    'format' => ['date', 'php:d/m/Y'],
                    'contentOptions' => ['class' => 'text-center'],
                ],
                'sexo',
                'numero_utente',
                'altura',
                'peso',
                [
                    'attribute' => 'alergias',
                    'format' => 'raw',
                    'value' => fn($model) => $model->alergias
                        ? '<span class="badge bg-warning text-dark">' . Html::encode($model->alergias) . '</span>'
                        : '<span class="badge bg-secondary">—</span>',
                ],
                [
                    'attribute' => 'doencas_cronicas',
                    'format' => 'raw',
                    'value' => fn($model) => $model->doencas_cronicas
                        ? '<span class="badge bg-danger">' . Html::encode($model->doencas_cronicas) . '</span>'
                        : '<span class="badge bg-secondary">—</span>',
                ],
                [
                    'class' => ActionColumn::class,
                    'header' => 'Ações',
                    'template' => '{update}',
                    'contentOptions' => ['class' => 'text-center'],
                    'buttons' => [
                        'update' => fn($url) => Html::a(
                            '<i class="bi bi-pencil"></i>',
                            $url,
                            ['class' => 'btn btn-sm btn-outline-primary', 'title' => 'Editar']
                        ),
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>
