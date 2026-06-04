<?php
require_once __DIR__ . '/../helpers/AuthHelper.php';
require_once __DIR__ . '/../models/Order.php';

class OrderController
{
    // Muestra los pedidos realizados por el cliente registrado
    public function misPedidos()
    {
        AuthHelper::requireCliente();

        $model = new Order();
        $pedidos = $model->listarPorUsuario($_SESSION['user']['id_usuario']);

        require __DIR__ . '/../views/orders/mis_pedidos.php';
    }

    // Muestra el detalle de un pedido concreto del cliente
    public function detalle()
    {
        AuthHelper::requireCliente();

        $id = $_GET['id'] ?? 0;
        $model = new Order();
        $pedido = $model->obtenerPedidoUsuario($id, $_SESSION['user']['id_usuario']);

        if (!$pedido) {
            die('Pedido no encontrado');
        }

        $detalles = $model->obtenerDetalle($id);

        require __DIR__ . '/../views/orders/detalle.php';
    }
}
