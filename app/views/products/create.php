<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="fondo-formulario d-flex align-items-center">

    <div class="container">
        <div class="row align-items-center">

            <!-- TEXTO IZQUIERDA -->
            <div class="col-md-6 text-white">
                <h1 class="titulo-formulario">
                    PANEL <br>
                    <span>ADMIN</span>
                </h1>
            </div>

            <!-- FORMULARIO -->
            <div class="col-md-6 d-flex justify-content-center">
                <div class="caja-formulario">

                    <h3 class="text-center mb-4 text-white">CREAR PRODUCTO</h3>

                    <form action="index.php?action=guardarProducto" method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label>Nombre del producto</label>
                            <input type="text" name="nombre_producto" class="form-control" placeholder="Nombre del producto">
                        </div>

                        <div class="mb-3">
                            <label>Precio (€)</label>
                            <input type="number" name="precio" class="form-control" placeholder="Precio">
                        </div>

                        <div class="mb-3">
                            <label>Stock</label>
                            <input type="number" name="stock" class="form-control" placeholder="Stock">
                        </div>

                        <div class="mb-3">
                            <label>Descripción</label>
                            <input type="textarea" name="descripcion" class="form-control" placeholder="Descripción">
                        </div>
                        <div class="mb-3">
                            <label for="id_categoria" class="form-label">Categoría</label>
                            <select name="id_categoria" id="id_categoria" class="form-control" required>
                                <option value="">Selecciona una categoría</option>
                                <option value="1">Suplementos</option>
                                <option value="2">Ropa</option>
                                <option value="3">Accesorios</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Imagen del producto</label>
                            <input type="file" name="imagen" class="form-control">
                        </div>

                        <button class="btn btn-primary w-100 mt-2">
                            GUARDAR PRODUCTO
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>