function formatInput(input) {
    // Eliminar cualquier carácter que no sea un número
    input.value = input.value.replace(/[^0-9]/g, '');

    // Si hay más de 2 dígitos, insertar el punto decimal
    if (input.value.length > 2) {
        const integerPart = input.value.slice(0, -2); // Todos menos los últimos dos dígitos
        const decimalPart = input.value.slice(-2); // Los últimos dos dígitos

        // Combinar partes y asegurarse de que solo haya un punto decimal
        input.value = integerPart + (integerPart.length > 0 ? '.' : '') + decimalPart; 
    }
}