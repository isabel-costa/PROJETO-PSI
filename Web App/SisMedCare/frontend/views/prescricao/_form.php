<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Prescricao $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="prescricao-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'data_prescricao')->textInput() ?>

    <br>

    <?= $form->field($model, 'observacoes')->textInput(['maxlength' => true]) ?>

    <br>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
