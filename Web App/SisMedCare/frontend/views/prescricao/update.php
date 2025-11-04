<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Prescricao $model */

$this->title = 'Update Prescricao: ' . $model->prescricao_id;
$this->params['breadcrumbs'][] = ['label' => 'Prescricaos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->prescricao_id, 'url' => ['view', 'prescricao_id' => $model->prescricao_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="prescricao-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
