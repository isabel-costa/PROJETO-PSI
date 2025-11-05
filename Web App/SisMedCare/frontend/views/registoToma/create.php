<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\RegistoToma $model */

$this->title = 'Create Registo Toma';
$this->params['breadcrumbs'][] = ['label' => 'Registo Tomas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registo-toma-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
