<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Paciente $model */

$this->title = 'Editar Paciente: ' . $model->nome_completo;
$this->params['breadcrumbs'][] = ['label' => 'Pacientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Editar';
?>
<div class="paciente-update">

    <br>

    <h1><?= Html::encode($this->title) ?></h1>

    <br>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
