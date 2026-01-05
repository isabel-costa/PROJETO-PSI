<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Medico $model */

$this->title = 'Criar Médico';
$this->params['breadcrumbs'][] = ['label' => 'Médicos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medico-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
