<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\PrescricaoMedicamento $model */

$this->title = $model->prescricao->data_prescricao . ' - ' . $model->medicamento->nome;
$this->params['breadcrumbs'][] = ['label' => 'Prescrição de Medicamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="prescricao-medicamento-view">

    <br>

    <h1><?= Html::encode($this->title) ?></h1>

    <br>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            [
                'label' => 'Data da Prescrição',
                'value' => $model->prescricao->data_prescricao,
            ],
            [
                'label' => 'Nome do Medicamento',
                'value' => $model->medicamento->nome,
            ],
            'posologia:ntext',
            'frequencia',
            'duracao_dias',
            'instrucoes',
        ],
    ]) ?>

</div>
