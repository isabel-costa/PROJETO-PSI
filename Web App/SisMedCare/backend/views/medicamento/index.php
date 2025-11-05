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


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            'nome',
            'descricao',
            'dosagem',
            'fabricante',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Medicamento $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <br>

    <p>
        <?= Html::a('Criar Medicamento', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


</div>
