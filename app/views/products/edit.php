<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="fondo-formulario d-flex align-items-center">
    <div class="container">
        <div class="row align-items-center">
            <!-- TEXTO -->
            <div class="col-md-6 text-white">
                <h1 class="titulo-formulario">EDITAR <br><span>PRODUCTO</span></h1>
            </div>

            <!-- FORM -->
            <div class="col-md-6 d-flex justify-content-center">
                <div class="caja-formulario">
                    <h3 class="text-center mb-4 text-white">EDITAR PRODUCTO</h3>

                    <form action="index.php?action=actualizarProducto" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id_producto']) ?>">
                        <input type="hidden" name="imagen_actual" value="<?= htmlspecialchars($product['imagen']) ?>">

                        <div class="mb-3">
                            <label>Nombre</label>
                            <input
                                type="text"
                                name="nombre_producto"
                                class="form-control"
                                value="<?= htmlspecialchars($product['nombre_producto']) ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label>Precio</label>
                            <input
                                type="number"
                                name="precio"
                                class="form-control"
                                step="0.01"
                                min="0.01"
                                value="<?= htmlspecialchars($product['precio']) ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label>Stock</label>
                            <input
                                type="number"
                                name="stock"
                                class="form-control"
                                min="0"
                                step="1"
                                value="<?= htmlspecialchars($product['stock']) ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label>Descripción</label>
                            <textarea name="descripcion" class="form-control" required><?= htmlspecialchars($product['descripcion']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label>Categoría</label>
                            <select name="id_categoria" class="form-control" required>
                                <option value="">Selecciona una categoría</option>

                                <?php foreach (($categorias ?? []) as $cat): ?>
                                    <option
                                        value="<?= htmlspecialchars($cat['id_categoria']) ?>"
                                        <?= (int) $product['id_categoria'] === (int) $cat['id_categoria'] ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($cat['nombre_categoria']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Imagen actual</label><br>

                            <?php if (!empty($product['imagen'])): ?>
                                <img src="img/<?= htmlspecialchars($product['imagen']) ?>" alt="Imagen actual" style="width:100px;">
                            <?php else: ?>
                                <p>No hay imagen.</p>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label>Nueva imagen (opcional)</label>
                            <input type="file" name="imagen" class="form-control" accept="image/png,image/jpeg,image/webp">
                        </div>

                        <button class="btn btn-primary w-100">ACTUALIZAR PRODUCTO</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
