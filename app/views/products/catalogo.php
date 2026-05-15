<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="seccion-tienda py-5">
    <div class="container">

        <h2 class="text-white mb-4">CATÁLOGO</h2>

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
                            <option value="1" <?php echo $categoriaActual == 1 ? 'selected' : ''; ?>>Suplementos</option>
                            <option value="2" <?php echo $categoriaActual == 2 ? 'selected' : ''; ?>>Ropa</option>
                            <option value="3" <?php echo $categoriaActual == 3 ? 'selected' : ''; ?>>Accesorios</option>
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

         data-image="/FitLab/public/img/<?php echo $p['imagen']; ?>">

        <img src="/FitLab/public/img/<?php echo $p['imagen']; ?>"
             class="imagen-producto">

        <div class="card-body">

            <p class="nombre-producto">

                <?php echo $p['nombre_producto']; ?>

            </p>

            <p class="precio-producto">

                <?php echo $p['precio']; ?>€

            </p>

            <a href="index.php?action=agregarAlCarrito&id=<?php echo $p['id_producto']; ?>"
               class="btn btn-primary w-100">

               Comprar

            </a>

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
                <a id="modalBuyBtn"
                   href="#"
                   class="btn btn-primary w-100 mt-4">

                    AGREGAR AL CARRITO

                </a>

            </div>

        </div>

    </div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>