<?php
require_once __DIR__ . '/../models/Product.php';

class ProductController {

    // Muestra la tienda y filtra productos por categoría si existe
    public function mostrarTienda() {

        $model = new Product();

        if (isset($_GET['categoria'])) {
            $products = $model->obtenerPorCategoria($_GET['categoria']);
        } else {
            $products = $model->obtenerTodos();
        }

        require __DIR__ . '/../views/products/tienda.php';
    }

    // Añade un producto al carrito y lo guarda en sesión
    public function agregarAlCarrito() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = $_GET['id'];

        $model = new Product();
        $product = $model->obtenerPorId($id);

        if (!$product) {
            die("Producto no encontrado");
        }

        // GUARDAR EN BASE DE DATOS SI EL USUARIO ESTÁ LOGUEADO
        if (isset($_SESSION['user'])) {

            $userId = $_SESSION['user']['id_usuario'];

            $model->agregarAlCarrito($userId, $id);
        }

        // GUARDAR PRODUCTOS EN SESIÓN
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $found = false;

        foreach ($_SESSION['cart'] as &$item) {

            if ($item['id'] == $product['id_producto']) {

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
        $_SESSION['total'] =
            ($_SESSION['total'] ?? 0) + $product['precio'];

        header("Location: index.php?action=products");

        exit;
    }

    // Elimina un producto del carrito
    public function quitarDelCarrito() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $index = $_GET['index'];

        if (isset($_SESSION['cart'][$index])) {

            $_SESSION['total'] -=
                $_SESSION['cart'][$index]['precio'];

            unset($_SESSION['cart'][$index]);

            $_SESSION['cart'] =
                array_values($_SESSION['cart']);
        }

        header("Location: index.php?action=products");

        exit;
    }

    // Guarda un nuevo producto en la base de datos
    public function guardarProducto() {

        $nombre = $_POST['nombre_producto'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];
        $descripcion = $_POST['descripcion'];

        // MANEJAR IMAGEN
        $imagenNombre = $_FILES['imagen']['name'];
        $rutaTemporal = $_FILES['imagen']['tmp_name'];

        // RUTA FINAL DE LA IMAGEN
        $rutaDestino =
            __DIR__ . '/../../public/img/' . $imagenNombre;

        move_uploaded_file($rutaTemporal, $rutaDestino);

        // GUARDAR PRODUCTO EN BASE DE DATOS
        $model = new Product();

        $model->crearProducto(
            $nombre,
            $precio,
            $stock,
            $descripcion,
            $imagenNombre
        );

        header("Location: index.php?action=products");
    }

    // Muestra el catálogo filtrando productos si existen filtros
    public function mostrarCatalogo() {

        $model = new Product();

        $categoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';
        $min = isset($_GET['min']) ? trim($_GET['min']) : '';
        $max = isset($_GET['max']) ? trim($_GET['max']) : '';

        $categoria = $categoria !== '' ? (int)$categoria : null;
        $min = ($min !== '' && is_numeric($min)) ? (float)$min : null;
        $max = ($max !== '' && is_numeric($max)) ? (float)$max : null;

        if ($min !== null && $max !== null && $min > $max) {
            $temporal = $min;
            $min = $max;
            $max = $temporal;
        }

        $products = $model->obtenerFiltrados($categoria, $min, $max);

        $categoriaActual = $categoria ?? '';
        $precioMinimoActual = $min ?? '';
        $precioMaximoActual = $max ?? '';

        require __DIR__ . '/../views/products/catalogo.php';
    }

    // Muestra el formulario de edición de un producto
    public function mostrarEditarProducto() {

        session_start();

        // SOLO ADMINISTRADORES
        if (!isset($_SESSION['user']) ||
            $_SESSION['user']['id_rol'] != 1) {

            die("Acceso denegado");
        }

        // COMPROBAR QUE LLEGA EL ID
        if (!isset($_GET['id'])) {
            die("ID no recibido");
        }

        $id = $_GET['id'];

        $model = new Product();

        $product = $model->obtenerPorId($id);

        if (!$product) {
            die("Producto no encontrado");
        }

        require __DIR__ . '/../views/products/edit.php';
    }

