<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="container py-5 text-white">
    <h1>Detalle del pedido #<?= htmlspecialchars($pedido['id_pedido']) ?></h1>

    <p><strong>Fecha:</strong> <?= htmlspecialchars($pedido['fecha_pedido']) ?></p>
    <p><strong>Estado:</strong> <?= htmlspecialchars($pedido['estado']) ?></p>
    <p><strong>Total:</strong> <?= htmlspecialchars($pedido['total']) ?>€</p>
    <p><strong>Seguimiento:</strong> <?= htmlspecialchars($pedido['estado']) ?>. El administrador podrá actualizarlo desde su panel.</p>

    <!-- DETALLE DEL PEDIDO -->
    <div class="table-responsive bg-dark p-3 rounded">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($detalles as $d): ?>
                    <tr>
                        <td><?= htmlspecialchars($d['nombre_producto']) ?></td>
                        <td><?= htmlspecialchars($d['cantidad']) ?></td>
                        <td><?= htmlspecialchars($d['precio_unitario']) ?>€</td>
                        <td><?= htmlspecialchars($d['subtotal']) ?>€</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <a class="btn btn-secondary mt-3" href="index.php?action=misPedidos">Volver</a>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
