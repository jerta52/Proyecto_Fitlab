<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="fondo-formulario d-flex align-items-center">

    <div class="container">
        <div class="row align-items-center">

            <!-- TEXTO -->
            <div class="col-md-6 text-white">
                <h1 class="titulo-formulario">
                    EDITAR <br>
                    <span>PRODUCTO</span>
                </h1>
            </div>

            <!-- FORM -->
            <div class="col-md-6 d-flex justify-content-center">
                <div class="caja-formulario">

                    <h3 class="text-center mb-4 text-white">EDITAR PRODUCTO</h3>

                    <form action="index.php?action=actualizarProducto" method="POST" enctype="multipart/form-data">

                        <?php if (!empty($product)): ?>

                        <input type="hidden" name="id" value="<?php echo $product['id_producto']; ?>">

                        <input type="hidden" name="imagen_actual" value="<?php echo $product['imagen']; ?>">
                        
                        <div class="mb-3">

                            <label>Nombre</label>

                            <input type="text"
                                   name="nombre_producto"
                                   class="form-control"
                                   value="<?php echo $product['nombre_producto']; ?>">

                        </div>

                        <div class="mb-3">

                            <label>Precio</label>

                            <input type="number"
                                   name="precio"
                                   class="form-control"
                                   step="0.01">

                        </div>

                        <div class="mb-3">

                            <label>Stock</label>

                            <input type="number"
                                   name="stock"
                                   class="form-control"
                                   value="<?php echo $product['stock']; ?>">

                        </div>

                        <div class="mb-3">

                            <label>Descripción</label>

                            <textarea name="descripcion"
                                      class="form-control"><?php echo $product['descripcion']; ?></textarea>

                        </div>

                        <div class="mb-3">

                            <label>Imagen actual</label><br>

                            <img src="/FitLab/public/img/<?php echo $product['imagen']; ?>"
                                 style="width:100px;">

                        </div>

                        <div class="mb-3">

                            <label>Nueva imagen (opcional)</label>

                            <input type="file"
                                   name="imagen"
                                   class="form-control">

                        </div>

                        <?php endif ?>

                        <button class="btn btn-primary w-100">

                            ACTUALIZAR PRODUCTO

                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>