<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Consulta $model */

$this->title = $model->consulta_id;
$this->params['breadcrumbs'][] = ['label' => 'Consultas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="consulta-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'consulta_id' => $model->consulta_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'consulta_id' => $model->consulta_id], [
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
            'consulta_id',
            'paciente_id',
            'medico_id',
            'data_consulta',
            'estado',
            'observacoes',
            'criado_em',
        ],
    ]) ?>

</div>
