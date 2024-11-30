document.addEventListener('DOMContentLoaded', function() {
    fetchAnimals();

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