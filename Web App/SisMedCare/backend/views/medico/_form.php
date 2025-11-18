<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Medico $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="medico-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'nome_completo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'especialidade')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nif')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telemovel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cedula_numero')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'horario_trabalho')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
