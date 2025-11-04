<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Medico $model */

$this->title = 'Update Medico: ' . $model->medico_id;
$this->params['breadcrumbs'][] = ['label' => 'Medicos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->medico_id, 'url' => ['view', 'medico_id' => $model->medico_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="medico-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
