<?php

use common\models\Prescricao;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Prescricaos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prescricao-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Prescricao', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'prescricao_id',
            'consulta_id',
            'medico_id',
            'paciente_id',
            'data_prescricao',
            //'observacoes',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Prescricao $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'prescricao_id' => $model->prescricao_id]);
                 }
            ],
        ],
    ]); ?>


</div>
