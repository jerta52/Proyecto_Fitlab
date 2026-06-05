<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="seccion-tienda py-5">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4 admin-catalogo-cabecera">
            <?php $esAdmin = isset($_SESSION['user']) && (int) $_SESSION['user']['id_rol'] === 1; ?>
            <?php $esCliente = isset($_SESSION['user']) && (int) $_SESSION['user']['id_rol'] === 2; ?>

            <h2 class="text-white mb-0"><?= $esAdmin ? 'GESTIÓN DE PRODUCTOS' : 'CATÁLOGO DE PRODUCTOS' ?></h2>

            <?php if ($esAdmin): ?>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="index.php?action=adminDashboard" class="btn btn-secondary">Volver al panel</a>
                    <a href="index.php?action=createProduct" class="btn btn-primary">Nuevo producto</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- ESTRUCTURA CORRECTA -->
        <div class="row">

            <!-- SIDEBAR -->
            <div class="col-lg-3 mb-4">

                <div class="caja-filtros p-4">
                    <?php
                        $categoriaActual = $categoriaActual ?? '';
                        $precioMinimoActual = $precioMinimoActual ?? '';
                        $precioMaximoActual = $precioMaximoActual ?? '';
                    ?>
                    <form method="GET" action="index.php">

                        <input type="hidden" name="action" value="catalogo">

                        <!-- CATEGORÍA -->
                        <p>Categorías</p>

                        <select name="categoria" class="form-select mb-3">
                            <option value="">Todas</option>
                            <?php foreach (($categorias ?? []) as $cat): ?>
                                <option value="<?= (int)$cat['id_categoria'] ?>" <?= ((int)$categoriaActual === (int)$cat['id_categoria']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['nombre_categoria']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <!-- PRECIO -->
                        <p>Precio</p>

                        <input type="number" name="min" placeholder="Min" 
                               min="0" step="0.01"
                               class="form-control mb-2"
                               value="<?php echo htmlspecialchars((string)$precioMinimoActual); ?>">

                        <input type="number" name="max" placeholder="Max" 
                               min="0" step="0.01"
                               class="form-control mb-3"
                               value="<?php echo htmlspecialchars((string)$precioMaximoActual); ?>">

                        <!-- BOTONES -->
                        <button class="btn btn-primary w-100 mb-2">
                            Filtrar
                        </button>

                        <a href="index.php?action=catalogo" 
                           class="btn btn-outline-light w-100">
                            Limpiar filtros
                        </a>

                    </form>

                </div>

            </div>

            <!-- PRODUCTOS -->
            <div class="col-lg-9">

                <div class="row">

                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $p): ?>

                            <div class="col-xl-4 col-md-6 mb-4">

    <div class="card tarjeta-producto h-100 abrir-producto"

         data-id="<?php echo $p['id_producto']; ?>"

         data-name="<?php echo $p['nombre_producto']; ?>"

         data-price="<?php echo $p['precio']; ?>€"

         data-description="<?php echo $p['descripcion']; ?>"

         data-image="img/<?php echo $p['imagen']; ?>"

         data-stock="<?php echo (int) $p['stock']; ?>">

        <img src="img/<?php echo $p['imagen']; ?>"
             class="imagen-producto">

        <div class="card-body">

            <p class="nombre-producto">

                <?php echo $p['nombre_producto']; ?>

            </p>

            <p class="precio-producto">

                <?php echo $p['precio']; ?>€

            </p>

            <?php if ($esCliente): ?>
                <?php if ((int) $p['stock'] > 0): ?>
                    <a
                        href="index.php?action=agregarAlCarrito&id=<?php echo $p['id_producto']; ?>"
                        class="btn btn-primary w-100"
                    >Comprar</a>
                <?php else: ?>
                    <button type="button" class="btn btn-secondary w-100" disabled>Agotado</button>
                <?php endif; ?>
            <?php elseif ($esAdmin): ?>
                <a
                    href="index.php?action=mostrarEditarProducto&id=<?php echo $p['id_producto']; ?>"
                    class="btn btn-warning w-100"
                >Editar</a>
                <a
                    href="index.php?action=eliminarProducto&id=<?php echo $p['id_producto']; ?>"
                    class="btn btn-danger w-100 mt-2"
                    onclick="return confirm('¿Eliminar producto?')"
                >Eliminar</a>
            <?php else: ?>
                <a
                    href="index.php?action=login"
                    class="btn btn-outline-light w-100"
                >Inicia sesión para comprar</a>
            <?php endif; ?>

        </div>

    </div>

</div>

                        <?php endforeach; ?>
                    <?php else: ?>

                        <p class="text-white">No hay productos disponibles</p>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>
</section>
<div id="productModal" class="ventana-producto">

    <!-- CONTENIDO -->
    <div class="contenido-ventana-producto">

        <!-- CERRAR -->
        <button class="cerrar-ventana"
                id="closeModal">

            x

        </button>

        <div class="row align-items-center g-4">

            <!-- IMAGEN -->
            <div class="col-lg-6 text-center">

                <img id="modalImage"
                     src=""
                     class="imagen-ventana-producto">

            </div>

            <!-- INFO -->
            <div class="col-lg-6">

                <h2 id="modalTitle"
                    class="modal-title">
                </h2>

                <h3 id="modalPrice"
                    class="precio-ventana">
                </h3>

                <p id="modalDescription"
                   class="descripcion-ventana">
                </p>

                <!-- ICONOS -->
                <div class="iconos-ventana mt-4">

                    <span>Envío gratis</span>

                    <span>Devoluciones</span>

                    <span>Pago seguro</span>

                </div>

                <!-- BOTÓN -->
                <?php if ($esCliente): ?>
                    <a id="modalBuyBtn"
                       href="#"
                       data-accion="comprar"
                       class="btn btn-primary w-100 mt-4">
                        AGREGAR AL CARRITO
                    </a>
                <?php elseif ($esAdmin): ?>
                    <a id="modalBuyBtn"
                       href="index.php?action=adminDashboard"
                       data-accion="admin"
                       class="btn btn-secondary w-100 mt-4">
                        VOLVER AL PANEL
                    </a>
                <?php else: ?>
                    <a id="modalBuyBtn"
                       href="index.php?action=login"
                       data-accion="login"
                       class="btn btn-outline-light w-100 mt-4">
                        INICIA SESIÓN PARA COMPRAR
                    </a>
                <?php endif; ?>

            </div>

        </div>

    </div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>