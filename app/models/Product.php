<?php
require_once dirname(__DIR__, 2) . '/config/database.php';

class Product
{
    private $db;

    // Inicializa la conexión con la base de datos
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->conectar();
    }

    // Inicia una transacción para el proceso de compra
    public function beginTransaction()
    {
        if (!$this->db->inTransaction()) {
            $this->db->beginTransaction();
        }
    }

    // Confirma la transacción activa
    public function commit()
    {
        if ($this->db->inTransaction()) {
            $this->db->commit();
        }
    }

    // Cancela la transacción activa si ocurre un error
    public function rollBack()
    {
        if ($this->db->inTransaction()) {
            $this->db->rollBack();
        }
    }

    // Comprueba si una columna existe para mantener compatibilidad con bases antiguas
    private function columnaExiste($tabla, $columna)
    {
        try {
            $stmt = $this->db->prepare('SHOW COLUMNS FROM `' . $tabla . '` LIKE :columna');
            $stmt->execute([':columna' => $columna]);

            return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            return false;
        }
    }

    // Obtiene todos los productos de la base de datos
    public function obtenerTodos()
    {
        $query = "SELECT p.*, c.nombre_categoria
                  FROM producto p
                  LEFT JOIN categoria c ON p.id_categoria = c.id_categoria
                  ORDER BY p.id_producto DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene todas las categorías para los filtros de la tienda
    public function obtenerCategorias()
    {
        $stmt = $this->db->prepare('SELECT * FROM categoria ORDER BY nombre_categoria');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene productos filtrados por categoría
    public function obtenerPorCategoria($id_categoria)
    {
        $query = "SELECT *
                  FROM producto
                  WHERE id_categoria = :id
                  ORDER BY id_producto DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => (int) $id_categoria]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene un producto mediante su ID
    public function obtenerPorId($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM producto WHERE id_producto = :id');
        $stmt->execute([':id' => (int) $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Añade un producto al carrito del usuario
    public function agregarAlCarrito($id_usuario, $id_producto)
    {
        // BUSCAR CARRITO DEL USUARIO
        $stmt = $this->db->prepare('SELECT * FROM carrito WHERE id_usuario = :id LIMIT 1');
        $stmt->execute([':id' => (int) $id_usuario]);

        $carrito = $stmt->fetch(PDO::FETCH_ASSOC);

        // CREAR CARRITO SI NO EXISTE
        if (!$carrito) {
            $stmt = $this->db->prepare('INSERT INTO carrito (fecha_creacion, id_usuario) VALUES (NOW(), :id)');
            $stmt->execute([':id' => (int) $id_usuario]);

            $id_carrito = $this->db->lastInsertId();
        } else {
            $id_carrito = $carrito['id_carrito'];
        }

        // OBTENER PRODUCTO
        $product = $this->obtenerPorId($id_producto);

        if (!$product || (int) $product['stock'] <= 0) {
            return false;
        }

        // COMPROBAR SI EL PRODUCTO YA EXISTE EN EL CARRITO
        $query = "SELECT *
                  FROM detalle_carrito
                  WHERE id_carrito = :carrito
                  AND id_producto = :producto";

        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':carrito' => $id_carrito,
            ':producto' => (int) $id_producto
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
                      (cantidad, subtotal, id_carrito, id_producto)
                      VALUES
                      (1, :subtotal, :carrito, :producto)";
        }

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':subtotal' => $product['precio'],
            ':carrito' => $id_carrito,
            ':producto' => (int) $id_producto
        ]);
    }

    // Guarda un nuevo producto en la base de datos
    public function crearProducto($nombre, $precio, $stock, $descripcion, $imagen, $id_categoria)
    {
        $query = "INSERT INTO producto
                  (nombre_producto, precio, stock, descripcion, imagen, id_categoria)
                  VALUES
                  (:nombre, :precio, :stock, :descripcion, :imagen, :categoria)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':nombre' => $nombre,
            ':precio' => $precio,
            ':stock' => (int) $stock,
            ':descripcion' => $descripcion,
            ':imagen' => $imagen,
            ':categoria' => (int) $id_categoria
        ]);
    }

    // Obtiene productos aplicando filtros de categoría y precio
    public function obtenerFiltrados($categoria, $min, $max)
    {
        $condiciones = [];
        $params = [];

        // FILTRAR POR CATEGORÍA
        if ($categoria !== null) {
            $condiciones[] = 'id_categoria = :categoria';
            $params[':categoria'] = (int) $categoria;
        }

        // FILTRAR POR PRECIO MÍNIMO
        if ($min !== null) {
            $condiciones[] = 'precio >= :min';
            $params[':min'] = (float) $min;
        }

        // FILTRAR POR PRECIO MÁXIMO
        if ($max !== null) {
            $condiciones[] = 'precio <= :max';
            $params[':max'] = (float) $max;
        }

        $query = 'SELECT * FROM producto';

        if ($condiciones) {
            $query .= ' WHERE ' . implode(' AND ', $condiciones);
        }

        $query .= ' ORDER BY id_producto DESC';

        $stmt = $this->db->prepare($query);

        foreach ($params as $key => $value) {
            $type = $key === ':categoria' ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $type);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualiza los datos de un producto
    public function actualizarProducto($id, $nombre, $precio, $stock, $descripcion, $imagen, $id_categoria)
    {
        $query = "UPDATE producto
                  SET nombre_producto = :nombre,
                      precio = :precio,
                      stock = :stock,
                      descripcion = :descripcion,
                      imagen = :imagen,
                      id_categoria = :categoria
                  WHERE id_producto = :id";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':nombre' => $nombre,
            ':precio' => $precio,
            ':stock' => (int) $stock,
            ':descripcion' => $descripcion,
            ':imagen' => $imagen,
            ':categoria' => (int) $id_categoria,
            ':id' => (int) $id
        ]);
    }

    // Elimina un producto de la base de datos
    public function eliminarProducto($id)
    {
        $stmt = $this->db->prepare('DELETE FROM producto WHERE id_producto = :id');

        return $stmt->execute([':id' => (int) $id]);
    }

    // Crea un nuevo pedido en la base de datos
    public function crearPedido($id_usuario, $total, $metodo_pago = 'Tarjeta')
    {
        // Compatible con bases antiguas: si todavía no existe metodo_pago,
        // el pedido se crea igualmente y no provoca error 500.
        $campos = ['fecha_pedido', 'total', 'estado', 'id_usuario'];
        $valores = ['NOW()', ':total', '"pendiente"', ':usuario'];
        $params = [
            ':total' => $total,
            ':usuario' => (int) $id_usuario
        ];

        if ($this->columnaExiste('pedido', 'metodo_pago')) {
            $campos[] = 'metodo_pago';
            $valores[] = ':metodo';
            $params[':metodo'] = $metodo_pago;
        }

        $query = 'INSERT INTO pedido (' . implode(', ', $campos) . ') VALUES (' . implode(', ', $valores) . ')';
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        // DEVOLVER ID DEL PEDIDO CREADO
        return $this->db->lastInsertId();
    }

    // Obtiene un pedido por su ID
    public function obtenerPedidoPorId($id_pedido)
    {
        $stmt = $this->db->prepare('SELECT * FROM pedido WHERE id_pedido = :id');
        $stmt->execute([':id' => (int) $id_pedido]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Guarda los detalles de un pedido
    public function crearDetallePedido($cantidad, $precio_unitario, $subtotal, $id_pedido, $id_producto)
    {
        $query = "INSERT INTO detalle_pedido
                  (cantidad, precio_unitario, subtotal, id_pedido, id_producto)
                  VALUES
                  (:cantidad, :precio, :subtotal, :pedido, :producto)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':cantidad' => (int) $cantidad,
            ':precio' => $precio_unitario,
            ':subtotal' => $subtotal,
            ':pedido' => (int) $id_pedido,
            ':producto' => (int) $id_producto
        ]);
    }

    // Resta stock al confirmar una compra
    public function reducirStock($id_producto, $cantidad)
    {
        $query = "UPDATE producto
                  SET stock = stock - :cantidad
                  WHERE id_producto = :id
                  AND stock >= :cantidad";

        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':cantidad' => (int) $cantidad,
            ':id' => (int) $id_producto
        ]);

        return $stmt->rowCount() > 0;
    }

    // Obtiene los detalles del pedido junto al nombre del producto
    public function detallesPedidoConNombre($id_pedido)
    {
        $query = "SELECT dp.*, pr.nombre_producto
                  FROM detalle_pedido dp
                  INNER JOIN producto pr ON dp.id_producto = pr.id_producto
                  WHERE dp.id_pedido = :id";

        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => (int) $id_pedido]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
