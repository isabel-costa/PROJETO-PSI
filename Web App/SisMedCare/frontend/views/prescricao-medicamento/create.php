<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PrescricaoMedicamento $model */

$this->title = 'Criar Prescrição de Medicamento';
$this->params['breadcrumbs'][] = ['label' => 'Prescrição de Medicamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prescricao-medicamento-create">

    <br>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'medicamentos' => $medicamentos,
    ]) ?>

</div>
