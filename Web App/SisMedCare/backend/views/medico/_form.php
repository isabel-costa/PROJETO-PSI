<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Medico $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="medico-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'nome_completo')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'especialidade')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nif')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'telemovel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cedula_numero')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'horario_trabalho')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
