<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Prescricao $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="prescricao-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'consulta_id')->textInput() ?>

    <?= $form->field($model, 'medico_id')->textInput() ?>

    <?= $form->field($model, 'paciente_id')->textInput() ?>

    <?= $form->field($model, 'data_prescricao')->textInput() ?>

    <?= $form->field($model, 'observacoes')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
