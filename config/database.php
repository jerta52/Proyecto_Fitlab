<?php

class Database {

    private $host = "sql102.infinityfree.com";
    private $db_name = "if0_41927992_fitlab";
    private $username = "if0_41927992";
    private $password = "wLNxAMpoK0";
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