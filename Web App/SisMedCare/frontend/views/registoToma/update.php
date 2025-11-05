<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\RegistoToma $model */

$this->title = 'Update Registo Toma: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Registo Tomas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="registo-toma-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
