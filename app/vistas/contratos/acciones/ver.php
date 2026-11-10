<?php
$id = (int) ($_GET['id'] ?? 0);
$contrato = $contratosController->obtener($id);
?>
<div class="d-flex justify-content-between align-items-center pt-2 pb-2 mb-4 border-bottom">
    <h1 class="h2"><i class="bi bi-eye me-2"></i>Detalle del contrato #<?php echo $id; ?></h1>
</div>

<div class="bg-white rounded shadow-sm p-4">
    <dl class="row mb-0">
        <dt class="col-sm-3">ID Contrato</dt>
        <dd class="col-sm-9"><?php echo (int) ($contrato['contrato_id'] ?? 0); ?></dd>

        <dt class="col-sm-3">ID Cliente</dt>
        <dd class="col-sm-9"><?php echo htmlspecialchars($contrato['cliente_id'] ?? ''); ?></dd>

        <dt class="col-sm-3">Monto</dt>
        <dd class="col-sm-9">$<?php echo number_format((float) ($contrato['monto'] ?? $contrato['monto_acordado'] ?? 0), 2); ?></dd>

        <dt class="col-sm-3">Fecha de Inicio</dt>
        <dd class="col-sm-9"><?php echo htmlspecialchars($contrato['fecha_inicio'] ?? 'N/A'); ?></dd>

        <dt class="col-sm-3">Fecha de Fin</dt>
        <dd class="col-sm-9"><?php echo htmlspecialchars($contrato['fecha_fin'] ?? 'Indefinido'); ?></dd>

        <dt class="col-sm-3">Estado</dt>
        <dd class="col-sm-9">
            <span class="badge bg-<?php echo (($contrato['estado'] ?? '') === 'activo') ? 'success' : 'secondary'; ?>">
                <?php echo ucfirst(htmlspecialchars($contrato['estado'] ?? '')); ?>
            </span>
        </dd>
    </dl>
    
    <div class="d-flex gap-2 mt-4">
        <a href="/appgestionusuarios/public/index.php?controlador=contratos&accion=editar&id=<?php echo $id; ?>" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i> Editar
        </a>
        <a href="/appgestionusuarios/public/index.php?controlador=contratos" class="btn btn-outline-secondary">Volver al listado</a>
    </div>
</div>