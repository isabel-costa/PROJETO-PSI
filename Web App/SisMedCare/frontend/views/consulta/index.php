<?php

use common\models\Consulta;
use yii\helpers\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

<<<<<<< Updated upstream
$this->title = 'Agenda de Consultas';
=======
$this->title = 'Agenda';
>>>>>>> Stashed changes
$this->params['breadcrumbs'][] = $this->title;

/* Bootstrap Icons (caso não estejam no layout) */
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>

<div class="consulta-index container consulta-grid-container">

<<<<<<< Updated upstream
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
=======
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            //'medico_id',
            [
                'attribute' => 'data_consulta',
                'value' => 'data_consulta',
                'format' => 'datetime',
            ],
            //'id',
            [
                'label' => 'Nome do Paciente',
                'value' => 'paciente.nome_completo',
            ],
            'estado',
            'observacoes',
            //'criado_em',
            [
                'class' => ActionColumn::class,
                'template' => '{view} {update}',
            ],
            [
                'label' => 'Prescrições',
                'format' => 'raw',
                'value' => function (Consulta $model) {
                    return Html::a(
                        'Ver prescrições',
                        ['prescricao/index', 'consulta_id' => $model->id],
                        ['class' => 'btn btn-sm btn-primary']
                    );
                },
            ],
        ],
    ]); ?>
>>>>>>> Stashed changes

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

                [
                    'class' => ActionColumn::class,
                    'header' => 'Ações',
                    'template' => '{view} {update}',
                    'contentOptions' => ['class' => 'text-center'],
                    'buttons' => [
                        'view' => fn($url) => Html::a(
                            '<i class="bi bi-eye"></i>',
                            $url,
                            ['class' => 'btn btn-sm btn-outline-secondary me-1', 'title' => 'Ver']
                        ),
                        'update' => fn($url) => Html::a(
                            '<i class="bi bi-pencil"></i>',
                            $url,
                            ['class' => 'btn btn-sm btn-outline-primary', 'title' => 'Editar']
                        ),
                    ],
                ],

                [
                    'header' => 'Criar Prescrição',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'width: 170px'],
                    'value' => function ($model) {
                        return Html::a(
                            '<i class="bi bi-journal-medical"></i> Criar',
                            ['prescricao/create', 'consulta_id' => $model->id],
                            ['class' => 'btn btn-sm btn-outline-success']
                        );
                    },
                ],
            ],
        ]); ?>
    </div>

</div>
