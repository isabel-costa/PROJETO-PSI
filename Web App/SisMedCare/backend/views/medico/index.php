<?php

use common\models\Medico;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Medicos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medico-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Medico', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'medico_id',
            'user_id',
            'nome_completo',
            'especialidade',
            'nif',
            //'email:email',
            //'telemovel',
            //'cedula_numero',
            //'horario_trabalho',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Medico $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'medico_id' => $model->medico_id]);
                 }
            ],
        ],
    ]); ?>


</div>
