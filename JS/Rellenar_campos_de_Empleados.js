function buscarEmpleado(cedula) {
    if (cedula.length >= 7 && cedula.length <= 8) { // Asegúrate de que la cédula tenga 8 dígitos
        $.ajax({
            url: '../PHP/CTR/Search_General.php', // Cambia esto por la ruta a tu script PHP
            type: 'POST',
            data: { cedula: cedula,
                op: 1 },
            success: function(response) {
                const datos = JSON.parse(response);
                rellenarFormulario(datos);
            },
            error: function() {
                alert('Error en la búsqueda del empleado. Intente nuevamente.');
            }
        });
    } else {
        // Limpiar los campos si la cédula no tiene 8 dígitos
        rellenarFormulario(null);
    }
}

function rellenarFormulario(datos) {
    if (datos) {
        console.log(datos);
        
        // Rellenar campos de información personal
        $('#validationCustom03').val(datos.cedula);
        $('#validationCustom01').val(datos.nombre);
        $('#validationCustom02').val(datos.apellido);
        $('#validationCustom04').val(datos.tlf);
        $('#validationCustom05').val(datos.second_tlf);
        $('#validationCustom6').val(datos.direccion);
        $('#validationCustom7').val(datos.correo);
        
        // Rellenar campos de información laboral
        $('#validationCustom09').val(datos.departamento);
        $('#validationCustom10').val(datos.cargo);
        $('#validationCustom11').val(datos.f_ingreso);
        $('#validationCustom12').val(datos.sueldo);
        
        // Rellenar campos de información especial
        if (datos.discapacidad !== 'Ninguna') {
            $('#validationFormCheck4').prop('checked', true);
            $('#validationCustom16').val(datos.discapacidad).change();
            $('#validationCustom17').val(datos.afeccion).change();
        } else {
            $('#validationFormCheck5').prop('checked', true);
            $('#validationCustom16').val('Ninguna').change();
            $('#validationCustom17').val('No aplica').change();
        }
        
        // Rellenar campos de información de sexo
        if (datos.sexo === 'H') {
            $('#validationFormCheck2').prop('checked', true);
        } else {
            $('#validationFormCheck3').prop('checked', true);
        }
        
        // Rellenar campos de información de fecha de nacimiento
        $('#validationCustom13').val(datos.edad);
        
    } else {
        // Si no se encuentra el usuario, puedes limpiar los campos o mostrar un mensaje
        $('#validationCustom03').val('');
        $('#validationCustom01').val('');
        $('#validationCustom02').val('');
        $('#validationCustom04').val('');
        $('#validationCustom05').val('');
        $('#validationCustom06').val('');
        $('#validationCustom07').val('');
        $('#validationCustom09').val('');
        $('#validationCustom10').val('');
        $('#validationCustom11').val('');
        $('#validationCustom12').val('');
        $('#validationFormCheck4').prop('checked', false);
        $('#validationFormCheck5').prop('checked', false);
        $('#validationCustom16').val('Ninguna');
        $('#validationCustom17').val('No aplica');
        $('#validationFormCheck2').prop('checked', false);
        $('#validationFormCheck3').prop('checked', false);
        $('#validationCustom13').val('');
    }
}