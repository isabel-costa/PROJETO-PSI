<?php
use yii\helpers\Html;
use yii\bootstrap4\Alert;
?>

<div class="login-wrapper">
    <div class="card login-card">
        <div class="card-body login-card-body">
            <p class="login-box-msg login-title">Iniciar Sessão</p>

            <?php if (Yii::$app->session->hasFlash('error')): ?>
                <?= Alert::widget([
                    'options' => ['class' => 'alert-danger'],
                    'body' => Yii::$app->session->getFlash('error'),
                    'closeButton' => ['label' => '&times;'],
                ]) ?>
            <?php endif; ?>

            <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'login-form']) ?>

            <?= $form->field($model,'username', [
                'options' => ['class' => 'form-group has-feedback'],
                'template' => '{beginWrapper}{input}{error}{endWrapper}',
                'wrapperOptions' => ['class' => 'input-group mb-3']
            ])
                ->label(false)
                ->textInput(['placeholder' => $model->getAttributeLabel('username'), 'class' => 'form-control rounded-input']) ?>

            <?= $form->field($model, 'password', [
                'options' => ['class' => 'form-group has-feedback'],
                'template' => '{beginWrapper}{input}{error}{endWrapper}',
                'wrapperOptions' => ['class' => 'input-group mb-3']
            ])
                ->label(false)
                ->passwordInput(['placeholder' => $model->getAttributeLabel('password'), 'class' => 'form-control rounded-input']) ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => '<div class="icheck-primary remember-container">{input}{label}</div>',
                'labelOptions' => ['class' => 'remember-label'],
                'uncheck' => null
            ]) ?>

            <?= Html::submitButton('Iniciar Sessão', ['class' => 'btn btn-primary btn-block rounded-btn mt-3']) ?>

            <?php \yii\bootstrap4\ActiveForm::end(); ?>
        </div>
    </div>
</div>
