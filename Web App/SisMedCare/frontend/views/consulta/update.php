<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Consulta $model */

$this->title = 'Update Consulta: ' . $model->consulta_id;
$this->params['breadcrumbs'][] = ['label' => 'Consultas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->consulta_id, 'url' => ['view', 'consulta_id' => $model->consulta_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="consulta-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
