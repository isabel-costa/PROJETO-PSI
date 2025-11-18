<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Medico $model */

$this->title = 'Editar Médico: ' . $model->nome_completo;
$this->params['breadcrumbs'][] = ['label' => 'Medicos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="medico-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
