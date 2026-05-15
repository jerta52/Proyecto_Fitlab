<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="portada-inicio position-relative">


    <!-- CARRUSEL -->
    <div id="heroCarousel"
         class="carousel slide h-100"
         data-bs-ride="carousel">

        <div class="carousel-inner h-100">

            <!-- SLIDE 1 -->
            <div class="carousel-item active h-100">

                <div class="imagen-portada"
                     style="background-image:url('img/imagen-hero1.png');">
                </div>

            </div>

            <!-- SLIDE 2 -->
            <div class="carousel-item h-100">

                <div class="imagen-portada"
                     style="background-image:url('img/zona-cintas.png');">
                </div>

            </div>

            <!-- SLIDE 3 -->
            <div class="carousel-item h-100">

                <div class="imagen-portada"
                     style="background-image:url('img/zona-clases.png');">
                </div>

            </div>

        </div>

    </div>


    <!-- OVERLAY -->
    <div class="capa-oscura"></div>

   
    <!-- CONTENIDO FIJO ENCIMA DEL CARRUSEL-->
    <div class="contenido-portada">

        <div class="container h-100 d-flex align-items-center">

            <div class="texto-portada-inicio">

                <h1 class="titulo-portada">

                    ENTRENA COMO <br>
                    UN ATLETA CON <br>

                    <span>
                        LO MEJOR
                    </span>

                    DEL FITNESS

                </h1>

                <p class="descripcion-portada">

                    Tu gimnasio abierto de 6AM a 1AM

                </p>

                <div class="botones-portada">

                    <a href="#instalaciones"
                       class="btn btn-primary">

                        VER INSTALACIONES

                    </a>

                    <a href="#servicios"
                       class="btn btn-outline-light">

                        NUESTROS SERVICIOS

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>
<section class="py-5 bg-dark text-white text-center"
         id="servicios">

    <div class="container">

        <h2>
            NUESTROS SERVICIOS
        </h2>

        <p>
            Descubre todo lo que FitLab puede ofrecerte.
        </p>

        <div class="row mt-5">

            <?php

            $servicios = [

                [
                    "img" => "img/asesor_entrenador.png",

                    "titulo" => "Entrenamiento personal",

                    "descripcion" =>
                    "Planes personalizados adaptados a tus objetivos y seguimiento profesional."
                ],

                [
                    "img" => "img/mancuernas.png",

                    "titulo" => "Nutrición",

                    "descripcion" =>
                    "Asesoramiento nutricional profesional para mejorar rendimiento y salud."
                ],

                [
                    "img" => "img/clase_zumba.png",

                    "titulo" => "Clases guiadas",

                    "descripcion" =>
                    "HIIT, funcional, spinning y muchas clases más."
                ],

                [
                    "img" => "img/mancuernas.png",

                    "titulo" => "Seguimiento",

                    "descripcion" =>
                    "Control periódico de progreso y evolución física."
                ]

            ];

            foreach ($servicios as $s):

            ?>

    
            <!-- CARD -->
            
            <div class="col-md-3 mb-4">

                <div class="card bg-dark border-light text-white
                            tarjeta-servicio abrir-servicio"

                     data-title="<?php echo $s['titulo']; ?>"

                     data-image="<?php echo $s['img']; ?>"

                     data-description="<?php echo $s['descripcion']; ?>">

                    <!-- IMAGEN -->
                    <img src="<?php echo $s['img']; ?>"
                         class="card-img-top">

                    <!-- BODY -->
                    <div class="card-body">

                        <h5>
                            <?php echo $s['titulo']; ?>
                        </h5>

                    </div>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

    </div>

</section>


<!-- MODAL -->

<div id="serviceModal"
     class="ventana-servicio">

    <div class="contenido-ventana-servicio">

        <!-- CERRAR -->
        <button id="closeServiceModal"
                class="cerrar-ventana-servicio">

            x

        </button>

        <div class="row align-items-center g-4">

            <!-- IMAGEN -->
            <div class="col-lg-6">

                <img id="serviceModalImage"
                     src=""
                     class="imagen-ventana-servicio">

            </div>

            <!-- INFO -->
            <div class="col-lg-6">

                <h2 id="serviceModalTitle"
                    class="titulo-ventana-servicio">
                </h2>

                <p id="serviceModalDescription"
                   class="descripcion-ventana-servicio">
                </p>

            </div>

        </div>

    </div>

</div>

<!-- INSTALACIONES -->
<section class="py-5 text-white text-center zona-instalaciones" id="instalaciones">
    <div class="container">
        <h2>NUESTRAS INSTALACIONES</h2>

        <div class="row mt-4">

            <?php
            $instalaciones = ["mancuernas.png","zona-cintas.png","zona-clases.png","mancuernas.png"];
            foreach ($instalaciones as $img):
            ?>

            <div class="col-md-3">
                <img src="img/<?php echo $img; ?>" class="img-fluid rounded">
            </div>

            <?php endforeach; ?>

        </div>
    </div>
</section>

<!-- CONTACTO -->
<section class="py-5 bg-dark text-white">
    <div class="container">
        <div class="row">

            <div class="col-md-4">
                <h4>¿Dónde estamos?</h4>
                <p><img src="img/iconos/icono-ubicacion.png" alt="Ubicación" width="20px">
                    Calle Futbol, 52</p>
                    <p>16003, Cuenca</p>
                <p><img src="img/iconos/icono-telefono.png"  alt="Telefono" width="20px">961 34 23 62</p>
                <p><img src="img/iconos/icono-correo.png"  alt="Correo" width="20px">fitlab@gmail.com</p>
            </div>

            <div class="col-md-4">
                <h4>Horario</h4>
                <p>Lunes a Viernes: 6:00 - 1:00</p>
                <p>Sábados: 8:00 - 22:00</p>
                <p>Domingo y Festivos: 8:00 - 22:00</p>
            </div>

            <div class="col-md-4">
                <iframe src="https://maps.google.com/maps?q=cuenca&t=&z=13&ie=UTF8&iwloc=&output=embed" 
                width="100%" height="200"></iframe>
            </div>

        </div>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>