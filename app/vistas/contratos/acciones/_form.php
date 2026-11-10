<?php
// Formulario compartido por crear.php y editar.php
require_once '../app/controladores/ClientesController.php';
$clientesController = new ClientesController();
$listaClientes = $clientesController->listar();

// Forzamos que el envío se haga a la URL base sin parámetros GET
$destinoURL = '/appgestionusuarios/public/index.php';
$esEdicion  = !empty($contrato['contrato_id']);
?>
<form method="POST" action="<?php echo $destinoURL; ?>">
    <!-- CAMPOS OCULTOS PARA RUTA Y ACCIÓN (Mantiene la URL limpia) -->
    <input type="hidden" name="controlador" value="contratos">
    <input type="hidden" name="accion" value="<?php echo $esEdicion ? 'actualizar' : 'guardar'; ?>">
    
    <?php if ($esEdicion): ?>
        <input type="hidden" name="id" value="<?php echo (int) $contrato['contrato_id']; ?>">
    <?php endif; ?>

    <div class="row g-3">
        <!-- Cliente -->
        <div class="col-md-6">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select class="form-select" id="cliente_id" name="cliente_id" required>
                <option value="">-- Selecciona un cliente --</option>
                <?php foreach ($listaClientes as $cli): ?>
                    <option value="<?php echo (int) $cli['cliente_id']; ?>" 
                        <?php echo (($contrato['cliente_id'] ?? 0) == $cli['cliente_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cli['cliente']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Tipo de Item -->
        <div class="col-md-6">
            <label for="tipo_item" class="form-label">Tipo de Ítem</label>
            <select class="form-select" id="tipo_item" name="tipo_item" required>
                <option value="servicio" <?php echo (($contrato['tipo_item'] ?? 'servicio') === 'servicio') ? 'selected' : ''; ?>>Servicio</option>
                <option value="producto" <?php echo (($contrato['tipo_item'] ?? '') === 'producto') ? 'selected' : ''; ?>>Producto</option>
            </select>
        </div>

        <!-- Monto Acordado -->
        <div class="col-md-4">
            <label for="monto_acordado" class="form-label">Monto acordado ($)</label>
            <input type="number" step="0.01" class="form-control" id="monto_acordado" name="monto_acordado" 
                   value="<?php echo htmlspecialchars($contrato['monto_acordado'] ?? ''); ?>" required>
        </div>

        <!-- Periodicidad -->
        <div class="col-md-4">
            <label for="periodicidad" class="form-label">Periodicidad</label>
            <select class="form-select" id="periodicidad" name="periodicidad" required>
                <option value="mensual" <?php echo (($contrato['periodicidad'] ?? 'mensual') === 'mensual') ? 'selected' : ''; ?>>Mensual</option>
                <option value="anual" <?php echo (($contrato['periodicidad'] ?? '') === 'anual') ? 'selected' : ''; ?>>Anual</option>
            </select>
        </div>

        <!-- Día de Corte -->
        <div class="col-md-4">
            <label for="dia_corte" class="form-label">Día de corte</label>
            <input type="number" min="1" max="31" class="form-control" id="dia_corte" name="dia_corte" 
                   value="<?php echo htmlspecialchars($contrato['dia_corte'] ?? 15); ?>" required>
        </div>

        <!-- Fecha Inicio -->
        <div class="col-md-6">
            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" 
                   value="<?php echo htmlspecialchars($contrato['fecha_inicio'] ?? date('Y-m-d')); ?>" required>
        </div>

        <!-- Estado -->
        <div class="col-md-6">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-select" id="estado" name="estado">
                <option value="activo" <?php echo (($contrato['estado'] ?? 'activo') === 'activo') ? 'selected' : ''; ?>>Activo</option>
                <option value="inactivo" <?php echo (($contrato['estado'] ?? '') === 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                <option value="suspendido" <?php echo (($contrato['estado'] ?? '') === 'suspendido') ? 'selected' : ''; ?>>Suspendido</option>
            </select>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i> Guardar</button>
        <a href="/appgestionusuarios/public/index.php?controlador=contratos" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</form>