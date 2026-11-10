<?php
$id = (int) ($_POST['id'] ?? 0);
$contrato = $contratosController->obtener($id);
?>
<div class="d-flex justify-content-between align-items-center pt-2 pb-2 mb-4 border-bottom">
    <h1 class="h2"><i class="bi bi-trash me-2"></i>Eliminar contrato</h1>
</div>

<div class="bg-white rounded shadow-sm p-4">
    <div class="alert alert-warning">
        ¿Seguro que deseas eliminar el contrato <strong>#<?php echo $id; ?></strong>? Esta acción no se puede deshacer.
    </div>

    <form method="POST" action="/appgestionusuarios/public/index.php?controlador=contratos&accion=borrar&id=<?php echo $id; ?>" class="d-flex gap-2">
        <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i> Sí, eliminar</button>
        <a href="/appgestionusuarios/public/index.php?controlador=contratos" class="btn btn-outline-secondary">Cancelar</a>
    </form>
</div>