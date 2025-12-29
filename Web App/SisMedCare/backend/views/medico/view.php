<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Medico $model */

$this->title = $model->nome_completo;
$this->params['breadcrumbs'][] = ['label' => 'Medicos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="medico-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nome_completo',
            'especialidade',
            'nif',
            'telemovel',
            'cedula_numero',
            'horario_trabalho',
        ],
    ]) ?>

    <br>

    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Apagar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Tem a certeza que pretende eliminar este item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

</div>
