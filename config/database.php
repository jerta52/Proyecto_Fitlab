<?php

class Database {

    private $host = "localhost";
    private $db_name = "fitlab";
    private $username = "root";
    private $password = "";
    private $conn;

    // Establece la conexión con la base de datos
    public function conectar() {

        $this->conn = null;

        try {

            $this->conn = new PDO(
                "mysql:host=" .
                $this->host .
                ";dbname=" .
                $this->db_name .
                ";charset=utf8",

                $this->username,
                $this->password
            );

            // ACTIVAR MANEJO DE ERRORES EN PDO
            $this->conn->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );

        } catch (PDOException $e) {

            die(
                "Error de conexión: " .
                $e->getMessage()
            );
        }

        return $this->conn;
    }
}