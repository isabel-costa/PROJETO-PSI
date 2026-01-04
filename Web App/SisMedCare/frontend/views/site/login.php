<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Iniciar Sessão';
$previousUrl = Yii::$app->request->referrer ?: ['/site/index'];
?>

<p>
    <?= Html::a('← Voltar', $previousUrl, ['class' => 'btn-voltar-smc']) ?>
</p>
<div class="site-login-wrapper">
    <div class="site-login" style="max-width: 400px; margin: 0 auto; padding: 20px;">
        <h2 class="text-center mb-4" style="font-weight: 700;"><?= Html::encode($this->title) ?></h2>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'username', [
                'inputOptions' => [
                    'class' => 'form-control rounded-pill px-4',
                    'autofocus' => true
                ],
                'template' => "{label}\n{input}\n{error}"
            ]) ?>

            <?= $form->field($model, 'password', [
                'inputOptions' => [
                    'class' => 'form-control rounded-pill px-4'
                ],
                'template' => "{label}\n{input}\n{error}"
            ])->passwordInput() ?>

            <div class="form-check mb-3">
                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => "{input} {label}\n{error}",
                    'labelOptions' => ['class' => 'form-check-label'],
                    'inputOptions' => ['class' => 'form-check-input']
                ]) ?>
            </div>

            <div class="d-grid">
                <?= Html::submitButton('Iniciar Sessão', ['class' => 'btn-login-smc rounded-pill py-2', 'name' => 'login-button'
    ]) ?>
</div>
        <?php ActiveForm::end(); ?>
    </div>
</div>