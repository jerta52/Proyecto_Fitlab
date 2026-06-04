<?php
require_once __DIR__ . '/../helpers/AuthHelper.php';
require_once __DIR__ . '/../models/AdminModel.php';
require_once __DIR__ . '/../models/Order.php';

class AdminController
{
    // Muestra el panel principal del administrador
    public function dashboard()
    {
        AuthHelper::requireAdmin();
        require __DIR__ . '/../views/admin/dashboard.php';
    }

    // Lista los registros de la entidad seleccionada en el panel
    public function listar()
    {
        AuthHelper::requireAdmin();

        $entity = $_GET['entity'] ?? '';
        $model = new AdminModel();
        $cfg = $model->getConfig($entity);
        $rows = $model->listar($entity);

        require __DIR__ . '/../views/admin/list.php';
    }

    // Muestra el formulario para crear un nuevo registro
    public function crear()
    {
        AuthHelper::requireAdmin();

        $entity = $_GET['entity'] ?? '';
        $model = new AdminModel();
        $cfg = $model->getConfig($entity);

        // La información general es única; no se crean varios registros.
        if ($entity === 'informacion') {
            $rows = $model->listar('informacion');

            if (!empty($rows)) {
                header('Location: index.php?action=adminEdit&entity=informacion&id=' . (int) $rows[0]['id_info']);
                exit;
            }

            $_SESSION['error'] = 'No existe información general inicial. Importa el SQL actualizado.';
            header('Location: index.php?action=adminList&entity=informacion');
            exit;
        }

        $row = null;
        require __DIR__ . '/../views/admin/form.php';
    }

    // Guarda un nuevo registro en la base de datos
    public function guardar()
    {
        AuthHelper::requireAdmin();

        try {
            $entity = $_POST['entity'] ?? '';
            $model = new AdminModel();

            $model->crear($entity, $_POST, $_FILES);
            $_SESSION['success'] = 'Registro creado correctamente.';

            header('Location: index.php?action=adminList&entity=' . urlencode($entity));
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: index.php?action=adminDashboard');
        }

        exit;
    }

    // Muestra el formulario de edición de un registro existente
    public function editar()
    {
        AuthHelper::requireAdmin();

        $entity = $_GET['entity'] ?? '';
        $id = $_GET['id'] ?? 0;

        $model = new AdminModel();
        $cfg = $model->getConfig($entity);
        $row = $model->obtener($entity, $id);

        if (!$row) {
            die('Registro no encontrado');
        }

        require __DIR__ . '/../views/admin/form.php';
    }

    // Actualiza un registro existente
    public function actualizar()
    {
        AuthHelper::requireAdmin();

        try {
            $entity = $_POST['entity'] ?? '';
            $id = $_POST['id'] ?? 0;

            $model = new AdminModel();
            $model->actualizar($entity, $id, $_POST, $_FILES);

            $_SESSION['success'] = 'Registro actualizado correctamente.';
            header('Location: index.php?action=adminList&entity=' . urlencode($entity));
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: index.php?action=adminDashboard');
        }

        exit;
    }

    // Elimina un registro de la entidad indicada
    public function eliminar()
    {
        AuthHelper::requireAdmin();

        $entity = $_GET['entity'] ?? '';
        $id = $_GET['id'] ?? 0;

        try {
            $model = new AdminModel();
            $model->eliminar($entity, $id);

            $_SESSION['success'] = 'Registro eliminado correctamente.';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: index.php?action=adminList&entity=' . urlencode($entity));
        exit;
    }

    // Aplica uno de los diseños visuales preestablecidos
    public function seleccionarDiseno()
    {
        AuthHelper::requireAdmin();

        try {
            $id = filter_input(INPUT_POST, 'id_config', FILTER_VALIDATE_INT);

            if (!$id) {
                throw new Exception('Diseño no válido');
            }

            $model = new AdminModel();
            $model->seleccionarDiseno($id);

            $_SESSION['success'] = 'Diseño visual aplicado correctamente.';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: index.php?action=adminList&entity=apariencia');
        exit;
    }

    // Lista todos los pedidos para que el administrador pueda cambiar su estado
    public function pedidos()
    {
        AuthHelper::requireAdmin();

        $model = new Order();
        $pedidos = $model->listarTodos();

        require __DIR__ . '/../views/admin/pedidos.php';
    }

    // Actualiza el estado de seguimiento de un pedido
    public function actualizarEstadoPedido()
    {
        AuthHelper::requireAdmin();

        $model = new Order();
        $model->actualizarEstado($_POST['id_pedido'], $_POST['estado']);

        $_SESSION['success'] = 'Estado del pedido actualizado.';
        header('Location: index.php?action=adminPedidos');
        exit;
    }
}
