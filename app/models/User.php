<?php

require_once dirname(__DIR__, 2) . '/config/database.php';

class User {

    private $db;

    // Inicializa la conexión con la base de datos
    public function __construct() {

        $database = new Database();

        $this->db = $database->conectar();
    }

    // REGISTRO

    // Registra un nuevo usuario en la base de datos
    public function registrar($data) {

        $query = "INSERT INTO usuario
                  (
                    nombre,
                    apellidos,
                    email,
                    contrasena,
                    fecha_registro,
                    id_rol
                  )
                  VALUES
                  (
                    :nombre,
                    :apellidos,
                    :email,
                    :contrasena,
                    NOW(),
                    2
                  )";

        $stmt = $this->db->prepare($query);

        // ENCRIPTAR CONTRASEÑA
        $hashedPassword =
            password_hash($data['password'], PASSWORD_DEFAULT);

        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':apellidos' => $data['apellidos'],
            ':email' => $data['email'],
            ':contrasena' => $hashedPassword
        ]);
    }

    // LOGIN

    // Comprueba si el usuario existe y verifica la contraseña
    public function comprobarInicioSesion($email, $password) {

        $query = "SELECT * FROM usuario
                  WHERE email = :email";

        $stmt = $this->db->prepare($query);

        $stmt->execute([
            ':email' => $email
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // VERIFICAR CONTRASEÑA
        if (
            $user &&
            password_verify($password, $user['contrasena'])
        ) {

            return $user;
        }

        return false;
    }
}