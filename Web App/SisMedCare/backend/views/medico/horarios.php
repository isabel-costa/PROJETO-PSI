<?php
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Horários de Médicos';
?>
<div class="container-fluid" style="margin-top: -100px;">

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
        'id',
        'nome_completo',
        'especialidade',
        'cedula_numero',
        'horario_trabalho',
    ],
]); ?>
</div>