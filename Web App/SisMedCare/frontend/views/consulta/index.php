<?php

use common\models\Consulta;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Consultas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="consulta-index">

    <br>

    <h1><?= Html::encode($this->title) ?></h1>

    <br>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            //'id',
            [
                'label' => 'Nome do Paciente',
                'value' => 'paciente.nome_completo',
            ],
            //'medico_id',
            [
                'attribute' => 'data_consulta',
                'value' => 'data_consulta',
                'format' => 'datetime',
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


</div>
