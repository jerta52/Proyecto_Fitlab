<?php
require_once dirname(__DIR__, 2) . '/config/database.php';

class Calculadora
{
    private $db;

    // Inicializa la conexión con la base de datos
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->conectar();
    }

    // GUARDAR CALORÍAS
    // Guarda en la base de datos el cálculo de calorías del usuario
    public function guardarCalorias($edad, $sexo, $peso, $altura, $actividad, $objetivo, $resultado, $id_usuario)
    {
        $query = "INSERT INTO calculo_calorias
                  (edad, sexo, peso, altura, actividad, objetivo, calorias_resultado, fecha, id_usuario)
                  VALUES
                  (:edad, :sexo, :peso, :altura, :actividad, :objetivo, :resultado, NOW(), :usuario)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':edad' => $edad,
            ':sexo' => $sexo,
            ':peso' => $peso,
            ':altura' => $altura,
            ':actividad' => $actividad,
            ':objetivo' => $objetivo,
            ':resultado' => $resultado,
            ':usuario' => $id_usuario
        ]);
    }

    // GUARDAR IMC
    // Guarda en la base de datos el cálculo del IMC del usuario
    public function guardarImc($peso, $altura, $resultado, $id_usuario)
    {
        $query = "INSERT INTO calculo_imc
                  (peso, altura, resultado_imc, fecha, id_usuario)
                  VALUES
                  (:peso, :altura, :resultado, NOW(), :usuario)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':peso' => $peso,
            ':altura' => $altura,
            ':resultado' => $resultado,
            ':usuario' => $id_usuario
        ]);
    }

    // Obtiene el histórico de IMC del usuario para mostrar tabla y gráfica
    public function historialImc($id_usuario)
    {
        $query = "SELECT peso, altura, resultado_imc, fecha
                  FROM calculo_imc
                  WHERE id_usuario = :usuario
                  ORDER BY fecha ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute([':usuario' => (int) $id_usuario]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene el histórico de calorías del usuario para mostrar tabla y gráfica
    public function historialCalorias($id_usuario)
    {
        $query = "SELECT edad, sexo, peso, altura, actividad, objetivo, calorias_resultado, fecha
                  FROM calculo_calorias
                  WHERE id_usuario = :usuario
                  ORDER BY fecha ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute([':usuario' => (int) $id_usuario]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
