<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\PrescricaoMedicamento $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="prescricao-medicamento-form">

    <?php $form = ActiveForm::begin(); ?>

    <br>

    <?= $form->field($model, 'medicamento_id')->dropDownList($medicamentos, ['prompt' => 'Selecione um medicamento']) ?>

    <br>

    <?= $form->field($model, 'posologia')->textarea(['rows' => 6]) ?>

    <br>

    <?= $form->field($model, 'frequencia')->textInput(['maxlength' => true]) ?>

    <br>

    <?= $form->field($model, 'duracao_dias')->textInput() ?>

    <br>

    <?= $form->field($model, 'instrucoes')->textInput(['maxlength' => true]) ?>

    <br>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
