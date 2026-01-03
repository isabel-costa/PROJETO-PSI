<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Prescricao $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="prescricao-form py-5">

    <!-- CARD CENTRAL -->
    <div class="form-card card shadow-sm mx-auto p-4">

        <?php $form = ActiveForm::begin(); ?>

        <!-- Data da Prescrição -->
        <div class="linha mb-3">
            <div>
                <?= $form->field($model, 'data_prescricao')->textInput(['type' => 'date']) ?>
            </div>
        </div>

        <!-- Observações (campo maior) -->
        <div class="linha mb-3">
            <div>
                <?= $form->field($model, 'observacoes')->textarea([
                    'rows' => 6,
                    'placeholder' => 'Digite aqui as observações...',
                ]) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <!-- BOTÃO CENTRALIZADO FORA DO CARD -->
    <div class="text-center mt-4">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success btn-lg', 'form' => $form->id]) ?>
    </div>

</div>
