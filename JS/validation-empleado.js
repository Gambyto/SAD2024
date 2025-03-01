(() => {
    'use strict';

    // Selecciona solo el formulario específico por su ID
    const formUpdate = document.getElementById('FormEmpleadoModal');
    const formRegister = document.getElementById('FormEmpleado');

    // Función para manejar la validación y el envío del formulario
    const handleFormSubmit = (form) => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                // Aquí puedes agregar lógica para el envío del formulario, si es necesario
                console.log('Formulario enviado:', form.id);
            }
            form.classList.add('was-validated');
        }, false);
    };

    // Validación para el formulario de actualización
    if (formUpdate) {
        handleFormSubmit(formUpdate);
    }

    // Validación para el formulario de registro
    if (formRegister) {
        handleFormSubmit(formRegister);
    }
})();




