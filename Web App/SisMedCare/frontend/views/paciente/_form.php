<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Paciente $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="paciente-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'altura')->textInput(['maxlength' => true]) ?>

    <br>

    <?= $form->field($model, 'peso')->textInput(['maxlength' => true]) ?>

    <br>

    <?= $form->field($model, 'alergias')->textInput(['maxlength' => true]) ?>

    <br>

    <?= $form->field($model, 'doencas_cronicas')->textInput(['maxlength' => true]) ?>

    <br>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
