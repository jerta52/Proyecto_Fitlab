<?php
require_once dirname(__DIR__, 2) . '/config/database.php';

class User
{
    private $db;

    // Inicializa la conexión con la base de datos
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->conectar();
    }

    // Registra un nuevo usuario cliente en la base de datos
    public function registrar($data)
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        return $this->crearUsuario(
            $data['nombre'],
            $data['apellidos'],
            $data['email'],
            $hashedPassword
        );
    }

    // Crea un usuario cliente con contraseña ya cifrada
    public function crearUsuario($nombre, $apellidos, $email, $passwordHash)
    {
        $query = "INSERT INTO usuario
                  (nombre, apellidos, email, contrasena, fecha_registro, id_rol)
                  VALUES
                  (:nombre, :apellidos, :email, :contrasena, NOW(), 2)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':nombre' => $nombre,
            ':apellidos' => $apellidos,
            ':email' => $email,
            ':contrasena' => $passwordHash
        ]);
    }

    // Busca un usuario por correo electrónico
    public function buscarPorEmail($email)
    {
        $stmt = $this->db->prepare('SELECT * FROM usuario WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Comprueba si el usuario existe y verifica la contraseña
    public function comprobarInicioSesion($email, $password)
    {
        $user = $this->buscarPorEmail($email);

        if ($user && password_verify($password, $user['contrasena'])) {
            return $user;
        }

        return false;
    }

}
