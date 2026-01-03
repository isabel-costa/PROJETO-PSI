<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Paciente $model */

$this->title = 'Editar paciente: ';
$this->params['breadcrumbs'][] = ['label' => 'Pacientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nome_completo, 'url' => ['view', 'nome_completo' => $model->nome_completo]];
$this->params['breadcrumbs'][] = 'Editar';

\yii\web\YiiAsset::register($this);
?>

<div class="paciente-update">

    <!-- TÍTULO -->
    <div style="margin-bottom: 30px;">
        <h2><?= Html::encode($this->title) ?></h2>
    </div>

    <!-- RETÂNGULO DOS DADOS -->
    <div class="paciente-dados">

        <div class="paciente-header">
            <h3><?= Html::encode($model->nome_completo) ?></h3>
            <p><strong>Nº de utente:</strong> <?= Html::encode($model->numero_utente) ?></p>
        </div>

        <?php $form = ActiveForm::begin(); ?>

        <div class="linha">
            <div>
                <label>Altura</label>
                <?= Html::activeTextInput($model, 'altura', ['class' => 'form-control']) ?>
            </div>

            <div>
                <label>Peso</label>
                <?= Html::activeTextInput($model, 'peso', ['class' => 'form-control']) ?>
            </div>
        </div>

        <div class="linha">
            <div>
                <label>Doenças crónicas</label>
                <?= Html::activeTextInput($model, 'doencas_cronicas', ['class' => 'form-control']) ?>
            </div>

            <div>
                <label>Alergias</label>
                <?= Html::activeTextInput($model, 'alergias', ['class' => 'form-control']) ?>
            </div>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>


</div>
