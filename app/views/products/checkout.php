<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="fondo-formulario d-flex align-items-center">

    <div class="container">

        <div class="row align-items-center">

            <!-- TEXTO -->
            <div class="col-md-6 text-white">

                <h1 class="titulo-formulario">
                    FINALIZAR <br>
                    <span>COMPRA</span>
                </h1>

            </div>

            <!-- FORM -->
            <div class="col-md-6 d-flex justify-content-center">

                <div class="caja-formulario">

                    <h3 class="text-center mb-4 text-white">
                        DATOS DE PAGO
                    </h3>

                    <form action="index.php?action=procesarPago" method="POST">

                        <!-- NOMBRE -->
                        <div class="mb-3">
                            <label>Nombre completo</label>

                            <input type="text"
                                   class="form-control"
                                   placeholder="Tu nombre">
                        </div>

                        <!-- DIRECCIÓN -->
                        <div class="mb-3">
                            <label>Dirección</label>

                            <input type="text"
                                   class="form-control"
                                   placeholder="Tu dirección">
                        </div>

                        <!-- TELÉFONO -->
                        <div class="mb-3">
                            <label>Teléfono</label>

                            <input type="text"
                                   class="form-control"
                                   placeholder="Tu teléfono">
                        </div>

                        <!-- NÚMERO TARJETA -->

                        <div class="mb-3">

                            <label>Número de tarjeta</label>

                            <input type="text"
                                id="cardNumber"
                                name="cardNumber"
                                class="form-control"
                                placeholder="4242 4242 4242 4242"
                                required>

                        </div>

                        <!-- TITULAR -->

                        <div class="mb-3">

                            <label>Titular de la tarjeta</label>

                            <input type="text"
                                id="cardHolder"
                                name="cardHolder"
                                class="form-control"
                                placeholder="Juan Pérez"
                                required>

                        </div>

                        <!-- FECHA + CVV -->

                        <div class="row">

                            <!-- FECHA -->
                            <div class="col-md-6 mb-3">

                                <label>Fecha expiración</label>

                                <input type="text"
                                    id="expiry"
                                    name="expiry"
                                    class="form-control"
                                    placeholder="MM/YY"
                                    required>

                            </div>

                            <!-- CVV -->
                            <div class="col-md-6 mb-3">

                                <label>CVV</label>

                                <input type="text"
                                    id="cvv"
                                    name="cvv"
                                    class="form-control"
                                    placeholder="123"
                                    required>

                            </div>

                        </div>

                        <!-- MÉTODO PAGO -->

                        <div class="mb-4">

                            <label>Método de pago</label>

                            <select name="paymentMethod"
                                    class="form-select">

                                <option value="Tarjeta">
                                    Tarjeta
                                </option>

                                <option value="PayPal">
                                    PayPal
                                </option>

                                <option value="Bizum">
                                    Bizum
                                </option>

                            </select>

                        </div>

                        <!-- TOTAL -->
                        <div class="mb-4 text-white">

                            <h5>
                                Total: 
                                <?php echo $_SESSION['total'] ?? 0; ?>€
                            </h5>

                        </div>

                        <!-- BOTÓN -->
                        <button class="btn btn-primary w-100">

                            FINALIZAR COMPRA

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>