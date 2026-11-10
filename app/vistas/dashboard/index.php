<?php
// Solo entra quien tenga sesión iniciada
if (!isset($_SESSION['usuario_id'])) {
    header('Location: /appgestionusuarios/public/index.php?controlador=login');
    exit();
}

include_once '../app/vistas/componentes/header.php';
?>

    <div class="container-fluid app-container">
        <div class="row h-100">

            <?php include_once '../app/vistas/componentes/sidebar.php'; ?>

            <!-- CONTENIDO PRINCIPAL -->
            <main class="col-md-9 col-lg-10 p-4">
                <?php
                switch ($controlador) {
                    case 'dashboard':
                        include_once '../app/vistas/dashboard/inicio.php';
                        break;
                    case 'clientes':
                        include_once '../app/vistas/clientes/index.php';
                        break;
                    case 'contratos':
                        include_once '../app/vistas/contratos/index.php';
                        break;
                    default:
                        echo 'Página no encontrada';
                        break;
                }
                ?>
            </main>

        </div>
    </div>

<?php include_once '../app/vistas/componentes/footer.php'; ?>
