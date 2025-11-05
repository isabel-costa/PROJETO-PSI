<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Medicamento $model */

$this->title = 'Criar Medicamento';
$this->params['breadcrumbs'][] = ['label' => 'Medicamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="medicamento-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
