<?php

use common\models\PrescricaoMedicamento;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Prescricao Medicamentos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prescricao-medicamento-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Prescricao Medicamento', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'prescricao_medicamento_id',
            'prescricao_id',
            'medicamento_id',
            'posologia:ntext',
            'frequencia',
            //'duracao_dias',
            //'instrucoes',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PrescricaoMedicamento $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'prescricao_medicamento_id' => $model->prescricao_medicamento_id]);
                 }
            ],
        ],
    ]); ?>


</div>
