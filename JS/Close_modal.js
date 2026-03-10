    // Agregar un evento de clic a todos los botones de close de los modales
$('.modal .btn-close').on('click', function() {
    // Cerrar el modal manualmente
    $(this).closest('.modal').modal('hide');
});

