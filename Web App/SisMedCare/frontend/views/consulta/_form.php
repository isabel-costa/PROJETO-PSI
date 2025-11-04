<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Consulta $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="consulta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'paciente_id')->textInput() ?>

    <?= $form->field($model, 'medico_id')->textInput() ?>

    <?= $form->field($model, 'data_consulta')->textInput() ?>

    <?= $form->field($model, 'estado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'observacoes')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'criado_em')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
