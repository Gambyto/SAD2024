function buscarEmpleado(cedula) {
    if (cedula.length >= 7 && cedula.length <= 8) { // Asegúrate de que la cédula tenga 8 dígitos
        $.ajax({
            url: '../PHP/CTR/Search_General.php', // Cambia esto por la ruta a tu script PHP
            type: 'POST',
            data: { cedula: cedula,
                op: 1 },
            success: function(response) {
                const datos = JSON.parse(response);
                if (datos) {
                    rellenarFormulario(datos);
                } else {
                    console.log('No se encontró un empleado con la cédula proporcionada.');
                }
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
    console.log('busqueda aplicada');
    if (datos) {
        console.log(datos);
        
        // Rellenar campos de información personal
        
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
    radioSi.dispatchEvent(new Event('change')); // Generar el HTML dinámicamente
    setTimeout(() => {
        const tipoDiscapacidad = datos.discapacidad;
        const opciones = {
            'Física': [
                'Parálisis Cerebral',
                'Esclerosis Múltiple',
                'Distrofia Muscular',
                'Lesión de la Médula Espinal',
                'Amputación',
                'Síndrome de Fatiga Crónica',
                'Enfermedad de Células Falciformes',
                'Fibrosis Quística',
                'Accidente Cerebrovascular',
                'Síndrome del Túnel Carpiano',
                'Trastornos de Dolor Crónico',
                'Parálisis de Bell',
                'Paraplejía Espástica Hereditaria',
                'Espina Bífida'
            ],
            'Mental': [
                'Autismo',
                'Síndrome de Down',
                'Discapacidad Intelectual',
                'Retraso Mental',
                'Síndrome de X Frágil',
                'Síndrome de Klinefelter',
                'Síndrome de Turner',
                'Síndrome de Williams',
                'Síndrome de Prader-Willi',
                'Trastorno de Aprendizaje No Verbal',
                'Dislexia',
                'Discalculia'
            ],
            'Sensorial': [
                'Ceguera',
                'Discapacidad Visual',
                'Sordera',
                'Discapacidad Auditiva',
                'Trastornos del Procesamiento Auditivo'
            ],
            'Salud mental': [
                'Depresión',
                'Ansiedad',
                'Trastorno Bipolar',
                'Esquizofrenia',
                'Trastorno Obsesivo-Compulsivo'
            ],
            'Otro': [
                'Especificar'
            ]
        };

        if (opciones[tipoDiscapacidad]) {
            opciones[tipoDiscapacidad].forEach(afeccion => {
                const option = document.createElement('option');
                option.value = afeccion;
                option.textContent = afeccion;
                document.getElementById('validationCustom17').appendChild(option);
            });
        }

        $('#validationCustom16').val(tipoDiscapacidad).change();
        $('#validationCustom17').val(datos.afeccion).change();
    }, 100); // Esperar un poco para que el HTML esté generado correctamente
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
    
    }
}