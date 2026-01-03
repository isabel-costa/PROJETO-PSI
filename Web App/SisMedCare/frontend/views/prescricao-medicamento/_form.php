<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\PrescricaoMedicamento $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $medicamentos */
?>

<div class="prescricao-medicamento-form py-5">

    <!-- CARD CENTRAL -->
    <div class="form-card card shadow-sm mx-auto p-4">

        <?php $form = ActiveForm::begin(); ?>

        <!-- Medicamento -->
        <div class="linha mb-3">
            <div>
                <?= $form->field($model, 'medicamento_id')->dropDownList($medicamentos, ['prompt' => 'Selecione um medicamento']) ?>
            </div>
        </div>

        <!-- Posologia (campo grande) -->
        <div class="linha mb-3">
            <div>
                <?= $form->field($model, 'posologia')->textarea([
                    'rows' => 6,
                    'placeholder' => 'Digite a posologia do medicamento...',
                ]) ?>
            </div>
        </div>

        <!-- Frequência, Duração, Instruções em uma linha -->
        <div class="linha mb-3">
            <div>
                <?= $form->field($model, 'frequencia')->textInput(['maxlength' => true]) ?>
            </div>
            <div>
                <?= $form->field($model, 'duracao_dias')->textInput() ?>
            </div>
            <div>
                <?= $form->field($model, 'instrucoes')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <!-- BOTÃO CENTRALIZADO FORA DO CARD -->
    <div class="text-center mt-4">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success btn-lg', 'form' => $form->id]) ?>
    </div>

</div>
