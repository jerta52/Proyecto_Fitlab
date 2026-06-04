<?php require __DIR__ . '/../layouts/header.php'; ?>
<section class="container py-5 text-white">
    <h1>Mis pedidos</h1>
    <div class="table-responsive bg-dark p-3 rounded">
        <table class="table table-dark table-striped">
            <thead><tr><th>Número</th><th>Fecha</th><th>Total</th><th>Estado / seguimiento</th><th>Detalle</th></tr></thead>
            <tbody>
            <?php if (empty($pedidos)): ?>
                <tr><td colspan="5">Todavía no has realizado pedidos.</td></tr>
            <?php else: foreach ($pedidos as $p): ?>
                <tr>
                    <td>#<?= htmlspecialchars($p['id_pedido']) ?></td>
                    <td><?= htmlspecialchars($p['fecha_pedido']) ?></td>
                    <td><?= htmlspecialchars($p['total']) ?>€</td>
                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($p['estado']) ?></span></td>
                    <td><a class="btn btn-sm btn-primary" href="index.php?action=detallePedido&id=<?= htmlspecialchars($p['id_pedido']) ?>">Ver detalle</a></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
