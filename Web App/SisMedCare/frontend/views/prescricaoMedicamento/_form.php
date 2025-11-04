<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\PrescricaoMedicamento $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="prescricao-medicamento-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'prescricao_id')->textInput() ?>

    <?= $form->field($model, 'medicamento_id')->textInput() ?>

    <?= $form->field($model, 'posologia')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'frequencia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'duracao_dias')->textInput() ?>

    <?= $form->field($model, 'instrucoes')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
