<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="container py-5 text-white">
    <h1>
        <?= ($row || $entity === 'informacion') ? 'Editar ' : 'Nuevo ' ?><?= htmlspecialchars($cfg['title']) ?>
    </h1>

    <?php if ($entity === 'apariencia'): ?>
        <div class="alert alert-info">
            Los diseños visuales son preestablecidos. Vuelve al listado y selecciona uno.
        </div>

        <a class="btn btn-secondary" href="index.php?action=adminList&entity=apariencia">Volver</a>
    <?php else: ?>
        <!-- FORMULARIO GENÉRICO DEL PANEL -->
        <form class="bg-dark p-4 rounded" method="POST" enctype="multipart/form-data" action="index.php?action=<?= $row ? 'adminUpdate' : 'adminStore' ?>">
            <input type="hidden" name="entity" value="<?= htmlspecialchars($entity) ?>">

            <?php if ($row): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($row[$cfg['pk']]) ?>">
            <?php endif; ?>

            <?php foreach ($cfg['fields'] as $field => $type): ?>
                <?php $value = $row[$field] ?? ''; ?>

                <div class="mb-3">
                    <label class="form-label"><?= htmlspecialchars($field) ?></label>

                    <?php if ($type === 'textarea'): ?>
                        <textarea
                            name="<?= htmlspecialchars($field) ?>"
                            class="form-control"
                            <?= in_array($field, ['descripcion', 'hero_titulo', 'hero_subtitulo'], true) ? 'required' : '' ?>
                        ><?= htmlspecialchars((string) $value) ?></textarea>

                    <?php elseif ($type === 'boolean'): ?>
                        <div>
                            <input type="checkbox" name="<?= htmlspecialchars($field) ?>" value="1" <?= $value ? 'checked' : '' ?>>
                            Activo / Sí
                        </div>

                    <?php elseif ($type === 'file'): ?>
                        <!-- IMAGEN ACTUAL -->
                        <?php if ($value): ?>
                            <p>Imagen actual:</p>
                            <img
                                src="<?= htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') ?>"
                                alt="Imagen actual"
                                style="max-width:180px; border-radius:8px; display:block; margin-bottom:10px;"
                            >
                            <p class="small text-secondary"><?= htmlspecialchars((string) $value) ?></p>
                        <?php endif; ?>

                        <input
                            type="file"
                            name="<?= htmlspecialchars($field) ?>"
                            class="form-control"
                            accept="image/png,image/jpeg,image/webp,image/gif"
                            <?= (!$row && in_array($entity, ['servicios', 'imagenes'], true)) ? 'required' : '' ?>
                        >

                        <?php if ($row): ?>
                            <small class="text-secondary">Deja este campo vacío si quieres conservar la imagen actual.</small>
                        <?php endif; ?>

                    <?php elseif ($type === 'password'): ?>
                        <input
                            type="password"
                            name="<?= htmlspecialchars($field) ?>"
                            class="form-control"
                            minlength="8"
                            <?= $row ? '' : 'required' ?>
                        >

                        <?php if ($row): ?>
                            <small class="text-secondary">
                                Deja la contraseña vacía para conservar la actual. Si escribes una nueva, se cifrará con password_hash.
                            </small>
                        <?php endif; ?>

                    <?php else: ?>
                        <input
                            type="<?= $type === 'decimal' ? 'number' : htmlspecialchars($type) ?>"
                            name="<?= htmlspecialchars($field) ?>"
                            class="form-control"
                            value="<?= htmlspecialchars((string) $value) ?>"
                            <?= in_array($type, ['number', 'decimal']) ? 'min="0" step="' . ($type === 'decimal' ? '0.01' : '1') . '"' : '' ?>
                            <?= in_array($field, ['nombre', 'apellidos', 'email', 'nombre_categoria', 'titulo', 'dia'], true) ? 'required' : '' ?>
                        >
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <button class="btn btn-primary">Guardar</button>
            <a class="btn btn-secondary" href="index.php?action=adminList&entity=<?= urlencode($entity) ?>">Cancelar</a>
        </form>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
