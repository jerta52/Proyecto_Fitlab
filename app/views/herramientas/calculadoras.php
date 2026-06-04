<?php require __DIR__ . '/../layouts/header.php'; ?>

<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$focus = $_GET['focus'] ?? 'calorias';

?>

<section class="seccion-calculadoras py-5">

    <div class="container">

        <!-- BOTONES -->
        <div class="pestanas-calculadora text-center mb-5">

            <a href="index.php?action=calculadoras&focus=calorias"
               class="pestana-calculadora <?php echo $focus == 'calorias' ? 'pestana-activa' : ''; ?>">

                CALCULADORA DE CALORÍAS

            </a>

            <a href="index.php?action=calculadoras&focus=imc"
               class="pestana-calculadora <?php echo $focus == 'imc' ? 'pestana-activa' : ''; ?>">

                CALCULADORA DE IMC

            </a>

        </div>

        <div class="row justify-content-center g-4">


            <!-- CALORÍAS -->
            <div class="col-lg-5">

                <div class="tarjeta-calculadora <?php echo $focus == 'calorias' ? 'tarjeta-activa' : 'tarjeta-inactiva'; ?>">

                    <h3 class="titulo-calculadora">
                        CALCULADORA DE CALORÍAS
                    </h3>

                    <form action="index.php?action=guardarCalorias"
                          method="POST">

                        <!-- SEXO -->
                        <div class="mb-3">

                            <label>Sexo</label>

                            <select name="sexo"
                                    class="form-select">

                                <option value="Hombre">Hombre</option>
                                <option value="Mujer">Mujer</option>

                            </select>

                        </div>

                        <!-- EDAD + PESO -->
                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label>Edad</label>

                                <input type="number"
                                       name="edad"
                                       min="10" max="100"
                                       class="form-control"
                                       required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Peso (kg)</label>

                                <input type="number"
                                       step="0.1"
                                       min="1" max="300"
                                       name="peso"
                                       class="form-control"
                                       required>

                            </div>

                        </div>

                        <!-- ALTURA -->
                        <div class="mb-3">

                            <label>Altura (cm)</label>

                            <input type="number"
                                   step="0.1"
                                   min="50" max="250"
                                   name="altura"
                                   class="form-control"
                                   required>

                        </div>

                        <!-- ACTIVIDAD -->
                        <div class="mb-3">

                            <label>Actividad</label>

                            <select name="actividad"
                                    class="form-select">

                                <option value="Ligera">Ligera</option>
                                <option value="Moderada">Moderada</option>
                                <option value="Alta">Alta</option>

                            </select>

                        </div>

                        <!-- OBJETIVO -->
                        <div class="mb-4">

                            <label>Objetivo</label>

                            <select name="objetivo"
                                    class="form-select">

                                <option value="Mantener peso">
                                    Mantener peso
                                </option>

                                <option value="Déficit suave">
                                    Déficit suave
                                </option>

                                <option value="Déficit moderado">
                                    Déficit moderado
                                </option>

                                <option value="Volumen">
                                    Volumen
                                </option>

                            </select>

                        </div>

                        <button type="submit"
                                class="btn btn-primary w-100">

                            CALCULAR CALORÍAS

                        </button>

                    </form>

                    <!-- RESULTADO -->
                    <?php if (isset($_SESSION['resultado_calorias'])): ?>

                        <div class="resultado-calculo mt-4">
                            <h3>TU RESULTADO: </h3>

                            <?php
                                echo $_SESSION['resultado_calorias'] . " CAL";
                                unset($_SESSION['resultado_calorias']);
                            ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

            <!-- IMC -->
            <div class="col-lg-5">

                <div class="tarjeta-calculadora <?php echo $focus == 'imc' ? 'tarjeta-activa' : 'tarjeta-inactiva'; ?>">

                    <h3 class="titulo-calculadora">
                        CALCULADORA IMC
                    </h3>

                    <form action="index.php?action=guardarImc"
                          method="POST">

                        <div class="mb-3">

                            <label>Peso (kg)</label>

                            <input type="number"
                                   step="0.1"
                                   min="1" max="300"
                                   name="peso"
                                   class="form-control"
                                   required>

                        </div>

                        <div class="mb-4">

                            <label>Altura (cm)</label>

                            <input type="number"
                                   step="0.1"
                                   min="50" max="250"
                                   name="altura"
                                   class="form-control"
                                   required>

                        </div>

                        <button type="submit"
                                class="btn btn-primary w-100">

                            CALCULAR IMC

                        </button>

                    </form>

                    <!-- RESULTADO -->
                    <?php if (isset($_SESSION['resultado_imc'])): ?>

                        <div class="resultado-calculo mt-4">
                            <h3>TU RESULTADO: </h3>
                            <?php
                                echo $_SESSION['resultado_imc'];
                                unset($_SESSION['resultado_imc']);
                            ?>

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>


        <!-- ================================================= -->
        <!-- HISTÓRICO Y GRÁFICAS -->
        <!-- ================================================= -->
        <div class="row mt-5 g-4">
            <div class="col-lg-6">
                <div class="bg-dark p-4 rounded text-white">
                    <h3>Histórico IMC</h3>

                    <table class="table table-dark table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Peso</th>
                                <th>Altura</th>
                                <th>IMC</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach (($historialImc ?? []) as $h): ?>
                                <tr>
                                    <td><?= htmlspecialchars($h['fecha']) ?></td>
                                    <td><?= htmlspecialchars($h['peso']) ?></td>
                                    <td><?= htmlspecialchars($h['altura']) ?></td>
                                    <td><?= htmlspecialchars($h['resultado_imc']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <canvas id="graficaImc" height="150"></canvas>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="bg-dark p-4 rounded text-white">
                    <h3>Histórico calorías</h3>

                    <table class="table table-dark table-striped">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Objetivo</th>
                                <th>Calorías</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach (($historialCalorias ?? []) as $h): ?>
                                <tr>
                                    <td><?= htmlspecialchars($h['fecha']) ?></td>
                                    <td><?= htmlspecialchars($h['objetivo']) ?></td>
                                    <td><?= htmlspecialchars($h['calorias_resultado']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <canvas id="graficaCalorias" height="150"></canvas>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const imcData = <?= json_encode($historialImc ?? []) ?>;
            const calData = <?= json_encode($historialCalorias ?? []) ?>;

            new Chart(document.getElementById('graficaImc'), {
                type: 'line',
                data: {
                    labels: imcData.map(item => item.fecha),
                    datasets: [{
                        label: 'IMC',
                        data: imcData.map(item => parseFloat(item.resultado_imc))
                    }]
                }
            });

            new Chart(document.getElementById('graficaCalorias'), {
                type: 'line',
                data: {
                    labels: calData.map(item => item.fecha),
                    datasets: [{
                        label: 'Calorías',
                        data: calData.map(item => parseInt(item.calorias_resultado))
                    }]
                }
            });
        </script>
    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>