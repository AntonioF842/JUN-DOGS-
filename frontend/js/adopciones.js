document.addEventListener("DOMContentLoaded", function() {
    fetch('../backend/adopciones.php')
        .then(response => response.json())
        .then(data => {
            const carouselInner = document.getElementById('carousel-inner');
            const modalsContainer = document.getElementById('modals-container');
            const mascotasSection = document.getElementById('mascotas');
            carouselInner.innerHTML = '';
            modalsContainer.innerHTML = '';
            mascotasSection.innerHTML = '<section class="anim-gallery"><h2 class="gallery-title">Mascotas disponibles para adopción :3</h2></section>';

            const animGallery = mascotasSection.querySelector('.anim-gallery');

            data.forEach((animal, index) => {
                // Carousel item
                const carouselItem = document.createElement('div');
                carouselItem.className = `carousel-item ${index === 0 ? 'active' : ''}`;
                carouselItem.innerHTML = `
                    <div class="card">
                        <img src="${animal.foto_url}" class="card-img-top" alt="${animal.nombre}">
                        <div class="card-body">
                            <h5 class="card-title">${animal.nombre}</h5>
                            <p class="card-text"><strong>Edad:</strong> ${animal.edad} años</p>
                            <p class="card-text"><strong>Raza:</strong> ${animal.raza}</p>
                            <p>${animal.descripcion}</p>
                            <button class="btn btn-adopt" data-bs-toggle="modal" data-bs-target="#modal${animal.animal_id}">Más Información</button>
                        </div>
                    </div>
                `;
                carouselInner.appendChild(carouselItem);

                // Modal
                const modal = document.createElement('div');
                modal.className = 'modal fade';
                modal.id = `modal${animal.animal_id}`;
                modal.tabIndex = -1;
                modal.setAttribute('aria-labelledby', `modal${animal.animal_id}Label`);
                modal.setAttribute('aria-hidden', 'true');
                modal.innerHTML = `
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal${animal.animal_id}Label">Solicitud de Adopción - ${animal.nombre}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <img src="${animal.foto_url}" class="img-fluid mb-3" alt="${animal.nombre}">
                                        <div class="pet-details">
                                            <p><strong>Edad:</strong> ${animal.edad} años</p>
                                            <p><strong>Raza:</strong> ${animal.raza}</p>
                                            <p>${animal.descripcion}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <form class="adoption-form" id="adoptionForm${animal.animal_id}">
                                            <input type="hidden" name="animal_id" value="${animal.animal_id}">
                                            <div class="form-group mb-3">
                                                <label for="fecha_inicial">Fecha de Inicio de Adopción</label>
                                                <input type="date" class="form-control" id="fecha_inicial" name="fecha_inicial" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="fecha_final">Cita</label>
                                                <input type="date" class="form-control" id="fecha_final" name="fecha_final" required>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="estado_adopcion">Estado de la Adopción</label>
                                                <select class="form-control" id="estado_adopcion" name="estado_adopcion" required>
                                                    <option value="pendiente">Pendiente</option>
                                                    <option value="aprobada">Aprobada</option>
                                                    <option value="rechazada">Rechazada</option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="comentarios">Comentarios Adicionales</label>
                                                <textarea class="form-control" id="comentarios" name="comentarios" rows="3"></textarea>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" form="adoptionForm${animal.animal_id}" class="btn btn-primary">Enviar Solicitud</button>
                            </div>
                        </div>
                    </div>
                `;
                modalsContainer.appendChild(modal);

                // Animal card in the gallery
                const animalCard = document.createElement('div');
                animalCard.className = 'anim-tarj';
                animalCard.innerHTML = `
                    <img src="${animal.foto_url}" alt="Foto de ${animal.nombre}">
                    <h3>${animal.nombre}</h3>
                    <p><strong>Edad:</strong> ${animal.edad} años</p>
                    <p><strong>Raza:</strong> ${animal.raza}</p>
                    <p class="descripcion">${animal.descripcion}</p>
                    <button class="adopt-button btn btn-dark" data-bs-toggle="modal" data-bs-target="#modal${animal.animal_id}">Adoptar</button>
                `;
                animGallery.appendChild(animalCard);
            });
        })
        .catch(error => console.error('Error fetching adoption data:', error));
});