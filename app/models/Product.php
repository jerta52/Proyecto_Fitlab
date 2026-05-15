<?php

require_once dirname(__DIR__, 2) . '/config/database.php';

class Product {

    private $db;

    // Inicializa la conexión con la base de datos
    public function __construct() {

        $database = new Database();

        $this->db = $database->conectar();
    }

    // Obtiene todos los productos de la base de datos
    public function obtenerTodos() {

        $query = "SELECT * FROM producto";

        $stmt = $this->db->prepare($query);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene productos filtrados por categoría
    public function obtenerPorCategoria($id_categoria) {

        $query = "SELECT * FROM producto 
                  WHERE id_categoria = :id";

        $stmt = $this->db->prepare($query);

        $stmt->execute([
            ':id' => $id_categoria
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene un producto mediante su ID
    public function obtenerPorId($id) {

        $query = "SELECT * FROM producto 
                  WHERE id_producto = :id";

        $stmt = $this->db->prepare($query);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Añade un producto al carrito del usuario
    public function agregarAlCarrito($id_usuario, $id_producto) {

        // BUSCAR CARRITO DEL USUARIO
        $query = "SELECT * FROM carrito 
                  WHERE id_usuario = :id";

        $stmt = $this->db->prepare($query);

        $stmt->execute([
            ':id' => $id_usuario
        ]);

        $carrito = $stmt->fetch(PDO::FETCH_ASSOC);

        // CREAR CARRITO SI NO EXISTE
        if (!$carrito) {

            $query = "INSERT INTO carrito 
                      (fecha_creacion, id_usuario)
                      VALUES (NOW(), :id)";

            $stmt = $this->db->prepare($query);

            $stmt->execute([
                ':id' => $id_usuario
            ]);

            $id_carrito = $this->db->lastInsertId();

        } else {

            $id_carrito = $carrito['id_carrito'];
        }

        // OBTENER PRODUCTO
        $product = $this->obtenerPorId($id_producto);

        if (!$product) {
            return false;
        }

        $subtotal = $product['precio'];

        // COMPROBAR SI EL PRODUCTO YA EXISTE EN EL CARRITO
        $query = "SELECT * FROM detalle_carrito
                  WHERE id_carrito = :carrito
                  AND id_producto = :producto";

        $stmt = $this->db->prepare($query);

        $stmt->execute([
            ':carrito' => $id_carrito,
            ':producto' => $id_producto
        ]);

        $detalle = $stmt->fetch(PDO::FETCH_ASSOC);

        // INSERTAR O ACTUALIZAR PRODUCTO DEL CARRITO
        if ($detalle) {

            $query = "UPDATE detalle_carrito
                      SET cantidad = cantidad + 1,
                          subtotal = subtotal + :subtotal
                      WHERE id_carrito = :carrito
                      AND id_producto = :producto";

        } else {

            $query = "INSERT INTO detalle_carrito
                      (
                        cantidad,
                        subtotal,
                        id_carrito,
                        id_producto
                      )
                      VALUES
                      (
                        1,
                        :subtotal,
                        :carrito,
                        :producto
                      )";
        }

        $stmt = $this->db->prepare($query);

        $stmt->execute([
            ':subtotal' => $subtotal,
            ':carrito' => $id_carrito,
            ':producto' => $id_producto
        ]);

        return true;
    }

    // Guarda un nuevo producto en la base de datos
    public function crearProducto(
        $nombre,
        $precio,
        $stock,
        $descripcion,
        $imagen
    ) {

        $query = "INSERT INTO producto
                  (
                    nombre_producto,
                    precio,
                    stock,
                    descripcion,
                    imagen
                  )
                  VALUES
                  (
                    :nombre,
                    :precio,
                    :stock,
                    :descripcion,
                    :imagen
                  )";

        $stmt = $this->db->prepare($query);

        $stmt->execute([
            ':nombre' => $nombre,
            ':precio' => $precio,
            ':stock' => $stock,
            ':descripcion' => $descripcion,
            ':imagen' => $imagen
        ]);
    }

    // Obtiene productos aplicando filtros de categoría y precio
    public function obtenerFiltrados($categoria, $min, $max) {

        $condiciones = [];
        $params = [];

        if ($categoria !== null) {
            $condiciones[] = "id_categoria = :categoria";
            $params[':categoria'] = $categoria;
        }

        if ($min !== null) {
            $condiciones[] = "precio >= :min";
            $params[':min'] = $min;
        }

        if ($max !== null) {
            $condiciones[] = "precio <= :max";
            $params[':max'] = $max;
        }

        $query = "SELECT * FROM producto";

        if (!empty($condiciones)) {
            $query .= " WHERE " . implode(" AND ", $condiciones);
        }

        $query .= " ORDER BY id_producto DESC";

        $stmt = $this->db->prepare($query);

        foreach ($params as $clave => $valor) {
            if ($clave === ':categoria') {
                $stmt->bindValue($clave, $valor, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($clave, $valor);
            }
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualiza los datos de un producto
    public function actualizarProducto(
        $id,
        $nombre,
        $precio,
        $stock,
        $descripcion,
        $imagen
    ) {

        $query = "UPDATE producto
                  SET nombre_producto = :nombre,
                      precio = :precio,
                      stock = :stock,
                      descripcion = :descripcion,
                      imagen = :imagen
                  WHERE id_producto = :id";

        $stmt = $this->db->prepare($query);

        $stmt->execute([
            ':nombre' => $nombre,
            ':precio' => $precio,
            ':stock' => $stock,
            ':descripcion' => $descripcion,
            ':imagen' => $imagen,
            ':id' => $id
        ]);
    }

    // Elimina un producto. Las relaciones se borran con ON DELETE CASCADE
    public function eliminarProducto($id) {

        $query = "DELETE FROM producto
                  WHERE id_producto = :id";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    // Crea un nuevo pedido en la base de datos
    public function crearPedido($id_usuario, $total) {

        $query = "INSERT INTO pedido
                  (
                    fecha_pedido,
                    total,
                    estado,
                    id_usuario
                  )
                  VALUES
                  (
                    NOW(),
                    :total,
                    'Pendiente',
                    :usuario
                  )";

        $stmt = $this->db->prepare($query);

        $stmt->execute([
            ':total' => $total,
            ':usuario' => $id_usuario
        ]);

        // DEVOLVER ID DEL PEDIDO CREADO
        return $this->db->lastInsertId();
    }

    // Guarda los detalles de un pedido
    public function crearDetallePedido(
        $cantidad,
        $precio_unitario,
        $subtotal,
        $id_pedido,
        $id_producto
    ) {

        $query = "INSERT INTO detalle_pedido
                  (
                    cantidad,
                    precio_unitario,
                    subtotal,
                    id_pedido,
                    id_producto
                  )
                  VALUES
                  (
                    :cantidad,
                    :precio,
                    :subtotal,
                    :pedido,
                    :producto
                  )";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':cantidad' => $cantidad,
            ':precio' => $precio_unitario,
            ':subtotal' => $subtotal,
            ':pedido' => $id_pedido,
            ':producto' => $id_producto
        ]);
    }
}