<?php

use common\models\Consulta;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Pedidos de Consulta';
?>
<div id="mqtt-modal-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.35); z-index:2000;">
    <div id="mqtt-modal" style="max-width:520px; margin:80px auto; background:#fff; border-radius:8px; box-shadow:0 10px 30px rgba(0,0,0,0.2); padding:18px; font-family:inherit;">
        <h4 style="margin:0 0 8px;">📩 Novo pedido de consulta solicitado!</h4>
        <div id="mqtt-modal-body" style="white-space:pre-line; color:#333; margin-bottom:12px;">Mensagem...</div>
        <div style="text-align:right;"><button id="mqtt-modal-close" class="btn btn-primary">Fechar</button></div>
    </div>
</div>

<div class="container-fluid" style="margin-top: -100px;">
     <div class="consulta-grid-wrapper">
    <?= GridView::widget([
        'dataProvider' => $dataProviderPendentes,
        'tableOptions' => ['class' => 'table table-hover align-middle mb-0'],
        'layout' => "{items}\n{pager}",
        'pager' => [
            'class' => \yii\bootstrap5\LinkPager::class,
            'options' => ['class' => 'pagination pagination-smc justify-content-center mt-3'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link'],
            'disabledListItemSubTagOptions' => ['class' => 'page-link'],
            'prevPageLabel' => '<i class="bi bi-chevron-left"></i>',
            'nextPageLabel' => '<i class="bi bi-chevron-right"></i>',
            'firstPageLabel' => '<i class="bi bi-skip-start"></i>',
            'lastPageLabel' => '<i class="bi bi-skip-end"></i>',
        ],
    'columns' => [

        [
            'label' => 'Nº Utente',
            'value' => 'paciente.numero_utente',
            'contentOptions' => ['class' => 'fw-semibold'],
        ],

        [
            'attribute' => 'observacoes',
            'value' => fn($model) => $model->observacoes ?: '—',
            'contentOptions' => ['class' => 'text-muted'],
        ],

         [
            'label' => 'Médico',
            'value' => 'medico.nome_completo',
        ],

        [
            'attribute' => 'data_consulta',
            'label' => 'Data / Hora',
            'format' => ['datetime'],
            'contentOptions' => ['class' => 'fw-semibold'],
        ],

        [
                'attribute' => 'estado',
                'format' => 'raw',
                'value' => function ($model) {
                    $estado = strtolower(trim($model->estado));
                    switch ($estado) {
                        case 'agendada':
                            return '<span class="badge bg-success">Agendada</span>';
                        case 'pendente':
                            return '<span class="badge bg-warning text-dark">Pendente</span>';
                        case 'realizada':
                            return '<span class="badge bg-success">Realizada</span>';
                        case 'cancelada':
                            return '<span class="badge bg-danger">Cancelada</span>';
                        default:
                            return '<span class="badge bg-secondary">' . ($model->estado ?: '—') . '</span>';
                    }
                },
            ],

            [
            'label' => 'Ações',
            'format' => 'raw',
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {
                return
                    Html::a(
                        '<i class="bi bi-check fs-4"></i>',
                        ['consulta/aprovar-consulta', 'id' => $model->id],
                        [
                            'title' => 'Aprovar consulta',
                            'data-confirm' => 'Pretende aprovar esta consulta?',
                            'data-method' => 'post',
                            'class' => 'me-3',
                            'style' => 'color:black; margin-right: 20px; text-decoration:none;',
                        ]
                    ) .
                    Html::a(
                        '<i class="bi bi-x fs-4"></i>',
                        ['consulta/delete', 'id' => $model->id],
                        [
                            'title' => 'Rejeitar consulta',
                            'data-confirm' => 'Pretende rejeitar esta consulta?',
                            'data-method' => 'post',
                            'style' => 'color:black; text-decoration:none;',
                        ]
                    );
                },
            ],
        ],
    ]); ?>
    </div>
</div>

    <?php if (Yii::$app->session->hasFlash('consulta-success')): ?>
        <div class="alert alert-success">
            <?= Yii::$app->session->getFlash('consulta-success') ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('consulta-error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('consulta-error') ?>
        </div>
    <?php endif; ?>

<?php
$checkUrl = Url::to(['consulta/check-novos-pedidos']);

$js = <<<JS
setInterval(function () {
    fetch('$checkUrl')
        .then(response => response.json())
        .then(data => {
            if (data.novo) {
                var mqttAlertEl = document.getElementById('mqtt-alert');
                if (mqttAlertEl) {
                    mqttAlertEl.classList.remove('d-none');
                }

                var overlay = document.getElementById('mqtt-modal-overlay');
                var body = document.getElementById('mqtt-modal-body');
                body.textContent = data.mensagem;
                overlay.style.display = 'block';

                var btn = document.getElementById('mqtt-modal-close');
                btn.onclick = function () {
                    overlay.style.display = 'none';
                    location.reload();
                };
            }
        })
        .catch(err => console.error(err));
}, 2000);
JS;

$this->registerJs($js);
?>