    // Actualiza los datos de un producto existente
    public function actualizarProducto() {

        session_start();

        // SOLO ADMINISTRADORES
        if (!isset($_SESSION['user']) ||
            $_SESSION['user']['id_rol'] != 1) {

            die("Acceso denegado");
        }

        $model = new Product();

        $id = $_POST['id'];
        $nombre = $_POST['nombre_producto'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];
        $descripcion = $_POST['descripcion'];

        // ACTUALIZAR IMAGEN SI SE SUBE UNA NUEVA
        if (!empty($_FILES['imagen']['name'])) {

            $imagenNombre =
                time() . "_" . $_FILES['imagen']['name'];

            $rutaTemp = $_FILES['imagen']['tmp_name'];

            $rutaDestino =
                __DIR__ . '/../../public/img/' . $imagenNombre;

            move_uploaded_file($rutaTemp, $rutaDestino);

        } else {

            $imagenNombre = $_POST['imagen_actual'];
        }

        $model->actualizarProducto(
            $id,
            $nombre,
            $precio,
            $stock,
            $descripcion,
            $imagenNombre
        );

        header("Location: index.php?action=catalogo");

        exit;
    }

    // Elimina un producto de la base de datos
    public function eliminarProducto() {

        session_start();

        // SOLO ADMINISTRADORES
        if (!isset($_SESSION['user']) ||
            $_SESSION['user']['id_rol'] != 1) {

            die("Acceso denegado");
        }

        // COMPROBAR QUE LLEGA EL ID
        if (!isset($_GET['id'])) {
            die("ID no recibido");
        }

        $id = $_GET['id'];

        $model = new Product();

        $model->eliminarProducto($id);

        header("Location: index.php?action=products");

        exit;
    }

    // Muestra la vista de pago del carrito
    public function mostrarPago() {

        session_start();

        if (!isset($_SESSION['user'])) {

            header("Location: index.php?action=login");

            exit;
        }

        require __DIR__ . '/../views/products/checkout.php';
    }

    // Procesa el pago y crea el pedido
    public function procesarPago() {

        session_start();

        $card = $_POST['cardNumber'];
        $cvv = $_POST['cvv'];
        $expiry = $_POST['expiry'];

        // LIMPIAR ESPACIOS DE LA TARJETA
        $card = str_replace(' ', '', $card);

        // VALIDAR TARJETA
        if (!preg_match('/^[0-9]{16}$/', $card)) {

            $_SESSION['error'] =
                "Número de tarjeta inválido";

            header("Location: index.php?action=checkout");

            exit;
        }

        // VALIDAR CVV
        if (!preg_match('/^[0-9]{3}$/', $cvv)) {

            $_SESSION['error'] =
                "CVV inválido";

            header("Location: index.php?action=checkout");

            exit;
        }

        // VALIDAR FECHA DE CADUCIDAD
        if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expiry)) {

            $_SESSION['error'] =
                "Fecha inválida";

            header("Location: index.php?action=checkout");

            exit;
        }

        // COMPROBAR USUARIO LOGUEADO
        if (!isset($_SESSION['user'])) {
            die("Debes iniciar sesión");
        }

        // COMPROBAR QUE EL CARRITO NO ESTÁ VACÍO
        if (empty($_SESSION['cart'])) {
            die("El carrito está vacío");
        }

        $id_usuario = $_SESSION['user']['id_usuario'];

        $total = $_SESSION['total'];

        $model = new Product();

        // CREAR PEDIDO
        $id_pedido =
            $model->crearPedido($id_usuario, $total);

        // GUARDAR DETALLES DEL PEDIDO
        foreach ($_SESSION['cart'] as $item) {

            $cantidad = $item['cantidad'];

            $precio = $item['precio'];

            $subtotal = $cantidad * $precio;

            $model->crearDetallePedido(
                $cantidad,
                $precio,
                $subtotal,
                $id_pedido,
                $item['id']
            );
        }

        // VACIAR CARRITO
        unset($_SESSION['cart']);
        unset($_SESSION['total']);

        // MENSAJE DE COMPRA COMPLETADA
        $_SESSION['success'] =
            "Compra realizada correctamente";

        // REDIRECCIÓN FINAL
        header("Location: index.php?action=products");

        exit;
    }
}