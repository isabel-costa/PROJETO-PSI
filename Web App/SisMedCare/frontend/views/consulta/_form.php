<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Consulta $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="consulta-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'estado')->radioList([
    'concluida' => 'Concluída',
    'cancelada' => 'Cancelada',
    ], [
    'itemOptions' => ['class' => 'form-check-input'],
    ]) ?>

    <br>

    <?= $form->field($model, 'observacoes')->textInput(['maxlength' => true]) ?>

    <br>

    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
