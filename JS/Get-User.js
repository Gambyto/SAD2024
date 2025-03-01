function cargarEmpleado(cedula) {
    $.ajax({
        url: '../PHP/CTR/Get_User.php',
        type: 'GET',
        data: { cedula: cedula },
        success: function(data) {
            console.log(data); // Imprimir la respuesta del servidor
            try {
                const empleado = JSON.parse(data);
                // Rellenar el formulario del modal con los datos del empleado
                document.getElementById('nombreModal').value = empleado.nombre;
                document.getElementById('apellidoModal').value = empleado.apellido;
                document.getElementById('cedulaModal').value = empleado.cedula; // Este campo puede ser de solo lectura
                document.getElementById('telefonoModal').value = empleado.clave;
                document.getElementById('telefono2Modal').value = empleado.type;
                document.getElementById('correoModal').value = empleado.username;
        
                // Mostrar el modal
                $('#empleadoModal').modal('show');
            } catch (e) {
                console.error('Error al analizar JSON:', e);
                alert('Error en la respuesta del servidor. Verifica la consola para más detalles.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error en la solicitud:', error);
            alert('Error al cargar los datos del empleado.');
        }
    });
}