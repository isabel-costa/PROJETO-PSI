<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Medicamento $model */

$this->title = 'Update Medicamento: ' . $model->medicamento_id;
$this->params['breadcrumbs'][] = ['label' => 'Medicamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->medicamento_id, 'url' => ['view', 'medicamento_id' => $model->medicamento_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="medicamento-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
