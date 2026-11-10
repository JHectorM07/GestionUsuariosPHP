<?php
$id = (int) ($_GET['id'] ?? 0);
$cliente = $clientesController->obtener($id);

?>
<div class="d-flex justify-content-between align-items-center pt-2 pb-2 mb-4 border-bottom">
    <h1 class="h2"><i class="bi bi-eye me-2"></i>Detalle del cliente</h1>
</div>

<div class="bg-white rounded shadow-sm p-4">
    <dl class="row mb-0">
        <dt class="col-sm-3">Nombre</dt>
        <dd class="col-sm-9"><?php echo htmlspecialchars($cliente['nombre'] ?? ''); ?></dd>
        <dt class="col-sm-3">Correo</dt>
        <dd class="col-sm-9"><?php echo htmlspecialchars($cliente['email'] ?? ''); ?></dd>
        <dt class="col-sm-3">Teléfono</dt>
        <dd class="col-sm-9"><?php echo htmlspecialchars($cliente['telefono'] ?? ''); ?></dd>
    </dl>
    <div class="d-flex gap-2 mt-4">
        <a href="/appgestionusuarios/public/index.php?controlador=clientes&accion=editar&id=<?php echo $id; ?>" class="btn btn-primary"><i class="bi bi-pencil-square me-1"></i> Editar</a>
        <a href="/appgestionusuarios/public/index.php?controlador=clientes" class="btn btn-outline-secondary">Volver al listado</a>
    </div>
</div>
