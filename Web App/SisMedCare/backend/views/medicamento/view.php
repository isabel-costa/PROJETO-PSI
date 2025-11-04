<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Medicamento $model */

$this->title = $model->medicamento_id;
$this->params['breadcrumbs'][] = ['label' => 'Medicamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="medicamento-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'medicamento_id' => $model->medicamento_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'medicamento_id' => $model->medicamento_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'medicamento_id',
            'nome',
            'descricao',
            'dosagem',
            'fabricante',
        ],
    ]) ?>

</div>
