<?php
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/ProductController.php';
require_once __DIR__ . '/../app/controllers/CalculadoraController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';
require_once __DIR__ . '/../app/controllers/OrderController.php';

$action = $_GET['action'] ?? 'home';

$authController = new AuthController();
$productController = new ProductController();
$calculadoraController = new CalculadoraController();
$adminController = new AdminController();
$orderController = new OrderController();

// Controlador frontal sencillo: recibe la acción y llama al controlador correspondiente.
switch ($action) {
    // PÁGINAS PÚBLICAS
    case 'home':
        if (class_exists('AuthHelper') && AuthHelper::isAdmin()) {
            header('Location: index.php?action=adminDashboard');
            exit;
        }
        require __DIR__ . '/../app/views/home/index.php';
        break;

    case 'login':
        $authController->mostrarLogin();
        break;

    case 'doLogin':
        $authController->iniciarSesion();
        break;

    case 'register':
        $authController->mostrarRegistro();
        break;


    case 'doRegister':
        $authController->registrarUsuario();
        break;

    case 'logout':
        $authController->cerrarSesion();
        break;

    // TIENDA Y PRODUCTOS
    case 'products':
        $productController->mostrarTienda();
        break;

    case 'catalogo':
        $productController->mostrarCatalogo();
        break;

    case 'agregarAlCarrito':
        $productController->agregarAlCarrito();
        break;

    case 'quitarDelCarrito':
        $productController->quitarDelCarrito();
        break;

    case 'createProduct':
        $productController->createProduct();
        break;

    case 'guardarProducto':
        $productController->guardarProducto();
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

    case 'checkout':
        $productController->mostrarPago();
        break;

    case 'procesarPago':
        $productController->procesarPago();
        break;

    // CALCULADORAS DEL CLIENTE
    case 'calculadoras':
        $calculadoraController->mostrarCalculadoras();
        break;

    case 'guardarCalorias':
        $calculadoraController->guardarCalorias();
        break;

    case 'guardarImc':
        $calculadoraController->guardarImc();
        break;

    // PEDIDOS DEL CLIENTE
    case 'misPedidos':
        $orderController->misPedidos();
        break;

    case 'detallePedido':
        $orderController->detalle();
        break;

    // PANEL DE ADMINISTRACIÓN
    case 'adminDashboard':
        $adminController->dashboard();
        break;

    case 'adminList':
        $adminController->listar();
        break;

    case 'adminCreate':
        $adminController->crear();
        break;

    case 'adminStore':
        $adminController->guardar();
        break;

    case 'adminEdit':
        $adminController->editar();
        break;

    case 'adminUpdate':
        $adminController->actualizar();
        break;

    case 'adminDelete':
        $adminController->eliminar();
        break;

    case 'adminPedidos':
        $adminController->pedidos();
        break;

    case 'adminActualizarEstadoPedido':
        $adminController->actualizarEstadoPedido();
        break;

    case 'adminSeleccionarDiseno':
        $adminController->seleccionarDiseno();
        break;

    default:
        require __DIR__ . '/../app/views/home/index.php';
        break;
}
