<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Medico $model */

$this->title = $model->medico_id;
$this->params['breadcrumbs'][] = ['label' => 'Medicos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="medico-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'medico_id' => $model->medico_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'medico_id' => $model->medico_id], [
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
            'medico_id',
            'user_id',
            'nome_completo',
            'especialidade',
            'nif',
            'email:email',
            'telemovel',
            'cedula_numero',
            'horario_trabalho',
        ],
    ]) ?>

</div>
