<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PrescricaoMedicamento $model */

$this->title = 'Update Prescricao Medicamento: ' . $model->prescricao_medicamento_id;
$this->params['breadcrumbs'][] = ['label' => 'Prescricao Medicamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->prescricao_medicamento_id, 'url' => ['view', 'prescricao_medicamento_id' => $model->prescricao_medicamento_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="prescricao-medicamento-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
