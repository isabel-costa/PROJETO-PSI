<?php

use common\models\Paciente;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pacientes';

?>
<div class="container-fluid" style="margin-top: -100px;">
    <div class="consulta-grid-wrapper" style="overflow: auto;">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => [
                'class' => 'table table-hover align-middle mb-0',
            ],
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
                'attribute' => 'id',
                'label' => 'Id',
            ],
            [
                'attribute' => 'nome_completo',
                'contentOptions' => ['style' => 'min-width: 200px;'],
            ],
            [
                'attribute' => 'data_nascimento',
                'format' => ['date', 'php:d/m/Y'],
            ],
            'sexo',
            [
                'attribute' => 'numero_utente',
                'contentOptions' => ['style' => 'min-width: 150px;'],
            ],
            'telemovel',
            [
                'attribute' => 'morada',
                'contentOptions' => ['style' => 'min-width: 300px; white-space: normal;'],
            ],
            [
                'label' => 'Email',
                'value' => function($model) {
                    return $model->user ? $model->user->email : '<i>Email não definido</i>';
                },
                'format' => 'raw',
                'contentOptions' => ['style' => 'min-width: 250px;'],
            ],
            'altura',
            'peso',
            [
                'attribute' => 'alergias',
                'contentOptions' => ['style' => 'min-width: 180px; white-space: normal;'],
            ],
            [
                'attribute' => 'doencas_cronicas',
                'contentOptions' => ['style' => 'min-width: 180px; white-space: normal;'],
            ],
            [
                'attribute' => 'data_registo',
                'format' => ['datetime', 'php:d/m/Y H:i'],
                'contentOptions' => ['style' => 'min-width: 180px;'],
            ],
        ],
        ]); ?>
    </div>
</div>