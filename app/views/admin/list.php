<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="container py-5 text-white">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1><?= htmlspecialchars($cfg['title']) ?></h1>

        <div>
            <a class="btn btn-secondary" href="index.php?action=adminDashboard">Volver</a>

            <?php if (!in_array($entity, ['apariencia', 'informacion'], true)): ?>
                <a class="btn btn-primary" href="index.php?action=adminCreate&entity=<?= urlencode($entity) ?>">Nuevo</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($entity === 'apariencia'): ?>
        <!-- DISEÑOS PREESTABLECIDOS -->
        <div class="alert alert-info">
            El administrador no escribe colores ni fuentes manualmente. Solo selecciona uno de los 3 diseños preestablecidos.
        </div>

        <div class="row g-4">
            <?php foreach ($rows as $r): ?>
                <div class="col-md-4">
                    <div class="card bg-dark text-white border-light h-100">
                        <div class="card-body">
                            <h3><?= htmlspecialchars($r['nombre'] ?? ('Diseño ' . $r['id_config'])) ?></h3>
                            <p><?= htmlspecialchars($r['descripcion'] ?? '') ?></p>

                            <p><strong>Fuente:</strong> <?= htmlspecialchars($r['fuente_principal']) ?></p>
                            <p><strong>Títulos:</strong> <?= htmlspecialchars($r['fuente_titulos']) ?></p>
                            <p><strong>Tamaño:</strong> <?= (int) $r['tamano_fuente_base'] ?>px</p>

                            <div class="d-flex gap-2 mb-3">
                                <span style="display:inline-block;width:42px;height:28px;border-radius:4px;background:<?= htmlspecialchars($r['color_principal']) ?>"></span>
                                <span style="display:inline-block;width:42px;height:28px;border-radius:4px;background:<?= htmlspecialchars($r['color_secundario']) ?>"></span>
                            </div>

                            <?php if (!empty($r['activo'])): ?>
                                <span class="badge bg-success">Diseño activo</span>
                            <?php else: ?>
                                <form method="POST" action="index.php?action=adminSeleccionarDiseno">
                                    <input type="hidden" name="id_config" value="<?= (int) $r['id_config'] ?>">
                                    <button class="btn btn-primary w-100">Aplicar este diseño</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- LISTADO GENÉRICO DEL PANEL -->
        <div class="table-responsive bg-dark p-3 rounded">
            <table class="table table-dark table-striped align-middle">
                <thead>
                    <tr>
                        <?php if (!empty($rows)): ?>
                            <?php foreach (array_keys($rows[0]) as $col): ?>
                                <th><?= htmlspecialchars($col) ?></th>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <th>Información</th>
                        <?php endif; ?>

                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($rows)): ?>
                        <tr>
                            <td colspan="20">No hay registros.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rows as $r): ?>
                            <tr>
                                <?php foreach ($r as $k => $v): ?>
                                    <td>
                                        <?php if (in_array($k, ['imagen', 'ruta'], true) && $v): ?>
                                            <img
                                                src="<?= htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8') ?>"
                                                alt="Imagen"
                                                style="width:80px;max-height:60px;object-fit:cover;border-radius:6px;display:block;"
                                            >
                                            <small><?= htmlspecialchars((string) $v) ?></small>
                                        <?php elseif ($k === 'contrasena'): ?>
                                            <em>Contraseña cifrada</em>
                                        <?php else: ?>
                                            <?= htmlspecialchars((string) $v) ?>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>

                                <td>
                                    <a
                                        class="btn btn-sm btn-warning"
                                        href="index.php?action=adminEdit&entity=<?= urlencode($entity) ?>&id=<?= $r[$cfg['pk']] ?>"
                                    >Editar</a>

                                    <?php if ($entity !== 'informacion'): ?>
                                        <a
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('¿Eliminar registro?')"
                                            href="index.php?action=adminDelete&entity=<?= urlencode($entity) ?>&id=<?= $r[$cfg['pk']] ?>"
                                        >Eliminar</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
