<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PrescricaoMedicamento $model */

$this->title = 'Detalhes do Medicamento Prescrito';
$previousUrl = Yii::$app->request->referrer ?: ['/site/index'];

\yii\web\YiiAsset::register($this);
?>

<p>
    <?= Html::a('<span style="display:inline-block; transform: rotate(180deg); margin-right: 6px;">⤷</span> Voltar',$previousUrl,['class' => 'btn-voltar-smc']) ?>
</p>
<div class="prescricao-medicamento-view py-5">

    <h2 class="text-center mb-4"><?= Html::encode($this->title) ?></h2>

    <!-- RETÂNGULO COM FUNDO VERDE -->
    <div class="prescricao-dados">

        <div class="linha">
            <div>
                <label>Data da Prescrição</label>
                <input type="text" value="<?= Yii::$app->formatter->asDatetime($model->prescricao->data_prescricao) ?>" disabled>
            </div>
            <div>
                <label>Nome do Medicamento</label>
                <input type="text" value="<?= Html::encode($model->medicamento->nome) ?>" disabled>
            </div>
        </div>

        <div class="linha">
            <div>
                <label>Posologia</label>
                <textarea rows="3" disabled><?= Html::encode($model->posologia) ?></textarea>
            </div>
            <div>
                <label>Frequência</label>
                <input type="text" value="<?= Html::encode($model->frequencia) ?>" disabled>
            </div>
        </div>

        <div class="linha">
            <div>
                <label>Duração (dias)</label>
                <input type="text" value="<?= Html::encode($model->duracao_dias) ?>" disabled>
            </div>
            <div>
                <label>Instruções</label>
                <input type="text" value="<?= Html::encode($model->instrucoes) ?>" disabled>
            </div>
        </div>

    </div>

</div>
