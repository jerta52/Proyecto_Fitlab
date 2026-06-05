<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="container py-5 text-white">
    <h1>Panel de administración</h1>
    <p>Zona exclusiva del administrador. Desde aquí se gestionan los datos del gimnasio.</p>

    <!-- OPCIONES DEL PANEL -->
    <div class="row g-3">
        <?php
        $items = [
            'usuarios' => 'Usuarios registrados',
            'categorias' => 'Categorías',
            'servicios' => 'Servicios',
            'imagenes' => 'Imágenes de instalaciones',
            'informacion' => 'Información general',
            'horario' => 'Horario',
            'apariencia' => 'Aspecto visual'
        ];
        ?>

        <?php foreach ($items as $entity => $title): ?>
            <div class="col-md-4">
                <a class="btn btn-primary w-100" href="index.php?action=adminList&entity=<?= $entity ?>">
                    <?= htmlspecialchars($title) ?>
                </a>
            </div>
        <?php endforeach; ?>

        <div class="col-md-4">
            <a class="btn btn-warning w-100" href="index.php?action=catalogo">Gestionar productos</a>
        </div>

        <div class="col-md-4">
            <a class="btn btn-primary w-100" href="index.php?action=createProduct">Crear producto nuevo</a>
        </div>

        <div class="col-md-4">
            <a class="btn btn-info w-100" href="index.php?action=adminPedidos">Gestionar pedidos</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
