<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Lato&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- CSS -->
    <link rel="stylesheet" href="css/styles.css?v=<?php echo time(); ?>">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark menu-principal">

    <div class="container">

        <!-- LOGO -->
        <a class="navbar-brand" href="index.php">

            <img 
                src="/FitLab/public/img/logos/Logo_Nav.png"
                alt="FitLab"
                class="logo-menu"
            >

        </a>

        <!-- BOTÓN MÓVIL -->
        <button 
            class="navbar-toggler"
            data-bs-toggle="collapse"
            data-bs-target="#menu"
        >

            <span class="navbar-toggler-icon"></span>

        </button>

        <!-- MENÚ -->
        <div class="collapse navbar-collapse" id="menu">

            <ul class="navbar-nav mx-auto">

                <li class="nav-item">
                    <a class="nav-link" href="index.php#servicios">
                        Servicios
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="index.php?action=products">
                        Tienda
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="index.php#instalaciones">
                        Instalaciones
                    </a>
                </li>

                <li class="nav-item">

                    <a 
                        class="nav-link"
                        href="index.php?action=calculadoras"
                    >
                        Herramientas
                    </a>

                </li>

            </ul>

            <!-- ZONA DERECHA -->
            <div class="d-flex align-items-center gap-3">

            <?php if (isset($_SESSION['user'])): ?>

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

                                        <div class="d-flex justify-content-between align-items-center">

                                            <p class="m-0">
                                                <?php echo $item['nombre']; ?>
                                            </p>

                                            <span class="cantidad-carrito">

                                                x<?php echo $item['cantidad'] ?? 1; ?>

                                            </span>

                                        </div>

                                        <small>
                                            <?php echo $item['precio']; ?>€
                                        </small>

                                    </div>

                                    <a 
                                        href="index.php?action=quitarDelCarrito&index=<?php echo $index; ?>" 
                                        class="quitar-carrito"
                                    >
                                        ✖
                                    </a>

                                </div>

                            <?php endforeach; ?>

                            <hr>

                            <p>
                                <strong>
                                    Total: <?php echo $_SESSION['total'] ?? 0; ?>€
                                </strong>
                            </p>

                            <a 
                                href="index.php?action=checkout"
                                class="btn btn-primary w-100"
                            >
                                Pagar
                            </a>

                        <?php else: ?>

                            <p>Carrito vacío</p>

                        <?php endif; ?>

                    </div>

                </div>

                <!-- PERFIL -->
                <div class="dropdown menu-perfil">

                    <a class="icono-menu" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <!-- SOLO ADMIN -->
                        <?php if (
                            isset($_SESSION['user']) &&
                            $_SESSION['user']['id_rol'] == 1
                        ): ?>

                            <li>

                                <a 
                                    class="dropdown-item"
                                    href="index.php?action=createProduct"
                                >
                                    Crear producto
                                </a>

                            </li>

                        <?php endif; ?>

                        <!-- LOGOUT -->
                        <li>

                            <a 
                                class="dropdown-item"
                                href="index.php?action=logout"
                            >
                                Cerrar sesión
                            </a>

                        </li>

                    </ul>

                </div>

            <?php else: ?>

                <a 
                    href="index.php?action=login"
                    class="nav-link text-white"
                >
                    Login
                </a>

                <a 
                    href="index.php?action=register"
                    class="nav-link text-white"
                >
                    Register
                </a>

            <?php endif; ?>

            </div>

        </div>

    </div>

</nav>

<!-- ALERTAS -->

<?php if (isset($_SESSION['success'])): ?>

    <div class="aviso aviso-correcto">

        <?= $_SESSION['success']; ?>

    </div>

    <?php unset($_SESSION['success']); ?>

<?php endif; ?>


<?php if (isset($_SESSION['error'])): ?>

    <div class="aviso aviso-error">

        <?= $_SESSION['error']; ?>

    </div>

    <?php unset($_SESSION['error']); ?>

<?php endif; ?>


<?php if (isset($_SESSION['info'])): ?>

    <div class="aviso aviso-info">

        <?= $_SESSION['info']; ?>

    </div>

    <?php unset($_SESSION['info']); ?>

<?php endif; ?>