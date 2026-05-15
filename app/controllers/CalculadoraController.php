<?php

require_once __DIR__ . '/../models/Calculadora.php';

class CalculadoraController {

    // CALORÍAS

    // Calcula las calorías necesarias según los datos del usuario
    // y guarda el resultado en la base de datos
    public function guardarCalorias() {

        session_start();

        if (!isset($_SESSION['user'])) {
            die("Debes iniciar sesión");
        }

        $edad = $_POST['edad'];
        $sexo = $_POST['sexo'];
        $peso = $_POST['peso'];
        $altura = $_POST['altura'];
        $actividad = $_POST['actividad'];
        $objetivo = $_POST['objetivo'];

        // FÓRMULA BASE
        if ($sexo == 'Hombre') {

            $calorias =
                (10 * $peso) +
                (6.25 * $altura) -
                (5 * $edad) + 5;

        } else {

            $calorias =
                (10 * $peso) +
                (6.25 * $altura) -
                (5 * $edad) - 161;
        }

        // NIVEL DE ACTIVIDAD
        switch ($actividad) {

            case 'Ligera':
                $calorias *= 1.2;
                break;

            case 'Moderada':
                $calorias *= 1.55;
                break;

            case 'Alta':
                $calorias *= 1.8;
                break;
        }

        // OBJETIVO DEL USUARIO
        switch ($objetivo) {

            case 'Déficit suave':
                $calorias -= 300;
                break;

            case 'Déficit moderado':
                $calorias -= 500;
                break;

            case 'Volumen':
                $calorias += 300;
                break;
        }

        $resultado = round($calorias);

        $_SESSION['resultado_calorias'] = $resultado;

        $model = new Calculadora();

        $model->guardarCalorias(
            $edad,
            $sexo,
            $peso,
            $altura,
            $actividad,
            $objetivo,
            $resultado,
            $_SESSION['user']['id_usuario']
        );

        header("Location: index.php?action=calculadoras&focus=calorias");

        exit;
    }

    // IMC

    // Calcula el índice de masa corporal del usuario
    // y guarda el resultado en la base de datos
    public function guardarImc() {

        session_start();

        if (!isset($_SESSION['user'])) {
            die("Debes iniciar sesión");
        }

        $peso = $_POST['peso'];

        $altura = $_POST['altura'] / 100;

        $resultado =
            $peso / ($altura * $altura);

        $resultado = round($resultado, 1);

        // CLASIFICACIÓN DEL IMC
        if ($resultado < 18.5) {

            $estado = "Bajo peso";

        } elseif ($resultado < 25) {

            $estado = "Peso normal";

        } elseif ($resultado < 30) {

            $estado = "Sobrepeso";

        } else {

            $estado = "Obesidad";
        }

        $_SESSION['resultado_imc'] =
            $resultado . " - " . $estado;

        $model = new Calculadora();

        $model->guardarImc(
            $peso,
            $altura,
            $resultado,
            $_SESSION['user']['id_usuario']
        );

        header("Location: index.php?action=calculadoras&focus=imc");

        exit;
    }
}