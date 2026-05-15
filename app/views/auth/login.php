<?php require '../app/views/layouts/header.php'; ?>

<section class="fondo-formulario d-flex align-items-center">

    <div class="container">
        <div class="row align-items-center">

            <!-- TEXTO IZQUIERDA -->
            <div class="col-md-6 text-white">
                <h1 class="titulo-formulario">
                    BIENVENIDO <br>
                    A <span>FITLAB</span>
                </h1>
            </div>

            <!-- FORM LOGIN -->
            <div class="col-md-6 d-flex justify-content-center">
                <div class="caja-formulario">

                    <h3 class="text-center mb-4 text-white">INICIAR SESIÓN</h3>

                    <form action="index.php?action=doLogin" method="POST">

                        <div class="mb-3">
                            <label>Correo electrónico</label>
                            <input type="email" name="email" class="form-control" placeholder="Tu correo electrónico">
                        </div>

                        <div class="mb-3">
                            <label>Contraseña</label>
                            <input type="password" name="password" class="form-control" placeholder="Tu contraseña">
                        </div>

                        <div class="d-flex justify-content-between small mb-3">
                            <a href="index.php?action=register">Crear cuenta</a>
                        </div>

                        <button class="btn btn-primary w-100">INICIAR SESIÓN</button>

                    </form>

                </div>
            </div>

        </div>
    </div>

</section>

<?php require '../app/views/layouts/footer.php';?>

