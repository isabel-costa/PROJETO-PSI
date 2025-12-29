<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Prescricao $model */

$this->title = 'Criar Prescrição';
$this->params['breadcrumbs'][] = ['label' => 'Prescrições', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="prescricao-create">

    <br>
    
    <h1><?= Html::encode($this->title) ?></h1>

    <br>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
