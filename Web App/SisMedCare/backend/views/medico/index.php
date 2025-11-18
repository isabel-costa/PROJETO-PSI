<?php

use common\models\Medico;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Médicos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medico-index">

    <p>
        <?= Html::a('Criar Médico', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'label' => 'Username',
                'value' => function($model) {
                    return $model->user ? $model->user->username : '(sem username)';
                },
            ],
            [
                'label' => 'Email',
                'value' => function($model) {
                    return $model->user ? $model->user->email : '(sem email)';
                },
            ],
            'nome_completo',
            'especialidade',
            'nif',
            'telemovel',
            'cedula_numero',
            'horario_trabalho',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Medico $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
