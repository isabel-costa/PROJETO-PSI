<?php

use common\models\RegistoToma;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Registo Tomas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registo-toma-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Registo Toma', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'paciente_id',
            'prescricao_medicamento_id',
            'data_toma',
            'foi_tomado',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, RegistoToma $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
