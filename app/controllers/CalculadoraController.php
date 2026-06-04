<?php
require_once __DIR__ . '/../models/Calculadora.php';
require_once __DIR__ . '/../helpers/AuthHelper.php';

class CalculadoraController
{
    // Muestra las calculadoras y el histórico del cliente
    public function mostrarCalculadoras()
    {
        AuthHelper::requireCliente();

        $model = new Calculadora();
        $historialImc = $model->historialImc($_SESSION['user']['id_usuario']);
        $historialCalorias = $model->historialCalorias($_SESSION['user']['id_usuario']);

        require __DIR__ . '/../views/herramientas/calculadoras.php';
    }

    // CALORÍAS
    // Calcula las calorías necesarias según los datos del usuario y guarda el resultado
    public function guardarCalorias()
    {
        AuthHelper::requireCliente();

        $edad = filter_input(INPUT_POST, 'edad', FILTER_VALIDATE_INT);
        $sexo = $_POST['sexo'] ?? '';
        $peso = filter_input(INPUT_POST, 'peso', FILTER_VALIDATE_FLOAT);
        $altura = filter_input(INPUT_POST, 'altura', FILTER_VALIDATE_FLOAT);
        $actividad = $_POST['actividad'] ?? '';
        $objetivo = $_POST['objetivo'] ?? '';

        $sexos = ['Hombre', 'Mujer'];
        $actividades = ['Ligera', 'Moderada', 'Alta'];
        $objetivos = ['Mantener peso', 'Déficit suave', 'Déficit moderado', 'Volumen'];

        if (
            !$edad ||
            $edad < 10 ||
            $edad > 100 ||
            !in_array($sexo, $sexos, true) ||
            !$peso ||
            $peso <= 0 ||
            !$altura ||
            $altura <= 0 ||
            !in_array($actividad, $actividades, true) ||
            !in_array($objetivo, $objetivos, true)
        ) {
            $_SESSION['error'] = 'Datos de calorías no válidos.';
            header('Location: index.php?action=calculadoras&focus=calorias');
            exit;
        }

        // FÓRMULA BASE
        if ($sexo === 'Hombre') {
            $calorias = (10 * $peso) + (6.25 * $altura) - (5 * $edad) + 5;
        } else {
            $calorias = (10 * $peso) + (6.25 * $altura) - (5 * $edad) - 161;
        }

        // NIVEL DE ACTIVIDAD
        $factoresActividad = [
            'Ligera' => 1.2,
            'Moderada' => 1.55,
            'Alta' => 1.8
        ];

        $calorias *= $factoresActividad[$actividad];

        // OBJETIVO DEL USUARIO
        if ($objetivo === 'Déficit suave') {
            $calorias -= 300;
        } elseif ($objetivo === 'Déficit moderado') {
            $calorias -= 500;
        } elseif ($objetivo === 'Volumen') {
            $calorias += 300;
        }

        $resultado = max(0, round($calorias));
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

        header('Location: index.php?action=calculadoras&focus=calorias');
        exit;
    }

    // IMC
    // Calcula el índice de masa corporal del usuario y guarda el resultado
    public function guardarImc()
    {
        AuthHelper::requireCliente();

        $peso = filter_input(INPUT_POST, 'peso', FILTER_VALIDATE_FLOAT);
        $alturaCm = filter_input(INPUT_POST, 'altura', FILTER_VALIDATE_FLOAT);

        if (!$peso || $peso <= 0 || $peso > 300 || !$alturaCm || $alturaCm <= 0 || $alturaCm > 250) {
            $_SESSION['error'] = 'Datos de IMC no válidos.';
            header('Location: index.php?action=calculadoras&focus=imc');
            exit;
        }

        $altura = $alturaCm / 100;
        $resultado = round($peso / ($altura * $altura), 1);

        // CLASIFICACIÓN DEL IMC
        if ($resultado < 18.5) {
            $estado = 'Bajo peso';
        } elseif ($resultado < 25) {
            $estado = 'Peso normal';
        } elseif ($resultado < 30) {
            $estado = 'Sobrepeso';
        } else {
            $estado = 'Obesidad';
        }

        $_SESSION['resultado_imc'] = $resultado . ' - ' . $estado;

        $model = new Calculadora();
        $model->guardarImc($peso, $altura, $resultado, $_SESSION['user']['id_usuario']);

        header('Location: index.php?action=calculadoras&focus=imc');
        exit;
    }
}
