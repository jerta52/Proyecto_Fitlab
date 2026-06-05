<?php require __DIR__ . '/../layouts/header.php'; ?>


<!-- HERO TIENDA -->
<section class="portada-tienda">

    <!-- OVERLAY OSCURO -->
    <div class="capa-oscura"></div>

    <div class="container-fluid h-100">

        <div class="row h-100 align-items-center">

    
            <!-- TEXTO IZQUIERDA -->
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

       
            <!-- CARRUSEL DERECHA -->
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


<!-- CATEGORÍAS -->
<section id="shop" class="seccion-tienda py-5">

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h3>CATEGORÍAS</h3>

            <div class="filtros">

                <a href="index.php?action=products"
                   class="btn btn-sm <?= empty($categoriaActual) ? 'btn-light' : 'btn-dark' ?>">
                    Todos
                </a>

                <?php foreach (($categorias ?? []) as $cat): ?>
                    <a href="index.php?action=products&categoria=<?= (int)$cat['id_categoria'] ?>"
                       class="btn btn-sm <?= ((int)($categoriaActual ?? 0) === (int)$cat['id_categoria']) ? 'btn-light' : 'btn-dark' ?>">
                        <?= htmlspecialchars($cat['nombre_categoria']) ?>
                    </a>
                <?php endforeach; ?>

            </div>

            <a href="index.php?action=catalogo"
               class="ver-todo">

                Ver todo →

            </a>

        </div>

   
        <!-- PRODUCTOS -->
        <div class="row">

            <?php if (!empty($products)): ?>
                <?php foreach ($products as $p): ?>

                    <div class="col-md-3 mb-4">

                      
                        <!-- CARD -->
                        <div class="card tarjeta-producto abrir-producto"

                             data-id="<?php echo $p['id_producto']; ?>"

                             data-name="<?php echo $p['nombre_producto']; ?>"

                             data-price="<?php echo $p['precio']; ?>€"

                             data-description="<?php echo $p['descripcion']; ?>"

                             data-image="img/<?php echo $p['imagen']; ?>"

                             data-stock="<?php echo (int) $p['stock']; ?>">

                            <!-- IMAGEN -->
                            <div class="caja-imagen-producto">
                                <img src="img/<?php echo $p['imagen']; ?>">
                            </div>

                            <!-- BODY -->
                            <div class="card-body">

                                <p class="nombre-producto">

                                    <?php echo $p['nombre_producto']; ?>

                                </p>

                                <p class="precio-producto">
                                    <?php echo $p['precio']; ?>€
                                </p>

                                <!-- COMPRAR SOLO CLIENTE -->
                                <?php if (isset($_SESSION['user']) && $_SESSION['user']['id_rol'] == 2): ?>
                                    <?php if ((int) $p['stock'] > 0): ?>
                                        <a href="index.php?action=agregarAlCarrito&id=<?php echo $p['id_producto']; ?>" class="btn btn-primary w-100">
                                            Comprar
                                        </a>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-secondary w-100" disabled>Agotado</button>
                                    <?php endif; ?>
                                <?php elseif (!isset($_SESSION['user'])): ?>
                                    <a href="index.php?action=login" class="btn btn-outline-light w-100">Inicia sesión para comprar</a>
                                <?php endif; ?>

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


<!-- POPUP PRODUCTO -->
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

                <div class="iconos-ventana mt-4">

                    <span>Envío gratis</span>

                    <span>Devoluciones</span>

                    <span>Pago seguro</span>

                </div>

                <!-- BOTÓN -->
                <a id="modalBuyBtn"
                   href="#"
                   data-accion="<?php echo (isset($_SESSION['user']) && $_SESSION['user']['id_rol'] == 2) ? 'comprar' : 'login'; ?>"
                   class="btn btn-primary w-100 mt-4">

                    AGREGAR AL CARRITO

                </a>

            </div>

        </div>

    </div>

</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>