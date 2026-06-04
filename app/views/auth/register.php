<?php require '../app/views/layouts/header.php'; ?>

<section class="fondo-formulario d-flex align-items-center">

    <div class="container">
        <div class="row align-items-center">

            <!-- TEXTO IZQUIERDA -->
            <div class="col-md-6 text-white">
                <h1 class="titulo-formulario">
                    ÚNETE A <br>
                    <span>FITLAB</span>
                </h1>
            </div>

            <!-- FORM REGISTER -->
            <div class="col-md-6 d-flex justify-content-center">
                <div class="caja-formulario">

                    <h3 class="text-center mb-4 text-white">CREAR CUENTA</h3>

                    <form action="index.php?action=doRegister" method="POST">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Nombre</label>
                                <input type="text" name="nombre" class="form-control" placeholder="Tu nombre">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Apellidos</label>
                                <input type="text" name="apellidos" class="form-control" placeholder="Tus apellidos">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Correo electrónico</label>
                            <input type="email" name="email" class="form-control" placeholder="Tu correo electrónico">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password">Contraseña</label>
                                <input type="password" name="password" class="form-control" placeholder="Tu contraseña">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Confirmar contraseña</label>
                                <input type="password" name="confirm_password" class="form-control" placeholder="Confirmar contraseña">
                            </div>
                        </div>

                        <button class="btn btn-primary w-100 mt-2">CREAR CUENTA</button>

                        <p class="text-center small mt-3 text-white">
                            ¿Ya tienes cuenta? 
                            <a href="index.php?action=login">Iniciar sesión</a>
                        </p>

                    </form>

                </div>
            </div>

        </div>
    </div>

</section>

<?php require '../app/views/layouts/footer.php'; ?>