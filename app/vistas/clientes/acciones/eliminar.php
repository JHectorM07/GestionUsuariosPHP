<?php
// Lee el ID venga por POST (desde el listado) o por GET
$id = (int) ($_REQUEST['id'] ?? 0);

$cliente = $clientesController->obtener($id);
if (!$cliente) {
    $cliente = [];
}
?>
<div class="d-flex justify-content-between align-items-center pt-2 pb-2 mb-4 border-bottom">
    <h1 class="h2"><i class="bi bi-trash me-2"></i>Eliminar cliente</h1>
</div>

<div class="bg-white rounded shadow-sm p-4">
    <div class="alert alert-warning">
        ¿Seguro que deseas eliminar a <strong><?php echo htmlspecialchars($cliente['cliente'] ?? 'este cliente'); ?></strong>? Esta acción no se puede deshacer.
    </div>

    <form method="POST" action="/appgestionusuarios/public/index.php" class="d-flex gap-2">
        <input type="hidden" name="controlador" value="clientes">
        <input type="hidden" name="accion" value="borrar">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-1"></i> Sí, eliminar</button>
        <a href="/appgestionusuarios/public/index.php?controlador=clientes" class="btn btn-outline-secondary">Cancelar</a>
    </form>
</div>