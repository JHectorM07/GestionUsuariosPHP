<?php
// El formulario enviará los datos por POST al controlador (pendiente de crear)
$destino = '/appgestionusuarios/public/index.php?controlador=clientes&accion=guardar';
$cliente = [];
?>
<div class="d-flex justify-content-between align-items-center pt-2 pb-2 mb-4 border-bottom">
    <h1 class="h2"><i class="bi bi-plus-circle me-2"></i>Crear cliente</h1>
</div>

<div class="bg-white rounded shadow-sm p-4">
    <?php include __DIR__ . '/_form.php'; ?>
</div>
