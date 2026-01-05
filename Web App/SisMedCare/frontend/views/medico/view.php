<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = 'Detalhes do ' . $model->nome_completo;
$previousUrl = Yii::$app->request->referrer ?: ['/site/index'];

$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
\yii\web\YiiAsset::register($this);
?>

<p>
    <?= Html::a(
        '<span style="display:inline-block; transform: rotate(180deg); margin-right: 6px;">⤷</span> Voltar',
        $previousUrl,
        ['class' => 'btn-voltar-smc']
    ) ?>
</p>

<div class="medico-view py-5">
    <h2 class="text-center mb-4"><?= Html::encode($this->title) ?></h2>

    <div class="medico-dados">

        <!-- Linha 1: ID (label pequena) + Nome Completo (label maior) -->
        <div class="linha1 linha1-nome-medico">
            <div class="nome-medico">
                <label>Nome Completo</label>
                <input type="text" value="<?= Html::encode($model->nome_completo) ?>" disabled>
            </div>
        </div>

        <!-- Linha 2: Especialidade + Cédula Profissional + Horário de Trabalho -->
        <div class="linha2 linha2-especialidade-cedula-horario">
            <div class="especialidade-medico">
                <label>Especialidade</label>
                <input type="text" value="<?= Html::encode($model->especialidade) ?>" disabled>
            </div>
            <div class="cedula-medico">
                <label>Cédula Profissional</label>
                <input type="text" value="<?= Html::encode($model->cedula_numero) ?>" disabled>
            </div>
            <div class="horario-medico">
                <label>Horário de Trabalho</label>
                <input type="text" value="<?= Html::encode($model->horario_trabalho ?: '—') ?>" disabled>
            </div>
        </div>

        <!-- Linha 3: Telemóvel + NIF -->
        <div class="linha3 linha3-tel-nif">
            <div class="telemovel-medico">
                <label>Telemóvel</label>
                <input type="text" value="<?= Html::encode($model->telemovel) ?>" disabled>
            </div>
            <div class="nif-medico">
                <label>NIF</label>
                <input type="text" value="<?= Html::encode($model->nif) ?>" disabled>
            </div>
        </div>

    </div>
</div>
