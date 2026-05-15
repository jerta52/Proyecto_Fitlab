<?php

require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/ProductController.php';
require_once __DIR__ . '/../app/controllers/CalculadoraController.php';

// OBTENER ACCIÓN DE LA URL
$action = $_GET['action'] ?? 'home';

// CREAR CONTROLADORES
$authController = new AuthController();

$productController = new ProductController();

$calculadoraController = new CalculadoraController();

// GESTIONAR RUTAS DE LA APLICACIÓN
switch ($action) {

    // LOGIN

    case 'login':
        $authController->mostrarLogin();
        break;

    case 'doLogin':
        $authController->iniciarSesion();
        break;

    // REGISTRO

    case 'register':
        $authController->mostrarRegistro();
        break;

    case 'doRegister':
        $authController->registrarUsuario();
        break;

    // CERRAR SESIÓN

    case 'logout':
        $authController->cerrarSesion();
        break;

    // PRODUCTOS

    case 'products':
        $productController->mostrarTienda();
        break;

    case 'agregarAlCarrito':
        $productController->agregarAlCarrito();
        break;

    case 'quitarDelCarrito':
        $productController->quitarDelCarrito();
        break;

    case 'createProduct':
        require __DIR__ . '/../app/views/products/create.php';
        break;

    case 'guardarProducto':
        $productController->guardarProducto();
        break;

    case 'catalogo':
        $productController->mostrarCatalogo();
        break;

    case 'mostrarEditarProducto':
        $productController->mostrarEditarProducto();
        break;

    case 'actualizarProducto':
        $productController->actualizarProducto();
        break;

    case 'eliminarProducto':
        $productController->eliminarProducto();
        break;

    // CHECKOUT

    case 'checkout':
        $productController->mostrarPago();
        break;

    case 'procesarPago':
        $productController->procesarPago();
        break;

    // CALCULADORAS

    case 'calculadoras':
        require __DIR__ . '/../app/views/herramientas/calculadoras.php';
        break;

    case 'guardarCalorias':
        $calculadoraController->guardarCalorias();
        break;

    case 'guardarImc':
        $calculadoraController->guardarImc();
        break;

    // HOME POR DEFECTO

    default:
        require __DIR__ . '/../app/views/home/index.php';
}