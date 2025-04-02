function verificarCedula(cedula) {
    if (cedula.length >= 7 && cedula.length <= 8) { // Asegúrate de que la cédula tenga 8 dígitos
        $.ajax({
            url: '../PHP/CTR/User_CTR.php', // Cambia esto por la ruta a tu script PHP
            type: 'POST',
            data: { cedula: cedula },
            success: function(response) {
                console.log(response); // Verificar la respuesta JSON
                const datos = JSON.parse(response);
                if (datos && Object.keys(datos).length > 0 && datos.cedula === cedula) { // Verificar si la respuesta JSON no es vacía
                    console.log("La cédula ya existe en el sistema.");
                    const alerta = document.getElementById('alerts');
                    alerta.innerHTML = `
                      <div class="container">
                        <div class="notification notification--failure">
                          <div class="notification__body">
                            <svg  class="notification__icon"
                            xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-xbox-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 21a9 9 0 0 0 9 -9a9 9 0 0 0 -9 -9a9 9 0 0 0 -9 9a9 9 0 0 0 9 9z" />
                            <path d="M9 8l6 8" />
                            <path d="M15 8l-6 8" /></svg>
                            La cédula ${cedula} ya existe en el sistema.
                          </div>
                          <div class="notification__progress"></div>
                        </div>
                      </div>
                    `;
                } else {
                    console.log("La cédula no existe en el sistema.");
                    const alerta = document.getElementById('alerts');
                    alerta.innerHTML = '';
                }
            },
            error: function() {
                alert('Error en la búsqueda del usuario. Intente nuevamente.');
            }
        });
    } else {
        console.log("La cédula no tiene 8 dígitos.");
        const alerta = document.getElementById('alerts');
        alerta.innerHTML = '';
    }
}