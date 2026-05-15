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
                                       class="form-control"
                                       required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Peso (kg)</label>

                                <input type="number"
                                       step="0.1"
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
                                   name="peso"
                                   class="form-control"
                                   required>

                        </div>

                        <div class="mb-4">

                            <label>Altura (cm)</label>

                            <input type="number"
                                   step="0.1"
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

    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>