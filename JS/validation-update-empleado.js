(() => {
    'use strict'

    // Selecciona solo el formulario específico por su ID
    const form = document.getElementById('FormEmpleadoModal');

    // Asegúrate de que el formulario exista antes de agregar el listener
    if (form) {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false);
    }
})();