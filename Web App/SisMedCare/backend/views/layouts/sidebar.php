<?php
use yii\helpers\Url;
use yii\helpers\Html;

$user = Yii::$app->user->identity;
$username = $user ? Html::encode($user->username) : 'Visitante';

$auth = Yii::$app->authManager;
$userRoles = $user ? $auth->getRolesByUser($user->id) : [];
$roleNames = array_keys($userRoles);
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= Url::home() ?>" class="brand-link">
        <img src="<?= $assetDir ?>/img/AdminLTELogo.png"
             alt="Logo"
             class="brand-image img-circle elevation-3"
             style="opacity:.8">
        <span class="brand-text font-weight-light">SisMedCare</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Painel do utilizador -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $assetDir ?>/img/user2-160x160.jpg"
                     class="img-circle elevation-2"
                     alt="User Image">
            </div>
            <div class="info">
                <a class="d-block"><?= $username ?></a>
            </div>
        </div>

        <!-- Menu -->
        <nav class="mt-2">
            <?php
            // Sidebar específica para cada role
            if (in_array('admin', $roleNames)) {
                echo \hail812\adminlte\widgets\Menu::widget([
                    'items' => [
                        [
                            'label' => 'Médicos',
                            'icon'  => 'user-md',
                            'url'   => ['medico/index'],
                        ],
                        [
                            'label' => 'Secretárias',
                            'icon'  => 'user-tie',
                            'url'   => ['user/index'],
                        ],
                        [
                            'label' => 'Medicamentos',
                            'icon'  => 'pills',
                            'url'   => ['medicamento/index'],
                        ],
                    ],
                ]);
            } elseif (in_array('secretary', $roleNames)) {
                echo \hail812\adminlte\widgets\Menu::widget([
                    'items' => [
                        [
                            'label' => 'Pedidos de Consulta',
                            'icon'  => 'calendar-check',
                            'url'   => ['pedido-consulta/index'],
                        ],
                    ],
                ]);
            } else {
                echo '<p class="text-center text-muted">Sem permissões</p>';
            }
            ?>
        </nav>
    </div>
</aside>
