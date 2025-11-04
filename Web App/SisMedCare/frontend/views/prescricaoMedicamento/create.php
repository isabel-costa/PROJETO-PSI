<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PrescricaoMedicamento $model */

$this->title = 'Create Prescricao Medicamento';
$this->params['breadcrumbs'][] = ['label' => 'Prescricao Medicamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prescricao-medicamento-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
