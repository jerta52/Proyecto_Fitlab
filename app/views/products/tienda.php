<?php require __DIR__ . '/../layouts/header.php'; ?>

<!-- ================================================= -->
<!-- HERO TIENDA -->
<!-- ================================================= -->

<section class="portada-tienda">

    <!-- OVERLAY OSCURO -->
    <div class="capa-oscura"></div>

    <div class="container-fluid h-100">

        <div class="row h-100 align-items-center">

            <!-- ================================================= -->
            <!-- TEXTO IZQUIERDA -->
            <!-- ================================================= -->

            <div class="col-lg-4 lado-izquierdo-portada">

                <h1 class="titulo-tienda text-white">

                    ESTILO QUE <br>
                    TE REPRESENTA

                </h1>

                <p class="texto-portada text-white">

                    Descubre el equilibrio entre rendimiento y estilo
                    con productos diseñados para destacar dentro
                    y fuera del gym.

                </p>

                <!-- BOTONES -->
                <div class="botones-portada">

                    <a href="#shop"
                       class="btn btn-primary text-white">

                        COMPRAR AHORA

                    </a>

                    <a href="index.php?action=catalogo"
                       class="btn btn-outline-light">

                        VER CATÁLOGO

                    </a>

                </div>

                <!-- ICONOS -->
                <div class="iconos-tienda text-white">

                    <span>
                        Envío gratis
                    </span>

                    <span>
                        Devoluciones
                    </span>

                    <span>
                        Pago seguro
                    </span>

                </div>

            </div>

            <!-- ================================================= -->
            <!-- CARRUSEL DERECHA -->
            <!-- ================================================= -->

            <div class="col-lg-8 lado-derecho-portada">

                <div id="shopCarousel"
                     class="carousel slide"
                     data-bs-ride="carousel">

                    <div class="carousel-inner">

                        <!-- SLIDE 1 -->
                        <div class="carousel-item active">

                            <img src="img/whey.png"
                                 class="imagen-portada-tienda"
                                 alt="Producto 1">

                        </div>

                        <!-- SLIDE 2 -->
                        <div class="carousel-item">

                            <img src="img/imagen-hero1.png"
                                 class="imagen-portada-tienda"
                                 alt="Producto 2">

                        </div>

                        <!-- SLIDE 3 -->
                        <div class="carousel-item">

                            <img src="img/whey.png"
                                 class="imagen-portada-tienda"
                                 alt="Producto 3">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- ================================================= -->
<!-- CATEGORÍAS -->
<!-- ================================================= -->

<section id="shop" class="seccion-tienda py-5">

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h3>CATEGORÍAS</h3>

            <div class="filtros">

                <a href="index.php?action=products"
                   class="btn btn-sm btn-light">

                    Todos

                </a>

                <a href="index.php?action=products&categoria=1"
                   class="btn btn-sm btn-dark">

                    Suplementos

                </a>

                <a href="index.php?action=products&categoria=2"
                   class="btn btn-sm btn-dark">

                    Ropa

                </a>

                <a href="index.php?action=products&categoria=3"
                   class="btn btn-sm btn-dark">

                    Accesorios

                </a>

            </div>

            <a href="index.php?action=catalogo"
               class="ver-todo">

                Ver todo →

            </a>

        </div>

        <!-- ================================================= -->
        <!-- PRODUCTOS -->
        <!-- ================================================= -->

        <div class="row">

            <?php if (!empty($products)): ?>
                <?php foreach ($products as $p): ?>

                    <div class="col-md-3 mb-4">

                        <!-- ================================================= -->
                        <!-- CARD -->
                        <!-- ================================================= -->

                        <div class="card tarjeta-producto abrir-producto"

                             data-id="<?php echo $p['id_producto']; ?>"

                             data-name="<?php echo $p['nombre_producto']; ?>"

                             data-price="<?php echo $p['precio']; ?>€"

                             data-description="<?php echo $p['descripcion']; ?>"

                             data-image="/FitLab/public/img/<?php echo $p['imagen']; ?>">

                            <!-- IMAGEN -->
                            <div class="caja-imagen-producto">
                                <img src="/FitLab/public/img/<?php echo $p['imagen']; ?>">
                            </div>

                            <!-- BODY -->
                            <div class="card-body">

                                <p class="nombre-producto">

                                    <?php echo $p['nombre_producto']; ?>

                                </p>

                                <p class="precio-producto">
                                    <?php echo $p['precio']; ?>€
                                </p>

                                <!-- COMPRAR -->
                                <a href="index.php?action=agregarAlCarrito&id=<?php echo $p['id_producto']; ?>" class="btn btn-primary w-100">
                                    Comprar
                                </a>

                                <!-- ADMIN -->
                                <?php if (isset($_SESSION['user']) && $_SESSION['user']['id_rol'] == 1): ?>

                                    <!-- EDITAR -->
                                    <a href="index.php?action=mostrarEditarProducto&id=<?php echo $p['id_producto']; ?>"
                                       class="btn btn-primary w-100 mt-2">

                                        Editar

                                    </a>

                                    <!-- ELIMINAR -->
                                    <a href="index.php?action=eliminarProducto&id=<?php echo $p['id_producto']; ?>"
                                       class="btn btn-danger w-100 mt-2"
                                       onclick="return confirm('¿Seguro que quieres eliminar este producto?');">
                                       
                                        Eliminar
                                    </a>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                <?php endforeach; ?>
            <?php endif; ?>

        </div>

    </div>

</section>

<!-- ================================================= -->
<!-- POPUP PRODUCTO -->
<!-- ================================================= -->

<div id="productModal" class="ventana-producto">

    <!-- CONTENIDO -->
    <div class="contenido-ventana-producto">

        <!-- CERRAR -->
        <button class="cerrar-ventana"
                id="closeModal">

            ×

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

                    <span>🚚 Envío gratis</span>

                    <span>↩️ Devoluciones</span>

                    <span>🔒 Pago seguro</span>

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