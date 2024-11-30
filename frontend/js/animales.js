document.addEventListener('DOMContentLoaded', function() {
    fetchAnimals();
    fetchAppointments();

    document.getElementById('animalForm').addEventListener('submit', function(event) {
        event.preventDefault();
        saveAnimal();
    });
});

function fetchAnimals() {
    fetch('../backend/animales.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('animalTableBody');
            tableBody.innerHTML = '';
            data.forEach(animal => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${animal.animal_id}</td>
                    <td>${animal.nombre}</td>
                    <td>${animal.tipo_animal}</td>
                    <td>${animal.tamaño}</td>
                    <td><img src="${animal.foto_url}" alt="${animal.nombre}" width="50"></td>
                    <td>${animal.descripcion}</td>
                    <td>${animal.vacunas}</td>
                    <td>${animal.estado_adopcion}</td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editAnimal(${animal.animal_id})">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteAnimal(${animal.animal_id})">Eliminar</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        });
}

function saveAnimal() {
    const formData = new FormData(document.getElementById('animalForm'));
    fetch('../backend/animales.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(message => {
        document.getElementById('animalMessage').innerText = message;
        fetchAnimals();
        document.getElementById('animalForm').reset();
    });
}

function editAnimal(id) {
    fetch(`../backend/animales.php?id=${id}`)
        .then(response => response.json())
        .then(animal => {
            document.getElementById('animal_id').value = animal.animal_id;
            document.getElementById('nombre').value = animal.nombre;
            document.getElementById('tipo_animal').value = animal.tipo_animal;
            document.getElementById('tamaño').value = animal.tamaño;
            document.getElementById('foto_url').value = animal.foto_url;
            document.getElementById('descripcion').value = animal.descripcion;
            document.getElementById('vacunas').value = animal.vacunas;
            document.getElementById('estado_adopcion').value = animal.estado_adopcion;
        });
}

function deleteAnimal(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este animal?')) {
        fetch(`../backend/animales.php?id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.text())
        .then(message => {
            document.getElementById('animalMessage').innerText = message;
            fetchAnimals();
        });
    }
}

function fetchAppointments() {
    fetch('../backend/citas.php')
        .then(response => response.json())
        .then(data => {
            const pendingTableBody = document.getElementById('appointmentTableBody');
            const upcomingTableBody = document.getElementById('upcomingAppointmentTableBody');
            pendingTableBody.innerHTML = '';
            upcomingTableBody.innerHTML = '';
            data.forEach(cita => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${cita.nombre_usuario}</td>
                    <td>${cita.apellido_paterno}</td>
                    <td>${cita.apellido_materno}</td>
                    <td>${cita.nombre_mascota}</td>
                    <td><img src="${cita.foto_url}" alt="${cita.nombre_mascota}" width="50"></td>
                    <td>${new Date(cita.fecha_cita).toLocaleDateString()}</td>
                    <td>${new Date(cita.fecha_cita).toLocaleTimeString()}</td>
                `;
                if (cita.estado_cita === 'Pendiente') {
                    row.innerHTML += `
                        <td>${cita.estado_cita}</td>
                        <td>
                            <button class="btn btn-success btn-sm" onclick="updateAppointmentStatus(${cita.cita_id}, 'Aprobada')">Aprobar</button>
                            <button class="btn btn-danger btn-sm" onclick="updateAppointmentStatus(${cita.cita_id}, 'Rechazada')">Rechazar</button>
                        </td>
                    `;
                    pendingTableBody.appendChild(row);
                } else if (cita.estado_cita === 'Aprobada') {
                    upcomingTableBody.appendChild(row);
                }
            });
        });
}

function updateAppointmentStatus(id, status) {
    fetch(`../backend/citas.php`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `id=${id}&status=${status}`
    })
    .then(response => response.text())
    .then(message => {
        alert(message);
        fetchAppointments();
    });
}