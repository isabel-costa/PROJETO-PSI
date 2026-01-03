<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Consulta $model */

$this->title = 'Detalhes da Consulta';
$this->params['breadcrumbs'][] = ['label' => 'Consultas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>

<div class="consulta-view py-5">

    <h2 class="text-center mb-4"><?= Html::encode($this->title) ?></h2>

    <!-- RETÂNGULO CENTRAL COM FUNDO VERDE -->
    <div class="consulta-dados">

        <div class="linha">
            <div>
                <label>Nome do Paciente</label>
                <input type="text" value="<?= Html::encode($model->paciente->nome_completo) ?>" disabled>
            </div>
            <div>
                <label>Data da Consulta</label>
                <input type="text" value="<?= Yii::$app->formatter->asDatetime($model->data_consulta) ?>" disabled>
            </div>
        </div>

        <div class="linha">
            <div>
                <label>Estado</label>
                <input type="text" value="<?= Html::encode($model->estado) ?>" disabled>
            </div>
            <div>
                <label>Observações</label>
                <textarea rows="3" disabled><?= Html::encode($model->observacoes) ?></textarea>
            </div>
        </div>

    </div>

</div>
