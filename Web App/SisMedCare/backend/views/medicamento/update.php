<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Medicamento $model */

$this->title = 'Atualizar Medicamento: ' . $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Medicamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="medicamento-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
