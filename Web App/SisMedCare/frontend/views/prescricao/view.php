<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Prescricao */

$this->title = 'Prescrição ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Prescrições', 'url' => ['index', 'paciente_id' => $model->paciente_id]];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css');
?>

<div class="prescricao-view py-5">

    <h2 class="text-center mb-4"><?= Html::encode($this->title) ?></h2>

    <div class="prescricao-dados">

        <div class="linha">
            <div>
                <label>Paciente</label>
                <input type="text" value="<?= Html::encode($model->paciente->nome_completo) ?>" disabled>
            </div>
            <div>
                <label>Nº de Utente</label>
                <input type="text" value="<?= Html::encode($model->paciente->numero_utente) ?>" disabled>
            </div>
        </div>

        <div class="linha">
            <div>
                <label>Médico</label>
                <input type="text" value="<?= Html::encode($model->medico->nome_completo) ?>" disabled>
            </div>
        </div>
        <div class="linha">
            <div>
                <label>Data da Prescrição</label>
                <input type="text" value="<?= Yii::$app->formatter->asDatetime($model->data_prescricao) ?>" disabled>
            </div>
            <div>
                <label>Data da Consulta</label>
                <input type="text" value="<?= Yii::$app->formatter->asDatetime($model->consulta->data_consulta) ?>" disabled>
            </div>
        </div>

        <div class="linha">
            <div style="flex: 1;">
                <label>Observações</label>
                <textarea rows="3" disabled><?= Html::encode($model->observacoes ?: '—') ?></textarea>
            </div>
        </div>

    </div>

    <div class="text-center mt-3">
            <?= Html::a('<i class="bi bi-arrow-left"></i> Voltar', ['index', 'paciente_id' => $model->paciente_id], ['class' => 'btn btn-success']) ?>
        </div>

</div>
