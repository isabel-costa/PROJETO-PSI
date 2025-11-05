<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\RegistoToma $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="registo-toma-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'paciente_id')->textInput() ?>

    <?= $form->field($model, 'prescricao_medicamento_id')->textInput() ?>

    <?= $form->field($model, 'data_toma')->textInput() ?>

    <?= $form->field($model, 'foi_tomado')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
