function cargarDatos() {
    const tablaAnimales = document.querySelector('.table tbody'); // Tabla de mascotas
    const tablaCitas = document.querySelectorAll('.table tbody')[1]; // Tabla de citas (asegurándonos de que es la segunda tabla)

    // Limpiar las tablas antes de agregar nuevas filas
    tablaAnimales.innerHTML = '';
    tablaCitas.innerHTML = '';

    // Hacer una petición al servidor para obtener los datos
    fetch('http://localhost:8888/JUN-DOGS-/backend/obtener_datos.php')
    .then(response => {
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }
        return response.json();
    })
    .then(data => {
        // Procesar las mascotas disponibles
        data.mascotas.forEach(mascota => {  // Cambié "animales" a "mascota"
            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${mascota.nombre}</td>
                <td>${mascota.tipo_animal}</td>   <!-- Cambié "tipo" a "tipo_animal" -->
                <td>${mascota.estado_adopcion}</td>   <!-- Cambié "estado" a "estado_adopcion" -->
            `;
            tablaAnimales.appendChild(fila);
        });

        // Rellenar la tabla de próximas citas
        data.citas.forEach(cita => {
            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${cita.nombre}</td>
                <td>${cita.fecha_cita}</td>   <!-- Cambié "fecha" a "fecha_cita" -->
                <td>${cita.motivo}</td>
            `;
            tablaCitas.appendChild(fila);
        });
    })
    .catch(error => {
        console.error('Error al cargar los datos:', error);
    });
}

// Cargar los datos al cargar la página
window.onload = cargarDatos;
