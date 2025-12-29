<?php

use common\models\Prescricao;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Prescrições da Consulta ' . $consulta->data_consulta;
$this->params['breadcrumbs'][] = ['label' => 'Consultas', 'url' => ['consulta/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prescricao-index">

    <br>

    <h1><?= Html::encode($this->title) ?></h1>

    <br>

    <p>
       <?= Html::a('Criar Prescrição', ['create', 'consulta_id' => $consulta->id], ['class' => 'btn btn-success']) ?>
    </p>

    <br>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //'id',
            [
                'label' => 'Data da Consulta',
                'value' => 'consulta.data_consulta',
                'format' => 'datetime',
            ],
            [
                'label' => 'Nome do Médico',
                'value' => 'consulta.medico.nome_completo',
            ],
            [
                'label' => 'Nome do Paciente',
                'value' => 'consulta.paciente.nome_completo',
            ],
            [
                'attribute' => 'data_prescricao',
                'value' => 'data_prescricao',
                'format' => 'datetime',
            ],
            'observacoes',
            [
                'class' => ActionColumn::class,
                'template' => '{view}',
            ],
            [
                'label' => 'Prescrições Medicamentos',
                'format' => 'raw',
                'value' => function (Prescricao $model) {
                    return Html::a(
                        'Ver prescrições de medicamentos',
                        ['prescricao-medicamento/index', 'prescricao_id' => $model->id],
                        ['class' => 'btn btn-sm btn-primary']
                    );
                },
            ],
        ],
    ]); ?>


</div>
