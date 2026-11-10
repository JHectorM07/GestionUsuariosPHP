<?php
$id = (int) ($_GET['id'] ?? 0);
$cliente = $clientesController->obtener($id);

?>
<div class="d-flex justify-content-between align-items-center pt-2 pb-2 mb-4 border-bottom">
    <h1 class="h2"><i class="bi bi-trash me-2"></i>Eliminar cliente</h1>
</div>

<div class="bg-white rounded shadow-sm p-4">
    <div class="alert alert-warning">
        ¿Seguro que deseas eliminar este cliente? Esta acción no se puede deshacer.
    </div>
    <!-- El borrado se envía por POST al controlador (pendiente de crear) -->
    <form method="POST" action="/appgestionusuarios/public/index.php?controlador=clientes&accion=borrar&id=<?php echo $id; ?>" class="d-flex gap-2">
        <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i> Sí, eliminar</button>
        <a href="/appgestionusuarios/public/index.php?controlador=clientes" class="btn btn-outline-secondary">Cancelar</a>
    </form>
</div>
