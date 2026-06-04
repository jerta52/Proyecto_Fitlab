<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="container py-5 text-white">
    <h1>Gestión de pedidos</h1>
    <a class="btn btn-secondary mb-3" href="index.php?action=adminDashboard">Volver</a>

    <!-- TABLA DE PEDIDOS -->
    <div class="table-responsive bg-dark p-3 rounded">
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Email</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Cambiar estado</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($pedidos as $p): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($p['id_pedido']) ?></td>
                        <td><?= htmlspecialchars($p['nombre'] . ' ' . $p['apellidos']) ?></td>
                        <td><?= htmlspecialchars($p['email']) ?></td>
                        <td><?= htmlspecialchars($p['fecha_pedido']) ?></td>
                        <td><?= htmlspecialchars($p['total']) ?>€</td>
                        <td><?= htmlspecialchars($p['estado']) ?></td>
                        <td>
                            <!-- CAMBIO DE ESTADO DEL PEDIDO -->
                            <form method="POST" action="index.php?action=adminActualizarEstadoPedido" class="d-flex gap-2">
                                <input type="hidden" name="id_pedido" value="<?= htmlspecialchars($p['id_pedido']) ?>">

                                <select name="estado" class="form-select form-select-sm">
                                    <?php foreach (['pendiente', 'pagado', 'enviado', 'entregado', 'cancelado'] as $estado): ?>
                                        <option value="<?= $estado ?>" <?= $p['estado'] === $estado ? 'selected' : '' ?>>
                                            <?= $estado ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <button class="btn btn-sm btn-primary">Guardar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
