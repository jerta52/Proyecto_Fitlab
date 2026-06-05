<?php
require_once dirname(__DIR__, 2) . '/config/database.php';

class Order
{
    private $db;

    // Inicializa la conexión con la base de datos
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->conectar();
    }

    // Obtiene los pedidos de un usuario concreto
    public function listarPorUsuario($id_usuario)
    {
        $query = "SELECT *
                  FROM pedido
                  WHERE id_usuario = :id
                  ORDER BY fecha_pedido DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => (int) $id_usuario]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene un pedido comprobando que pertenece al usuario conectado
    public function obtenerPedidoUsuario($id_pedido, $id_usuario)
    {
        $query = "SELECT *
                  FROM pedido
                  WHERE id_pedido = :pedido
                  AND id_usuario = :usuario";

        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':pedido' => (int) $id_pedido,
            ':usuario' => (int) $id_usuario
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtiene un pedido con los datos del cliente para el panel de administración
    public function obtenerPedido($id_pedido)
    {
        $query = "SELECT p.*, u.nombre, u.apellidos, u.email
                  FROM pedido p
                  INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                  WHERE p.id_pedido = :pedido";

        $stmt = $this->db->prepare($query);
        $stmt->execute([':pedido' => (int) $id_pedido]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Obtiene las líneas o productos de un pedido
    public function obtenerDetalle($id_pedido)
    {
        $query = "SELECT dp.*, pr.nombre_producto
                  FROM detalle_pedido dp
                  INNER JOIN producto pr ON dp.id_producto = pr.id_producto
                  WHERE dp.id_pedido = :pedido";

        $stmt = $this->db->prepare($query);
        $stmt->execute([':pedido' => (int) $id_pedido]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lista todos los pedidos para el administrador
    public function listarTodos()
    {
        $query = "SELECT p.*, u.nombre, u.apellidos, u.email
                  FROM pedido p
                  INNER JOIN usuario u ON p.id_usuario = u.id_usuario
                  ORDER BY p.fecha_pedido DESC";

        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Actualiza el estado de seguimiento de un pedido
    public function actualizarEstado($id_pedido, $estado)
    {
        $permitidos = ['pendiente', 'pagado', 'enviado', 'entregado', 'cancelado'];

        if (!in_array($estado, $permitidos, true)) {
            throw new Exception('Estado no válido');
        }

        $id_pedido = (int) $id_pedido;

        try {
            $this->db->beginTransaction();

            // Obtener el estado actual para saber si hay que devolver o volver a restar stock.
            $stmtPedido = $this->db->prepare('SELECT estado FROM pedido WHERE id_pedido = :pedido FOR UPDATE');
            $stmtPedido->execute([':pedido' => $id_pedido]);
            $pedido = $stmtPedido->fetch(PDO::FETCH_ASSOC);

            if (!$pedido) {
                throw new Exception('Pedido no encontrado');
            }

            $estadoAnterior = $pedido['estado'];

            // Si el pedido pasa a cancelado, se devuelven las unidades al stock.
            if ($estado === 'cancelado' && $estadoAnterior !== 'cancelado') {
                $this->restaurarStockPedido($id_pedido);
            }

            // Si un pedido cancelado vuelve a un estado activo, se vuelve a descontar el stock.
            if ($estado !== 'cancelado' && $estadoAnterior === 'cancelado') {
                $this->descontarStockPedido($id_pedido);
            }

            $query = "UPDATE pedido
                      SET estado = :estado
                      WHERE id_pedido = :pedido";

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':estado' => $estado,
                ':pedido' => $id_pedido
            ]);

            $this->db->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

    // Devuelve al producto las unidades de un pedido cancelado
    private function restaurarStockPedido($id_pedido)
    {
        $detalles = $this->obtenerDetalle($id_pedido);

        foreach ($detalles as $detalle) {
            $query = "UPDATE producto
                      SET stock = stock + :cantidad
                      WHERE id_producto = :producto";

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':cantidad' => (int) $detalle['cantidad'],
                ':producto' => (int) $detalle['id_producto']
            ]);
        }
    }

    // Vuelve a descontar stock si un pedido cancelado se reactiva
    private function descontarStockPedido($id_pedido)
    {
        $detalles = $this->obtenerDetalle($id_pedido);

        foreach ($detalles as $detalle) {
            $query = "UPDATE producto
                      SET stock = stock - :cantidad
                      WHERE id_producto = :producto
                      AND stock >= :cantidad";

            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':cantidad' => (int) $detalle['cantidad'],
                ':producto' => (int) $detalle['id_producto']
            ]);

            if ($stmt->rowCount() === 0) {
                throw new Exception('No hay stock suficiente para reactivar el pedido.');
            }
        }
    }
}
