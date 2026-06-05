<?php
require_once __DIR__ . '/../models/User.php';

class AuthController
{
    // Muestra el formulario de inicio de sesión
    public function mostrarLogin()
    {
        require __DIR__ . '/../views/auth/login.php';
    }

    // Muestra el formulario de registro
    public function mostrarRegistro()
    {
        require __DIR__ . '/../views/auth/register.php';
    }

    // Registra un usuario cliente en la aplicación
    public function registrarUsuario()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $apellidos = trim($_POST['apellidos'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($nombre === '' || $apellidos === '' || $email === '' || $password === '' || $confirmPassword === '') {
            $_SESSION['error'] = 'Completa todos los campos obligatorios.';
            header('Location: index.php?action=register');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'El correo electrónico no es válido.';
            header('Location: index.php?action=register');
            exit;
        }

        if (strlen($password) < 8) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 8 caracteres.';
            header('Location: index.php?action=register');
            exit;
        }

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Las contraseñas no coinciden.';
            header('Location: index.php?action=register');
            exit;
        }

        $model = new User();

        if ($model->buscarPorEmail($email)) {
            $_SESSION['error'] = 'Ya existe un usuario con ese correo electrónico.';
            header('Location: index.php?action=register');
            exit;
        }

        $model->crearUsuario($nombre, $apellidos, $email, password_hash($password, PASSWORD_DEFAULT));
        $_SESSION['success'] = 'Cuenta creada correctamente. Ya puedes iniciar sesión.';

        header('Location: index.php?action=login');
        exit;
    }

    // Inicia sesión y redirige según el rol del usuario
    public function iniciarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $_SESSION['error'] = 'Introduce correo electrónico y contraseña.';
            header('Location: index.php?action=login');
            exit;
        }

        $model = new User();
        $user = $model->buscarPorEmail($email);

        if (!$user || !password_verify($password, $user['contrasena'])) {
            $_SESSION['error'] = 'Correo electrónico o contraseña incorrectos.';
            header('Location: index.php?action=login');
            exit;
        }

        $_SESSION['user'] = $user;

        // El administrador entra solo a su panel, no a tienda, carrito ni calculadoras.
        if ((int) $user['id_rol'] === 1) {
            header('Location: index.php?action=adminDashboard');
            exit;
        }

        header('Location: index.php?action=products');
        exit;
    }

    // Cierra la sesión actual
    public function cerrarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();
        header('Location: index.php?action=home');
        exit;
    }

}
