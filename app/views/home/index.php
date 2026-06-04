<?php
// Carga dinámica de contenido público administrable.
// Si la base de datos falla, se usan datos fijos para que el index no quede en blanco.
require_once dirname(__DIR__, 3) . '/config/database.php';

$servicios = [];
$instalaciones = [];
$infoGimnasio = null;
$horarios = [];

try {
    $database = new Database();
    $db = $database->conectar();

    // SERVICIOS GRATUITOS DEL INDEX
    $stmt = $db->query("SELECT nombre, descripcion, imagen
                        FROM servicios
                        WHERE activo = 1
                        ORDER BY id_servicio ASC");
    $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // INSTALACIONES DEL INDEX
    $stmt = $db->query("SELECT titulo, descripcion, ruta
                        FROM imagenes_instalaciones
                        WHERE activa = 1
                        ORDER BY id_imagen ASC");
    $instalaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // INFORMACIÓN GENERAL DEL HERO Y CONTACTO
    $stmt = $db->query("SELECT *
                        FROM informacion_gimnasio
                        ORDER BY id_info ASC
                        LIMIT 1");
    $infoGimnasio = $stmt->fetch(PDO::FETCH_ASSOC);

    // HORARIO DEL GIMNASIO
    $stmt = $db->query("SELECT dia, hora_apertura, hora_cierre, cerrado
                        FROM horario_gimnasio
                        ORDER BY id_horario ASC");
    $horarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) {
    $servicios = [];
    $instalaciones = [];
    $infoGimnasio = null;
    $horarios = [];
}

// Datos de respaldo para el index si la base de datos está vacía.
if (empty($servicios)) {
    $servicios = [
        [
            'nombre' => 'Entrenamiento personal',
            'descripcion' => 'Planes personalizados adaptados a tus objetivos y seguimiento profesional.',
            'imagen' => 'img/asesor_entrenador.png'
        ],
        [
            'nombre' => 'Nutrición',
            'descripcion' => 'Asesoramiento nutricional profesional para mejorar rendimiento y salud.',
            'imagen' => 'img/mancuernas.png'
        ],
        [
            'nombre' => 'Clases guiadas',
            'descripcion' => 'HIIT, funcional, spinning y muchas clases más.',
            'imagen' => 'img/clase_zumba.png'
        ],
        [
            'nombre' => 'Seguimiento',
            'descripcion' => 'Control periódico de progreso y evolución física.',
            'imagen' => 'img/mancuernas.png'
        ]
    ];
}

$imagenesServicio = [
    'img/asesor_entrenador.png',
    'img/mancuernas.png',
    'img/clase_zumba.png',
    'img/mancuernas.png'
];

if (empty($instalaciones)) {
    $instalaciones = [
        [
            'titulo' => 'Zona de musculación',
            'descripcion' => 'Zona de entrenamiento de fuerza.',
            'ruta' => 'img/mancuernas.png'
        ],
        [
            'titulo' => 'Zona de cardio',
            'descripcion' => 'Zona de cintas y máquinas de cardio.',
            'ruta' => 'img/zona-cintas.png'
        ],
        [
            'titulo' => 'Zona de clases',
            'descripcion' => 'Sala de actividades dirigidas.',
            'ruta' => 'img/zona-clases.png'
        ],
        [
            'titulo' => 'Zona core',
            'descripcion' => 'Zona de entrenamiento funcional.',
            'ruta' => 'img/mancuernas.png'
        ]
    ];
}

$infoGimnasio = $infoGimnasio ?: [
    'nombre' => 'FitLab',
    'hero_titulo' => 'ENTRENA COMO\nUN ATLETA CON\nLO MEJOR DEL FITNESS',
    'hero_subtitulo' => 'Tu gimnasio abierto de alta calidad con servicios gratuitos para orientar tus entrenamientos.',
    'descripcion' => 'Gimnasio local con tienda, servicios personalizados y herramientas de seguimiento.',
    'direccion' => 'Calle Futbol, 52, 16003 Cuenca',
    'telefono' => '961 34 23 62',
    'email' => 'fitlab@gmail.com',
    'mapa_url' => 'https://maps.google.com/maps?q=cuenca&t=&z=13&ie=UTF8&iwloc=&output=embed'
];

// Devuelve una ruta de imagen válida para imágenes de /img, /uploads o URLs externas.
function asset_publico($ruta)
{
    $ruta = trim((string) $ruta);

    if ($ruta === '') {
        return 'img/mancuernas.png';
    }

    if (preg_match('/^https?:\/\//i', $ruta)) {
        return $ruta;
    }

    return htmlspecialchars($ruta, ENT_QUOTES, 'UTF-8');
}

// Formatea la hora HH:MM:SS a HH:MM
function hora_corta($hora)
{
    if (!$hora) {
        return '';
    }

    return substr($hora, 0, 5);
}
?>
<?php require __DIR__ . '/../layouts/header.php'; ?>

<!-- ================================================= -->
<!-- 🔥 CARRUSEL SOLO FONDO -->
<!-- ================================================= -->
<section class="portada-inicio position-relative">
    <div id="heroCarousel" class="carousel slide h-100" data-bs-ride="carousel">
        <div class="carousel-inner h-100">
            <!-- SLIDE 1 -->
            <div class="carousel-item active h-100">
                <div class="imagen-portada" style="background-image:url('img/imagen-hero1.png');"></div>
            </div>

            <!-- SLIDE 2 -->
            <div class="carousel-item h-100">
                <div class="imagen-portada" style="background-image:url('img/zona-cintas.png');"></div>
            </div>

            <!-- SLIDE 3 -->
            <div class="carousel-item h-100">
                <div class="imagen-portada" style="background-image:url('img/zona-clases.png');"></div>
            </div>
        </div>
    </div>

    <!-- ================================================= -->
    <!-- 🔥 OVERLAY -->
    <!-- ================================================= -->
    <div class="capa-oscura"></div>

    <!-- ================================================= -->
    <!-- 🔥 CONTENIDO FIJO -->
    <!-- ================================================= -->
    <div class="contenido-portada">
        <div class="container h-100 d-flex align-items-center">
            <div class="texto-portada-inicio">
                <?php
                $heroTitulo = $infoGimnasio['hero_titulo'] ?? 'ENTRENA COMO\nUN ATLETA CON\nLO MEJOR DEL FITNESS';
                $heroSubtitulo = $infoGimnasio['hero_subtitulo'] ?? ($infoGimnasio['descripcion'] ?? '');
                ?>

                <h1 class="titulo-portada"><?= nl2br(htmlspecialchars($heroTitulo, ENT_QUOTES, 'UTF-8')) ?></h1>
                <p class="descripcion-portada"><?= htmlspecialchars($heroSubtitulo, ENT_QUOTES, 'UTF-8') ?></p>

                <div class="botones-portada">
                    <a href="#instalaciones" class="btn btn-primary">VER INSTALACIONES</a>
                    <a href="#servicios" class="btn btn-outline-light">NUESTROS SERVICIOS</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================================================= -->
<!-- 🔥 SERVICIOS -->
<!-- ================================================= -->
<section class="py-5 bg-dark text-white text-center" id="servicios">
    <div class="container">
        <h2>NUESTROS SERVICIOS</h2>
        <p>
            Servicios gratuitos ofrecidos por <?= htmlspecialchars($infoGimnasio['nombre']) ?>
            para que los posibles clientes conozcan el gimnasio.
        </p>

        <div class="row mt-5">
            <?php foreach ($servicios as $i => $s): ?>
                <?php
                $imgServicio = !empty($s['imagen'])
                    ? asset_publico($s['imagen'])
                    : $imagenesServicio[$i % count($imagenesServicio)];
                ?>

                <div class="col-md-3 mb-4">
                    <div
                        class="card bg-dark border-light text-white tarjeta-servicio abrir-servicio"
                        data-title="<?= htmlspecialchars($s['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                        data-image="<?= htmlspecialchars($imgServicio, ENT_QUOTES, 'UTF-8') ?>"
                        data-description="<?= htmlspecialchars($s['descripcion'], ENT_QUOTES, 'UTF-8') ?>"
                    >
                        <!-- IMAGEN -->
                        <img
                            src="<?= htmlspecialchars($imgServicio, ENT_QUOTES, 'UTF-8') ?>"
                            class="card-img-top"
                            alt="<?= htmlspecialchars($s['nombre']) ?>"
                        >

                        <!-- BODY -->
                        <div class="card-body">
                            <h5><?= htmlspecialchars($s['nombre']) ?></h5>
                            <p class="small mb-0"><?= htmlspecialchars($s['descripcion']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ================================================= -->
<!-- 🔥 MODAL SERVICIO -->
<!-- ================================================= -->
<div id="serviceModal" class="ventana-servicio">
    <div class="contenido-ventana-servicio">
        <!-- CERRAR -->
        <button id="closeServiceModal" class="cerrar-ventana-servicio">x</button>

        <div class="row align-items-center g-4">
            <!-- IMAGEN -->
            <div class="col-lg-6">
                <img id="serviceModalImage" src="" class="imagen-ventana-servicio" alt="Servicio">
            </div>

            <!-- INFO -->
            <div class="col-lg-6">
                <h2 id="serviceModalTitle" class="titulo-ventana-servicio"></h2>
                <p id="serviceModalDescription" class="descripcion-ventana-servicio"></p>
            </div>
        </div>
    </div>
</div>

<!-- ================================================= -->
<!-- 🔥 INSTALACIONES -->
<!-- ================================================= -->
<section class="py-5 text-white text-center zona-instalaciones" id="instalaciones">
    <div class="container">
        <h2>NUESTRAS INSTALACIONES</h2>
        <p>Espacios diseñados para ofrecer la mejor experiencia de entrenamiento.</p>

        <div class="row mt-4">
            <?php foreach ($instalaciones as $img): ?>
                <div class="col-md-3 mb-4">
                    <div class="card bg-dark border-light text-white h-100">
                        <img
                            src="<?= asset_publico($img['ruta']) ?>"
                            class="img-fluid rounded card-img-top"
                            alt="<?= htmlspecialchars($img['titulo']) ?>"
                        >

                        <div class="card-body">
                            <h5><?= htmlspecialchars($img['titulo']) ?></h5>

                            <?php if (!empty($img['descripcion'])): ?>
                                <p class="small mb-0"><?= htmlspecialchars($img['descripcion']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ================================================= -->
<!-- 🔥 CONTACTO Y HORARIO -->
<!-- ================================================= -->
<section class="py-5 bg-dark text-white">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h4>¿Dónde estamos?</h4>

                <p>
                    <img src="img/iconos/icono-ubicacion.png" alt="Ubicación" width="20">
                    <?= htmlspecialchars($infoGimnasio['direccion']) ?>
                </p>

                <p>
                    <img src="img/iconos/icono-telefono.png" alt="Teléfono" width="20">
                    <?= htmlspecialchars($infoGimnasio['telefono']) ?>
                </p>

                <p>
                    <img src="img/iconos/icono-correo.png" alt="Correo" width="20">
                    <?= htmlspecialchars($infoGimnasio['email']) ?>
                </p>
            </div>

            <div class="col-md-4">
                <h4>Horario</h4>

                <?php if (!empty($horarios)): ?>
                    <?php foreach ($horarios as $h): ?>
                        <p>
                            <?= htmlspecialchars($h['dia']) ?>:
                            <?= (int) $h['cerrado'] === 1
                                ? 'Cerrado'
                                : htmlspecialchars(hora_corta($h['hora_apertura']) . ' - ' . hora_corta($h['hora_cierre'])) ?>
                        </p>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Lunes a Viernes: 6:00 - 1:00</p>
                    <p>Sábados: 8:00 - 22:00</p>
                    <p>Domingo y Festivos: 8:00 - 22:00</p>
                <?php endif; ?>
            </div>

            <div class="col-md-4">
                <iframe
                    src="<?= htmlspecialchars($infoGimnasio['mapa_url'] ?: 'https://maps.google.com/maps?q=cuenca&t=&z=13&ie=UTF8&iwloc=&output=embed', ENT_QUOTES, 'UTF-8') ?>"
                    width="100%"
                    height="200"
                    loading="lazy"
                ></iframe>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
