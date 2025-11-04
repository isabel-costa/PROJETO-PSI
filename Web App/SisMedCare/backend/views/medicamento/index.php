<?php

use common\models\Medicamento;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Medicamentos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medicamento-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Medicamento', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'medicamento_id',
            'nome',
            'descricao',
            'dosagem',
            'fabricante',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Medicamento $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'medicamento_id' => $model->medicamento_id]);
                 }
            ],
        ],
    ]); ?>


</div>
