// TODO CARGADO

document.addEventListener('DOMContentLoaded', () => {

    // ALERTAS AUTOMÁTICAS
console.log("JS funcionando");
    const alerts = document.querySelectorAll('.aviso');
    alerts.forEach(alert => {

        // MOSTRAR
        setTimeout(() => {
            alert.classList.add('mostrar-aviso');
        }, 100);

        // OCULTAR
        setTimeout(() => {
            alert.classList.remove('mostrar-aviso');
            alert.classList.add('ocultar-aviso');

            // ELIMINAR
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 3500);
    });

    // POPUP PRODUCTOS

    const modal =
        document.getElementById('productModal');

    // SI EXISTE MODAL PRODUCTOS

    if (modal) {

        const cards =
            document.querySelectorAll('.abrir-producto');

        const closeBtn =
            document.getElementById('closeModal');

        const modalImage =
            document.getElementById('modalImage');

        const modalTitle =
            document.getElementById('modalTitle');

        const modalPrice =
            document.getElementById('modalPrice');

        const modalDescription =
            document.getElementById('modalDescription');

        const modalBuyBtn =
            document.getElementById('modalBuyBtn');

        // ABRIR POPUP

        cards.forEach(card => {

            card.addEventListener('click', (e) => {

                // SI PULSA EN BOTÓN NO ABRIR
                if (e.target.closest('a')) {
                    return;
                }

                modalImage.src =
                    card.dataset.image;

                modalTitle.innerText =
                    card.dataset.name;

                modalPrice.innerText =
                    card.dataset.price;

                modalDescription.innerText =
                    card.dataset.description;

                modalBuyBtn.href =
                    `index.php?action=agregarAlCarrito&id=${card.dataset.id}`;

                // MOSTRAR
                modal.classList.add('mostrar-ventana');

                // BLOQUEAR SCROLL
                document.body.classList.add('modal-abierto');

            });

        });

        // CERRAR BOTÓN

        closeBtn.addEventListener('click', cerrarModalProducto);

        // CERRAR CLICK FUERA

        modal.addEventListener('click', (e) => {

            if (e.target === modal) {
                cerrarModalProducto();
            }
        });

        // FUNCIÓN CERRAR

        function cerrarModalProducto() {

            modal.classList.remove('mostrar-ventana');

            document.body.classList.remove('modal-abierto');

        }

    }

    // POPUP SERVICIOS

    const serviceModal =
        document.getElementById('serviceModal');

    // SI EXISTE MODAL SERVICIOS

    if (serviceModal) {

        const serviceCards =
            document.querySelectorAll('.abrir-servicio');

        const closeServiceModal =
            document.getElementById('closeServiceModal');

        const serviceModalImage =
            document.getElementById('serviceModalImage');

        const serviceModalTitle =
            document.getElementById('serviceModalTitle');

        const serviceModalDescription =
            document.getElementById('serviceModalDescription');

        // ABRIR

        serviceCards.forEach(card => {

            card.addEventListener('click', () => {

                serviceModalImage.src =
                    card.dataset.image;

                serviceModalTitle.innerText =
                    card.dataset.title;

                serviceModalDescription.innerText =
                    card.dataset.description;

                // MOSTRAR
                serviceModal.classList.add('mostrar-servicio');

                // BLOQUEAR SCROLL
                document.body.classList.add('modal-abierto');

            });

        });

        // CERRAR BOTÓN

        closeServiceModal.addEventListener(
            'click',
            cerrarModalServicio
        );

        // CERRAR CLICK FUERA

        serviceModal.addEventListener('click', (e) => {

            if (e.target === serviceModal) {

                cerrarModalServicio();

            }

        });

        // FUNCIÓN CERRAR

        function cerrarModalServicio() {

            serviceModal.classList.remove('mostrar-servicio');

            document.body.classList.remove('modal-abierto');

        }

    }

});