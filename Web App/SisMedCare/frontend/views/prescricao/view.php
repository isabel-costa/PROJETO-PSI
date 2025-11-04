<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Prescricao $model */

$this->title = $model->prescricao_id;
$this->params['breadcrumbs'][] = ['label' => 'Prescricaos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="prescricao-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'prescricao_id' => $model->prescricao_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'prescricao_id' => $model->prescricao_id], [
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
            'prescricao_id',
            'consulta_id',
            'medico_id',
            'paciente_id',
            'data_prescricao',
            'observacoes',
        ],
    ]) ?>

</div>
