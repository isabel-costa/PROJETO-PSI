<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\PrescricaoMedicamento $model */

$this->title = $model->prescricao_medicamento_id;
$this->params['breadcrumbs'][] = ['label' => 'Prescricao Medicamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="prescricao-medicamento-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'prescricao_medicamento_id' => $model->prescricao_medicamento_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'prescricao_medicamento_id' => $model->prescricao_medicamento_id], [
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
            'prescricao_medicamento_id',
            'prescricao_id',
            'medicamento_id',
            'posologia:ntext',
            'frequencia',
            'duracao_dias',
            'instrucoes',
        ],
    ]) ?>

</div>
