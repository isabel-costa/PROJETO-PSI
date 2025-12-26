<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Consulta $model */

$this->title = 'Editar Consulta: ' . $model->data_consulta;
$this->params['breadcrumbs'][] = ['label' => 'Consultas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="consulta-update">

    <br>

    <h1><?= Html::encode($this->title) ?></h1>

    <br>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
