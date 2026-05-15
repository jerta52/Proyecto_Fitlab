<?php
require_once '../app/models/User.php';

class AuthController {

    // Muestra la vista del formulario de inicio de sesión
    public function mostrarLogin() {
        require __DIR__ . '/../views/auth/login.php';
    }

    // Muestra la vista del formulario de registro
    public function mostrarRegistro() {
        require __DIR__ . '/../views/auth/register.php';
    }

    // Gestiona el inicio de sesión del usuario
    public function iniciarSesion() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = new User();
            $user = $userModel->comprobarInicioSesion($email, $password);

            if ($user) {

                $_SESSION['user'] = $user;

                $_SESSION['success'] =
                    "Bienvenido " . $user['nombre'];

                header("Location: index.php?action=home");

            } else {

                $_SESSION['error'] =
                    "Correo o contraseña incorrectos";

                header("Location: index.php?action=login");
            }

            exit;
        }
    }

    // Gestiona el registro de nuevos usuarios
    public function registrarUsuario() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            try {

                $userModel = new User();

                $userModel->registrar($_POST);

                $_SESSION['success'] =
                    "Cuenta creada correctamente";

                header("Location: index.php?action=login");

            } catch (PDOException $e) {

                $_SESSION['error'] =
                    "Ese correo ya está registrado";

                header("Location: index.php?action=register");

            }

            exit;
        }
    }

    // Cierra la sesión del usuario y elimina los datos del carrito
    public function cerrarSesion() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // BORRAR SOLO USER
        unset($_SESSION['user']);

        // BORRAR CARRITO
        unset($_SESSION['cart']);

        unset($_SESSION['total']);

        // MENSAJE INFORMATIVO
        $_SESSION['info'] =
            "Sesión cerrada correctamente";

        header("Location: index.php?action=login");

        exit;
    }
}