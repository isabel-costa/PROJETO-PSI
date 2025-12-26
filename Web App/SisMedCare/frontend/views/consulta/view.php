<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Consulta $model */

$this->title = $model->data_consulta . ' - ' . $model->observacoes;
$this->params['breadcrumbs'][] = ['label' => 'Consultas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="consulta-view">

    <br>

    <h1><?= Html::encode($this->title) ?></h1>

    <br>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            [
                'label' => 'Nome do Paciente',
                'value' => $model->paciente->nome_completo,
            ],
            //'medico_id',
            [
                'label' => 'Data da Consulta',
                'value' => Yii::$app->formatter->asDatetime($model->data_consulta),
            ],
            'estado',
            'observacoes',
            //'criado_em',
        ],
    ]) ?>

</div>
