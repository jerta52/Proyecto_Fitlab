<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Diseño por defecto. Si hay un diseño activo en base de datos, se sustituye por esos valores.
$apariencia = [
    'color_principal' => '#78B7FF',
    'color_secundario' => '#0077FF',
    'color_fondo' => '#101112',
    'fuente_principal' => 'Lato',
    'fuente_titulos' => 'Bebas Neue',
    'tamano_fuente_base' => 16,
    'activo' => 1
];

try {
    require_once dirname(__DIR__, 3) . '/config/database.php';

    $databaseHeader = new Database();
    $dbHeader = $databaseHeader->conectar();

    $queryHeader = "SELECT color_principal,
                           color_secundario,
                           color_fondo,
                           fuente_principal,
                           fuente_titulos,
                           tamano_fuente_base
                    FROM configuracion_apariencia
                    WHERE activo = 1
                    ORDER BY id_config ASC
                    LIMIT 1";

    $stmtHeader = $dbHeader->query($queryHeader);
    $cfgHeader = $stmtHeader->fetch(PDO::FETCH_ASSOC);

    if ($cfgHeader) {
        $apariencia = array_merge($apariencia, $cfgHeader);
    }
} catch (Throwable $e) {
    // Si falla la base de datos, se mantiene el diseño default para no romper la página.
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>FitLab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fuentes -->
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Lato&family=Montserrat:wght@400;600;700&family=Oswald:wght@400;600&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- CSS -->
    <link rel="stylesheet" href="css/styles.css?v=<?php echo time(); ?>">

    <!-- Variables del diseño visual activo -->
    <style>
        :root {
            --fitlab-color-principal: <?= htmlspecialchars($apariencia['color_principal'], ENT_QUOTES, 'UTF-8') ?>;
            --fitlab-color-secundario: <?= htmlspecialchars($apariencia['color_secundario'], ENT_QUOTES, 'UTF-8') ?>;
            --fitlab-color-fondo: <?= htmlspecialchars($apariencia['color_fondo'], ENT_QUOTES, 'UTF-8') ?>;
            --fitlab-fuente-principal: <?= htmlspecialchars($apariencia['fuente_principal'], ENT_QUOTES, 'UTF-8') ?>;
            --fitlab-fuente-titulos: <?= htmlspecialchars($apariencia['fuente_titulos'], ENT_QUOTES, 'UTF-8') ?>;
            --fitlab-tamano-base: <?= (int) $apariencia['tamano_fuente_base'] ?>px;
        }

        body {
            background-color: var(--fitlab-color-fondo);
            font-family: var(--fitlab-fuente-principal), Arial, sans-serif;
            font-size: var(--fitlab-tamano-base);
        }

        h1,
        h2,
        h3,
        .titulo-portada {
            font-family: var(--fitlab-fuente-titulos), Arial, sans-serif;
        }

        .btn,
        .btn:visited,
        button.btn,
        input[type=submit].btn {
            color: #fff !important;
        }

        .btn-primary,
        .caja-formulario .btn-primary,
        .tarjeta-calculadora .btn-primary {
            background: var(--fitlab-color-principal) !important;
            border-color: var(--fitlab-color-principal) !important;
            color: #fff !important;
        }

        .btn-primary:hover,
        .caja-formulario .btn-primary:hover,
        .tarjeta-calculadora .btn-primary:hover {
            background: var(--fitlab-color-secundario) !important;
            border-color: var(--fitlab-color-secundario) !important;
            color: #fff !important;
        }

        .btn-outline-light,
        .boton-secundario {
            border-color: var(--fitlab-color-secundario) !important;
            color: #fff !important;
        }

        .btn-outline-light:hover,
        .boton-secundario:hover {
            background: var(--fitlab-color-secundario) !important;
            border-color: var(--fitlab-color-secundario) !important;
            color: #fff !important;
        }

        .btn-light,
        .btn-warning,
        .btn-info,
        .btn-secondary,
        .btn-danger,
        .btn-success,
        .btn-dark {
            color: #fff !important;
        }

        .btn-light {
            background: var(--fitlab-color-principal) !important;
            border-color: var(--fitlab-color-principal) !important;
        }

        .nav-link.active,
        .titulo-portada span,
        .precio-producto,
        .precio-ventana {
            color: var(--fitlab-color-principal) !important;
        }

        .menu-principal,
        footer,
        .bg-dark,
        .caja-filtros,
        .tarjeta-producto,
        .tarjeta-servicio {
            background-color: var(--fitlab-color-fondo) !important;
        }

        .card,
        .form-control,
        .form-select {
            font-size: var(--fitlab-tamano-base);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark menu-principal">
    <div class="container">
        <!-- LOGO -->
        <a class="navbar-brand" href="index.php">
            <img src="img/logos/Logo_Nav.png" alt="FitLab" class="logo-menu">
        </a>

        <!-- BOTÓN MÓVIL -->
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- MENÚ -->
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php#servicios">Servicios</a>
                </li>

                <?php if (!isset($_SESSION['user']) || (int) $_SESSION['user']['id_rol'] === 2): ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php?action=products">Tienda</a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link" href="index.php#instalaciones">Instalaciones</a>
                </li>

                <?php if (isset($_SESSION['user']) && (int) $_SESSION['user']['id_rol'] === 2): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=calculadoras">Herramientas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=misPedidos">Mis pedidos</a>
                    </li>
                <?php endif; ?>

                <?php if (isset($_SESSION['user']) && (int) $_SESSION['user']['id_rol'] === 1): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?action=adminDashboard">Panel administrador</a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- ZONA DERECHA -->
            <div class="d-flex align-items-center gap-3">
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ((int) $_SESSION['user']['id_rol'] === 2): ?>
                        <!-- CARRITO -->
                        <div class="dropdown">
                            <a class="icono-menu" data-bs-toggle="dropdown">
                                <i class="bi bi-cart"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end p-3 caja-carrito">
                                <?php if (!empty($_SESSION['cart'])): ?>
                                    <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                                        <div class="linea-carrito d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="m-0">
                                                    <?php echo htmlspecialchars($item['nombre']); ?>
                                                    <span class="cantidad-carrito">x<?php echo (int) ($item['cantidad'] ?? 1); ?></span>
                                                </p>
                                                <small><?php echo htmlspecialchars($item['precio']); ?>€</small>
                                            </div>

                                            <a href="index.php?action=quitarDelCarrito&index=<?php echo $index; ?>" class="quitar-carrito">✖</a>
                                        </div>
                                    <?php endforeach; ?>

                                    <hr>
                                    <p><strong>Total: <?php echo htmlspecialchars($_SESSION['total'] ?? 0); ?>€</strong></p>
                                    <a href="index.php?action=checkout" class="btn btn-primary w-100">Pagar</a>
                                <?php else: ?>
                                    <p>Carrito vacío</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- PERFIL -->
                    <div class="dropdown menu-perfil">
                        <a class="icono-menu" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ((int) $_SESSION['user']['id_rol'] === 1): ?>
                                <!-- SOLO ADMIN -->
                                <li>
                                    <a class="dropdown-item" href="index.php?action=adminDashboard">Panel administrador</a>
                                </li>
                            <?php else: ?>
                                <li>
                                    <a class="dropdown-item" href="index.php?action=misPedidos">Mis pedidos</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="index.php?action=calculadoras">Calculadoras</a>
                                </li>
                            <?php endif; ?>

                            <!-- LOGOUT -->
                            <li>
                                <a class="dropdown-item" href="index.php?action=logout">Cerrar sesión</a>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="index.php?action=login" class="nav-link text-white">Login</a>
                    <a href="index.php?action=register" class="nav-link text-white">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- ALERTAS -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="aviso aviso-correcto"><?= htmlspecialchars($_SESSION['success']); ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="aviso aviso-error"><?= htmlspecialchars($_SESSION['error']); ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['info'])): ?>
    <div class="aviso aviso-info"><?= htmlspecialchars($_SESSION['info']); ?></div>
    <?php unset($_SESSION['info']); ?>
<?php endif; ?>
