function formatPhoneNumber(input) {
    // Eliminar todos los caracteres que no sean números
    let value = input.value.replace(/[^0-9]/g, '');
    
    // Formatear el número en el formato deseado
    if (value.length > 4) {
        value = value.substring(0, 4) + ' - ' + value.substring(4, 11);
    }
    
    // Asignar el valor formateado de nuevo al input
    input.value = value;
}