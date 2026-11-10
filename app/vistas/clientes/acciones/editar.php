<?php
$id = (int) ($_GET['id'] ?? 0);

$cliente = $clientesController->obtener($id);
if (!$cliente) {
    $cliente = [];
}

$destino = '/appgestionusuarios/public/index.php?controlador=clientes&accion=actualizar&id=' . $id;
?>
<div class="d-flex justify-content-between align-items-center pt-2 pb-2 mb-4 border-bottom">
    <h1 class="h2"><i class="bi bi-pencil-square me-2"></i>Editar cliente</h1>
</div>

<div class="bg-white rounded shadow-sm p-4">
    <?php include __DIR__ . '/_form.php'; ?>
</div>