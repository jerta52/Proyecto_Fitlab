<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';

class ProductController
{
    // Muestra la tienda y filtra productos por categoría si existe
    public function mostrarTienda()
    {
        $model = new Product();

        $categoriaActual = isset($_GET['categoria']) && $_GET['categoria'] !== ''
            ? (int) $_GET['categoria']
            : null;

        $products = $categoriaActual
            ? $model->obtenerPorCategoria($categoriaActual)
            : $model->obtenerTodos();

        // Las categorías se cargan desde la base de datos para que las nuevas aparezcan en el filtro.
        $categorias = $model->obtenerCategorias();

        require __DIR__ . '/../views/products/tienda.php';
    }

    // Añade un producto al carrito del cliente registrado
    public function agregarAlCarrito()
    {
        AuthHelper::requireCliente();

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            die('Producto no válido');
        }

        $model = new Product();
        $product = $model->obtenerPorId($id);

        if (!$product || (int) $product['stock'] <= 0) {
            die('Producto no disponible');
        }

        // GUARDAR EN BASE DE DATOS
        $model->agregarAlCarrito($_SESSION['user']['id_usuario'], $id);

        // GUARDAR PRODUCTOS EN SESIÓN
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $found = false;

        foreach ($_SESSION['cart'] as &$item) {
            if ((int) $item['id'] === (int) $product['id_producto']) {
                $item['cantidad']++;
                $found = true;
                break;
            }
        }

