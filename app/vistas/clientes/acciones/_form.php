<?php
// Formulario compartido por crear.php y editar.php
// Variables: $cliente (array con los valores si es edición)

// URL base sin parámetros GET para no ensuciar la barra de direcciones
$destinoURL = '/appgestionusuarios/public/index.php';
$esEdicion  = !empty($cliente['cliente_id']);
?>
<form method="POST" action="<?php echo $destinoURL; ?>">
    <!-- CAMPOS OCULTOS PARA RUTA Y ACCIÓN (Mantiene la URL limpia) -->
    <input type="hidden" name="controlador" value="clientes">
    <input type="hidden" name="accion" value="<?php echo $esEdicion ? 'actualizar' : 'guardar'; ?>">

    <!-- Input oculto para conservar el ID durante la edición -->
    <?php if ($esEdicion): ?>
        <input type="hidden" name="id" value="<?php echo (int) $cliente['cliente_id']; ?>">
    <?php endif; ?>

    <div class="row g-3">
        <div class="col-md-6">
            <label for="cliente" class="form-label">Nombre del Cliente</label>
            <input type="text" class="form-control" id="cliente" name="cliente" value="<?php echo htmlspecialchars($cliente['cliente'] ?? ''); ?>" required>
        </div>
        <div class="col-md-6">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($cliente['email'] ?? ''); ?>" required>
        </div>
        <div class="col-md-6">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" class="form-control" id="telefono" name="telefono" value="<?php echo htmlspecialchars($cliente['telefono'] ?? ''); ?>">
        </div>
        <div class="col-md-6">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado" name="estado">
                <option value="activo" <?php echo (($cliente['estado'] ?? 'activo') === 'activo') ? 'selected' : ''; ?>>Activo</option>
                <option value="inactivo" <?php echo (($cliente['estado'] ?? '') === 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
            </select>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i> Guardar</button>
        <a href="/appgestionusuarios/public/index.php?controlador=clientes" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>