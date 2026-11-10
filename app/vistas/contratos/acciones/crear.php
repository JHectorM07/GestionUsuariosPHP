<?php
$destino = '/appgestionusuarios/public/index.php?controlador=contratos&accion=guardar';
$contrato = [];
?>
<div class="d-flex justify-content-between align-items-center pt-2 pb-2 mb-4 border-bottom">
    <h1 class="h2"><i class="bi bi-file-earmark-plus me-2"></i>Crear contrato</h1>
</div>

<div class="bg-white rounded shadow-sm p-4">
    <?php include __DIR__ . '/_form.php'; ?>
</div>