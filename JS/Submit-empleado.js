
$(document).ready(function() {
    // Envío del formulario de registro de empleados
    $('#FormEmpleado').on('submit', function(e) {
        e.preventDefault(); // Evita el envío normal del formulario

        // Asegúrate de que el formulario de actualización no esté visible
        if ($('#empleadoModal').is(':visible')) {
            return; // No hacer nada si el modal está visible
        }
 
        // Recoge los datos del formulario
        var formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: '../PHP/CTR/Insert_Empleado_CTR.php',
            data: formData,
            success: function(response) {
                console.log(response);
                var data = JSON.parse(response);
                if (data.html) {
                    $('#alerts').html(data.html);
                } else {
                    alert(data.message);
                }
            },
            error: function() {
                alert('Error al guardar los datos.');
            }
        });
    });
});