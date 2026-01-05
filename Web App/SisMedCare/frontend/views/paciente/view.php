<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Paciente|null $model */
/** @var string|null $numeroUtente */

$this->title = 'Histórico clínico';
$previousUrl = Yii::$app->request->referrer ?: ['/site/index'];

\yii\web\YiiAsset::register($this);
?>

<p>
    <?= Html::a('<span style="display:inline-block; transform: rotate(180deg); margin-right: 6px;">⤷</span> Voltar',$previousUrl,['class' => 'btn-voltar-smc']) ?>
</p>

<div class="paciente-view">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">

        <h2><?= Html::encode($this->title) ?></h2>

        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['view'],
        ]); ?>

        <?= Html::input(
            'text',
            'numero_utente',
            $numeroUtente,
            [
                'class' => 'form-control',
                'placeholder' => 'Nº de utente',
                'style' => 'width: 220px; display: inline-block;'
            ]
        ) ?>

        <?= Html::submitButton('Pesquisar', ['class' => 'btn btn-secondary']) ?>

        <?php ActiveForm::end(); ?>
    </div>

    <?php if (!$model): ?>
        <div style="text-align: center; font-style: italic; margin-bottom: 15px;">
            Pesquise um paciente pelo número de utente.
        </div>
    <?php endif; ?>

    <div class="paciente-dados">

    <?php if ($model): ?>
        <div class="paciente-header">
            <h3><?= Html::encode($model->nome_completo) ?></h3>
            <p><strong>Nº de utente:</strong> <?= Html::encode($model->numero_utente) ?></p>
        </div>
    <?php endif; ?>

        <?php
        function valor($model, $campo) 
        {
            return $model ? Html::encode($model->$campo ?? '') : '';
        }
        ?>

        <div class="linha">
            <div>
                <label>Nome completo</label>
                <input type="text" value="<?= valor($model, 'nome_completo') ?>" disabled>
            </div>

            <div>
                <label>Altura</label>
                <input type="text" value="<?= valor($model, 'altura') ?>" disabled>
            </div>
        </div>

        <div class="linha">
            <div>
                <label>Data de nascimento</label>
                <input type="text"
                       value="<?= $model && $model->data_nascimento
                           ? Yii::$app->formatter->asDate($model->data_nascimento)
                           : '' ?>"
                       disabled>
            </div>

            <div>
                <label>Peso</label>
                <input type="text" value="<?= valor($model, 'peso') ?>" disabled>
            </div>
        </div>

        <div class="linha">
            <div>
                <label>Sexo</label>
                <input type="text" value="<?= valor($model, 'sexo') ?>" disabled>
            </div>

            <div>
                <label>Doenças crónicas</label>
                <input type="text" value="<?= valor($model, 'doencas_cronicas') ?>" disabled>
            </div>
        </div>

        <div class="linha">
            <div>
                <label>Alergias</label>
                <input type="text" value="<?= valor($model, 'alergias') ?>" disabled>
            </div>

            <div>
                <label>Telemóvel</label>
                <input type="text" value="<?= valor($model, 'telemovel') ?>" disabled>
            </div>
        </div>

        <div class="linha">
            <div>
                <label>Email</label>
                <input type="text"
                    value="<?= $model && $model->user ? Html::encode($model->user->email) : '—' ?>" disabled>
            </div>
        </div>

    </div>

    <?php if ($model): ?>
        <div style="text-align: center; margin-top: 20px;">
            <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </div>
    <?php endif; ?>

</div>