        // AÑADIR PRODUCTO SI NO EXISTE EN EL CARRITO
        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product['id_producto'],
                'nombre' => $product['nombre_producto'],
                'precio' => $product['precio'],
                'cantidad' => 1
            ];
        }

        // ACTUALIZAR TOTAL DEL CARRITO
        $_SESSION['total'] = ($_SESSION['total'] ?? 0) + (float) $product['precio'];

        header('Location: index.php?action=products');
        exit;
    }

    // Elimina un producto del carrito
    public function quitarDelCarrito()
    {
        AuthHelper::requireCliente();

        $index = filter_input(INPUT_GET, 'index', FILTER_VALIDATE_INT);

        if ($index !== false && isset($_SESSION['cart'][$index])) {
            $_SESSION['total'] -= ($_SESSION['cart'][$index]['precio'] * ($_SESSION['cart'][$index]['cantidad'] ?? 1));

            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }

        header('Location: index.php?action=products');
        exit;
    }

    // Muestra el formulario para crear productos
    public function createProduct()
    {
        AuthHelper::requireAdmin();

        $model = new Product();
        $categorias = $model->obtenerCategorias();

        require __DIR__ . '/../views/products/create.php';
    }

    // Guarda un nuevo producto en la base de datos
    public function guardarProducto()
    {
        AuthHelper::requireAdmin();

        $nombre = trim($_POST['nombre_producto'] ?? '');
        $precio = $_POST['precio'] ?? null;
        $stock = $_POST['stock'] ?? null;
        $descripcion = trim($_POST['descripcion'] ?? '');
        $id_categoria = filter_input(INPUT_POST, 'id_categoria', FILTER_VALIDATE_INT);

        if ($nombre === '' || !is_numeric($precio) || $precio <= 0 || !is_numeric($stock) || $stock < 0 || !$id_categoria) {
            die('Datos de producto no válidos');
        }

        // MANEJAR IMAGEN
        $imagenNombre = $this->validarYSubirImagen($_FILES['imagen'] ?? null, true);

        // GUARDAR PRODUCTO EN BASE DE DATOS
        $model = new Product();
        $model->crearProducto($nombre, $precio, $stock, $descripcion, $imagenNombre, $id_categoria);

        header('Location: index.php?action=catalogo');
        exit;
    }

    // Muestra el catálogo filtrando productos si existen filtros
    public function mostrarCatalogo()
    {
        $model = new Product();

        $categoria = isset($_GET['categoria']) && $_GET['categoria'] !== ''
            ? (int) $_GET['categoria']
            : null;

        $min = isset($_GET['min']) && is_numeric($_GET['min'])
            ? (float) $_GET['min']
            : null;

        $max = isset($_GET['max']) && is_numeric($_GET['max'])
            ? (float) $_GET['max']
            : null;

        if ($min !== null && $max !== null && $min > $max) {
            [$min, $max] = [$max, $min];
        }

        $products = $model->obtenerFiltrados($categoria, $min, $max);
        $categorias = $model->obtenerCategorias();

        $categoriaActual = $categoria ?? '';
        $precioMinimoActual = $min ?? '';
        $precioMaximoActual = $max ?? '';

        require __DIR__ . '/../views/products/catalogo.php';
    }

    // Muestra el formulario de edición de un producto
    public function mostrarEditarProducto()
    {
        AuthHelper::requireAdmin();

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            die('ID no recibido');
        }

        $model = new Product();
        $product = $model->obtenerPorId($id);
        $categorias = $model->obtenerCategorias();

        if (!$product) {
            die('Producto no encontrado');
        }

        require __DIR__ . '/../views/products/edit.php';
    }

    // Actualiza los datos de un producto existente
    public function actualizarProducto()
    {
        AuthHelper::requireAdmin();

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $nombre = trim($_POST['nombre_producto'] ?? '');
        $precio = $_POST['precio'] ?? null;
        $stock = $_POST['stock'] ?? null;
        $descripcion = trim($_POST['descripcion'] ?? '');
        $id_categoria = filter_input(INPUT_POST, 'id_categoria', FILTER_VALIDATE_INT);

        if (!$id || $nombre === '' || !is_numeric($precio) || $precio <= 0 || !is_numeric($stock) || $stock < 0 || !$id_categoria) {
            die('Datos de producto no válidos');
        }

        $imagenNombre = $_POST['imagen_actual'] ?? '';

        // ACTUALIZAR IMAGEN SI SE SUBE UNA NUEVA
        if (!empty($_FILES['imagen']['name'])) {
            $imagenNombre = $this->validarYSubirImagen($_FILES['imagen'], false);
        }

        $model = new Product();
        $model->actualizarProducto($id, $nombre, $precio, $stock, $descripcion, $imagenNombre, $id_categoria);

        header('Location: index.php?action=catalogo');
        exit;
    }

    // Elimina un producto de la base de datos
    public function eliminarProducto()
    {
        AuthHelper::requireAdmin();

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            die('ID no recibido');
        }

        $model = new Product();
        $model->eliminarProducto($id);

        header('Location: index.php?action=catalogo');
        exit;
    }

    // Muestra la vista de pago del carrito
    public function mostrarPago()
    {
        AuthHelper::requireCliente();

        if (empty($_SESSION['cart'])) {
            $_SESSION['error'] = 'El carrito está vacío.';
            header('Location: index.php?action=products');
            exit;
        }

        require __DIR__ . '/../views/products/checkout.php';
    }

    // Procesa el pago y crea el pedido
    public function procesarPago()
    {
        AuthHelper::requireCliente();

        // Esta acción solo debe procesarse por POST desde el formulario de checkout.
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = 'Acceso no válido al proceso de pago.';
            header('Location: index.php?action=checkout');
            exit;
        }

        if (empty($_SESSION['cart'])) {
            $_SESSION['error'] = 'El carrito está vacío.';
            header('Location: index.php?action=products');
            exit;
        }

        // VALIDACIONES DEL FORMULARIO
        // Ante cualquier error se vuelve al checkout para evitar pantallas blancas o errores 500.
        $nombreEnvio = trim($_POST['nombre_envio'] ?? '');
        $direccionEnvio = trim($_POST['direccion_envio'] ?? '');
        $telefonoEnvio = trim($_POST['telefono_envio'] ?? '');
        $cardHolder = trim($_POST['cardHolder'] ?? '');
        $paymentMethod = trim($_POST['paymentMethod'] ?? 'Tarjeta');
        $card = str_replace(' ', '', $_POST['cardNumber'] ?? '');
        $cvv = trim($_POST['cvv'] ?? '');
        $expiry = trim($_POST['expiry'] ?? '');

        if ($nombreEnvio === '' || $direccionEnvio === '' || $telefonoEnvio === '' || $cardHolder === '') {
            $_SESSION['error'] = 'Completa todos los datos de envío y pago.';
            header('Location: index.php?action=checkout');
            exit;
        }

        // VALIDAR TELÉFONO
        if (!preg_match('/^[0-9+ ]{9,15}$/', $telefonoEnvio)) {
            $_SESSION['error'] = 'El teléfono no tiene un formato válido.';
            header('Location: index.php?action=checkout');
            exit;
        }

        // VALIDAR TARJETA
        if (!preg_match('/^[0-9]{16}$/', $card)) {
            $_SESSION['error'] = 'Número de tarjeta inválido. Debe tener 16 dígitos.';
            header('Location: index.php?action=checkout');
            exit;
        }

        // VALIDAR CVV
        if (!preg_match('/^[0-9]{3}$/', $cvv)) {
            $_SESSION['error'] = 'CVV inválido. Debe tener 3 dígitos.';
            header('Location: index.php?action=checkout');
            exit;
        }

        // VALIDAR FECHA DE CADUCIDAD
        if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expiry, $m)) {
            $_SESSION['error'] = 'Fecha de caducidad inválida. Usa el formato MM/YY.';
            header('Location: index.php?action=checkout');
            exit;
        }

        $mes = (int) $m[1];
        $anio = 2000 + (int) $m[2];
        $fechaCad = DateTime::createFromFormat('Y-m-d H:i:s', sprintf('%04d-%02d-01 23:59:59', $anio, $mes));

        if (!$fechaCad) {
            $_SESSION['error'] = 'Fecha de caducidad inválida.';
            header('Location: index.php?action=checkout');
            exit;
        }

        $fechaCad->modify('last day of this month 23:59:59');

        if ($fechaCad < new DateTime()) {
            $_SESSION['error'] = 'La tarjeta está caducada.';
            header('Location: index.php?action=checkout');
            exit;
        }

        $model = new Product();

        try {
            // CALCULAR TOTAL DEL CARRITO
            $total = 0;

            foreach ($_SESSION['cart'] as $itemTotal) {
                $total += (int) ($itemTotal['cantidad'] ?? 1) * (float) ($itemTotal['precio'] ?? 0);
            }

            if ($total <= 0) {
                $_SESSION['error'] = 'El total del pedido no es válido.';
                header('Location: index.php?action=checkout');
                exit;
            }

            // CREAR PEDIDO
            $model->beginTransaction();
            $id_pedido = $model->crearPedido($_SESSION['user']['id_usuario'], $total, $paymentMethod);

            // GUARDAR DETALLES DEL PEDIDO Y RESTAR STOCK
            foreach ($_SESSION['cart'] as $item) {
                $idProducto = (int) ($item['id'] ?? 0);
                $cantidad = (int) ($item['cantidad'] ?? 1);
                $precio = (float) ($item['precio'] ?? 0);
                $nombre = $item['nombre'] ?? 'producto';

                if ($idProducto <= 0 || $cantidad <= 0 || $precio <= 0) {
                    throw new Exception('Hay un producto no válido en el carrito.');
                }

                if (!$model->reducirStock($idProducto, $cantidad)) {
                    throw new Exception('No hay stock suficiente para ' . $nombre . '.');
                }

                $subtotal = $cantidad * $precio;
                $model->crearDetallePedido($cantidad, $precio, $subtotal, $id_pedido, $idProducto);
            }

            $model->commit();
        } catch (Throwable $e) {
            $model->rollBack();
            $this->registrarErrorPago($e->getMessage());

            $_SESSION['error'] = 'No se pudo finalizar la compra: ' . $e->getMessage();
            header('Location: index.php?action=checkout');
            exit;
        }

        // Email de confirmación eliminado por petición del proyecto.
        // El pedido queda registrado y el usuario puede consultarlo en Mis pedidos.
        $_SESSION['success'] = 'Pedido realizado correctamente.';

        // VACIAR CARRITO
        unset($_SESSION['cart'], $_SESSION['total']);

        // REDIRECCIÓN FINAL
        header('Location: index.php?action=misPedidos');
        exit;
    }

    // Guarda errores del pago en un log para poder revisarlos sin mostrar error 500
    private function registrarErrorPago($mensaje)
    {
        $dir = dirname(__DIR__, 2) . '/logs';

        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }

        @file_put_contents(
            $dir . '/pago_errors.log',
            '[' . date('Y-m-d H:i:s') . '] ' . $mensaje . PHP_EOL,
            FILE_APPEND
        );
    }

    // Valida y sube la imagen de un producto
    private function validarYSubirImagen($file, $obligatoria)
    {
        if (!$file || empty($file['name'])) {
            if ($obligatoria) {
                die('La imagen es obligatoria');
            }

            return '';
        }

        $permitidos = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];

        if (!isset($permitidos[$file['type']])) {
            die('Tipo de imagen no permitido');
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            die('La imagen supera 2MB');
        }

        $nombre = uniqid('producto_', true) . '.' . $permitidos[$file['type']];
        $destino = dirname(__DIR__, 2) . '/public/img/' . $nombre;

        move_uploaded_file($file['tmp_name'], $destino);

        return $nombre;
    }
}
