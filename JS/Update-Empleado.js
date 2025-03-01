function guardarCambios() {
    const empleadoData = {
        cedula: document.getElementById('cedulaModal').value,
        nombre: document.getElementById('nombreModal').value,
        apellido: document.getElementById('apellidoModal').value,
        tlf: document.getElementById('telefonoModal').value,
        direccion: document.getElementById('direccionModal').value,
        correo: document.getElementById('correoModal').value,
        sexo: document.getElementById('sexoModal').value,
        edad: document.getElementById('edadModal').value,
        departamento: document.getElementById('departamentoModal').value,
        cargo: document.getElementById('cargoModal').value,
        f_ingreso: document.getElementById('fechaIngresoModal').value,
        sueldo: document.getElementById('sueldoModal').value
    };

    $.ajax({
        url: 'CTR/Update_Empleado_CTR.php', // Cambia esta URL según tu estructura de carpetas
        type: 'POST',
        data: empleadoData,
        success: function(response) {
            if (response.success) {
                alert('Datos actualizados correctamente.');
                $('#empleadoModal').modal('hide');
                // Aquí puedes recargar la lista de empleados o actualizar la tabla
                location.reload(); // Recargar la página para ver los cambios
            } else {
                alert('Error al actualizar los datos: ' + response.message);
            }
        },
        error: function() {
            alert('Error en la conexión al servidor.');
        }
    });
}