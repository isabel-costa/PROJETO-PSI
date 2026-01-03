<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Consulta $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="consulta-form py-5">

    <h2 class="text-center mb-4">Editar Consulta</h2>

    <!-- CARD CENTRAL -->
    <div class="form-card card shadow-sm mx-auto p-4">

        <?php $form = ActiveForm::begin(['id' => 'consulta-form']); ?>

        <!-- Estado (radio buttons) -->
        <div class="linha mb-3">
            <?= $form->field($model, 'estado')->radioList([
                'concluida' => 'Concluída',
                'cancelada' => 'Cancelada',
            ], [
                'itemOptions' => ['class' => 'form-check-input'],
            ]) ?>
        </div>

        <!-- Observações -->
        <div class="linha mb-3">
            <?= $form->field($model, 'observacoes')->textarea([
                'rows' => 4,
                'placeholder' => 'Digite observações adicionais...',
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <!-- BOTÃO CENTRALIZADO FORA DO CARD -->
    <div class="text-center mt-4">
        <?= Html::submitButton('Guardar', [
            'class' => 'btn btn-success btn-lg',
            'form' => 'consulta-form', // liga o botão ao ActiveForm
        ]) ?>
    </div>

</div>
