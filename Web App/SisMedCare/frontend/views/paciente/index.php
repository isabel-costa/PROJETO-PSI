<?php

use common\models\Paciente;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pacientes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="paciente-index">

    <br>

    <h1><?= Html::encode($this->title) ?></h1>

    <br>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [

            //'id',
            //'user_id',
            'nome_completo',
            [
                'attribute' => 'data_nascimento',
                'value' => 'data_nascimento',
                'format' => 'datetime',
            ],
            'sexo',
            'numero_utente',
            //'email:email',
            //'telemovel',
            //'morada',
            'altura',
            'peso',
            'alergias',
            'doencas_cronicas',
            //'data_registo',
            [
                'class' => ActionColumn::class,
                'template' => '{view} {update}',
            ],
        ],
    ]); ?>


</div>
