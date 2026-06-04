<?php
class AuthHelper
{
    // Inicia la sesión si todavía no está iniciada
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Devuelve los datos del usuario autenticado
    public static function user()
    {
        self::start();
        return $_SESSION['user'] ?? null;
    }

    // Comprueba si el usuario actual es administrador
    public static function isAdmin()
    {
        self::start();
        return isset($_SESSION['user']) && (int) $_SESSION['user']['id_rol'] === 1;
    }

    // Comprueba si el usuario actual es cliente registrado
    public static function isCliente()
    {
        self::start();
        return isset($_SESSION['user']) && (int) $_SESSION['user']['id_rol'] === 2;
    }

    // Obliga a iniciar sesión para entrar en zonas privadas
    public static function requireLogin()
    {
        self::start();

        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = 'Debes iniciar sesión para acceder a esta zona.';
            header('Location: index.php?action=login');
            exit;
        }
    }

    // Protege las funciones propias del cliente registrado
    public static function requireCliente()
    {
        self::requireLogin();

        if ((int) $_SESSION['user']['id_rol'] !== 2) {
            $_SESSION['error'] = 'Esta función es solo para clientes registrados.';
            header('Location: index.php?action=adminDashboard');
            exit;
        }
    }

    // Protege las rutas exclusivas del administrador
    public static function requireAdmin()
    {
        self::requireLogin();

        if ((int) $_SESSION['user']['id_rol'] !== 1) {
            http_response_code(403);
            die('Acceso denegado. Esta zona es solo para administradores.');
        }
    }
}
