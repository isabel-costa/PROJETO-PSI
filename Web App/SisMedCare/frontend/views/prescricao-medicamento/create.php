<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\PrescricaoMedicamento $model */

$this->title = 'Criar Prescrição de Medicamento';
$previousUrl = Yii::$app->request->referrer ?: ['/site/index'];
?>

<p>
    <?= Html::a('<span style="display:inline-block; transform: rotate(180deg); margin-right: 6px;">⤷</span> Voltar',$previousUrl,['class' => 'btn-voltar-smc']) ?>
</p>
<div class="prescricao-medicamento-create">

    <br>

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'medicamentos' => $medicamentos,
    ]) ?>

</div>
