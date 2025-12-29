<?php

use common\models\PrescricaoMedicamento;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Prescrição de Medicamentos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prescricao-medicamento-index">

    <br>

    <h1><?= Html::encode($this->title) ?></h1>

    <br>

    <p>
        <?= Html::a('Criar Prescrição de Medicamentos', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            //'id',
            [
                'label' => 'Data da Prescrição',
                    'value' => function($model) {
                    return Yii::$app->formatter->asDatetime($model->prescricao->data_prescricao);
                },
            ],
            [
                'label' => 'Nome do Medicamento',
                'value' => 'medicamento.nome',
            ],
            'posologia:ntext',
            'frequencia',
            'duracao_dias',
            'instrucoes',
            [
                'class' => ActionColumn::class,
                'template' => '{view}',
            ],
        ],
    ]); ?>


</div>
