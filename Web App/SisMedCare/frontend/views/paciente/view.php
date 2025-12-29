<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Paciente $model */

$this->title = $model->nome_completo . ' - ' . $model->numero_utente;
$this->params['breadcrumbs'][] = ['label' => 'Pacientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="paciente-view">

    <br>

    <h1><?= Html::encode($this->title) ?></h1>

    <br>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'user_id',
            'nome_completo',
            [
                'label' => 'Data de Nascimento',
                'value' => Yii::$app->formatter->asDatetime($model->data_nascimento),
            ],
            'sexo',
            'numero_utente',
            'email:email',
            'telemovel',
            'morada',
            'altura',
            'peso',
            'alergias',
            'doencas_cronicas',
            //'data_registo',
        ],
    ]) ?>

    <br>

    <p>
        <?= Html::a('Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>
</div>
