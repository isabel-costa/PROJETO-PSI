<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Prescricao $model */

$this->title = $model->data_prescricao . ' - ' . $model->observacoes;
$this->params['breadcrumbs'][] = ['label' => 'Prescrições', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="prescricao-view">

    <br>

    <h1><?= Html::encode($this->title) ?></h1>

    <br>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            [
                'label' => 'Data da Consulta',
                'value' => $model->consulta->data_consulta,
            ],
            [
                'label' => 'Nome do Médico',
                'value' => $model->medico->nome_completo,
            ],
            [
                'label' => 'Nome do Paciente',
                'value' => $model->paciente->nome_completo,
            ],
            [
                'label' => 'Data da Prescrição',
                'value' => Yii::$app->formatter->asDatetime($model->data_prescricao),
            ],
            'observacoes',
        ],
    ]) ?>

</div>
