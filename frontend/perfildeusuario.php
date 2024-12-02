<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: InicioSesion.html");
    exit;
}

require_once '../backend/config/database.php';
$database = new Database();
$conn = $database->getConnection();
$mascotas = [];
$citas = [];
$proximaCita = null;

if ($conn) {
    try {
        $sql = "SELECT nombre FROM animales WHERE estado_adopcion = 'Disponible'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $mascotas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch user's appointments
        $sql = "SELECT c.cita_id, c.fecha_cita, c.motivo, c.estado_cita, a.nombre AS nombre_mascota, a.foto_url
                FROM citas c
                JOIN animales a ON c.animal_id = a.animal_id
                WHERE c.user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch the next upcoming appointment
        $sql = "SELECT c.fecha_cita, c.motivo, c.estado_cita, a.nombre AS nombre_mascota, a.foto_url
                FROM citas c
                JOIN animales a ON c.animal_id = a.animal_id
                WHERE c.user_id = :user_id AND c.fecha_cita > NOW()
                ORDER BY c.fecha_cita ASC
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $proximaCita = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error en la base de datos: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../frontend/css/perfildeusuario.css">
        <link rel="stylesheet" href="./css/inicio.css">
    <link rel="shortcut icon" href="./imagenes/favicon.png" type="image/x-icon">

</head>

<body>
    <!-- Barra de navegacion -->
    <div class="navbar">
        <img src="./imagenes/nav.png" alt="huellitas">
        <h1><a href="Inicio.html" class="navbar-link">Junt-Dogs</a></h1>
        <img src="./imagenes/nav.png" alt="huellitas">
        <div class="links">
            <a href="Adopciones.html">¿Cómo adoptar?</a>
            <a href="Nosotros.html">¿Quiénes Somos?</a>
            <a href="Testimonios.html">Testimonios</a>
            <a href="Contacto.html">Contacto</a>
            <button class="btn btn-light" onclick="window.location.href='InicioSesion.html'">Iniciar sesión</button>
            <button class="btn btn-dark" onclick="window.location.href='Registro.html'">Registro</button>
        </div>
    </div>

    <hr class="divider">

    <div class="profile-info">
        <h2>Bienvenido, <?php echo $_SESSION['nombre']; ?></h2>
        <p>Apellido Paterno: <?php echo $_SESSION['apellido_paterno']; ?></p>
        <p>Apellido Materno: <?php echo $_SESSION['apellido_materno']; ?></p>
        <p>Dirección: <?php echo $_SESSION['direccion']; ?></p>
        <p>Email: <?php echo $_SESSION['email']; ?></p>
    </div>
    <!-- Contenido principal -->
    <main class="container mt-4">
        <section id="nueva-cita">
            <h2 class="text-center" style="color: #D46A6A; font-family: 'Itim', cursive;">Agendar Nueva Cita</h2>
            <p class="text-center">Completa el formulario para programar tu cita</p>
            <form action="../backend/registrar_cita.php" method="post" class="table-container">
                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Campo</th>
                            <th>Descripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Fecha de la Cita</td>
                            <td><input type="date" class="form-control" name="fecha" required></td>
                        </tr>
                        <tr>
                            <td>Hora de la Cita</td>
                            <td><input type="time" class="form-control" name="hora" required></td>
                        </tr>
                        <tr>
                            <td>Mascota de Interés</td>
                            <td>
                                <select class="form-select" name="mascota" required>
                                    <option value="" disabled selected>Selecciona una mascota</option>
                                    <?php foreach ($mascotas as $mascota): ?>
                                        <option value="<?php echo $mascota['nombre']; ?>"><?php echo $mascota['nombre']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        <tr>
                            <td>Motivo</td>
                            <td><input type="text" class="form-control" name="motivo" required></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-adopt">Agendar Cita</button>
                </div>
            </form>
        </section>
        <section id="mis-citas" class="mt-5">
            <h2 class="text-center" style="color: #D46A6A; font-family: 'Itim', cursive;">Mis Citas</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Mascota</th>
                        <th>Foto</th>
                        <th>Motivo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($citas as $cita): ?>
                        <tr>
                            <td><?php echo date('Y-m-d', strtotime($cita['fecha_cita'])); ?></td>
                            <td><?php echo date('H:i', strtotime($cita['fecha_cita'])); ?></td>
                            <td><?php echo $cita['nombre_mascota']; ?></td>
                            <td><img src="<?php echo $cita['foto_url']; ?>" alt="<?php echo $cita['nombre_mascota']; ?>" width="50"></td>
                            <td><?php echo $cita['motivo']; ?></td>
                            <td><?php echo $cita['estado_cita']; ?></td>
                            <td>
                                <form action="../backend/eliminar_cita.php" method="post" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cita?');" style="display:inline;">
                                    <input type="hidden" name="cita_id" value="<?php echo $cita['cita_id']; ?>">
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form>
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalEditarCita<?php echo $cita['cita_id']; ?>">Editar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

    <?php foreach ($citas as $cita): ?>
        <!-- Modal para editar cita -->
        <div class="modal fade" id="modalEditarCita<?php echo $cita['cita_id']; ?>" tabindex="-1" aria-labelledby="modalEditarCitaLabel<?php echo $cita['cita_id']; ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-custom">
                    <div class="modal-header modal-header-custom">
                        <h5 class="modal-title" id="modalEditarCitaLabel<?php echo $cita['cita_id']; ?>">Editar Cita</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body modal-body-custom">
                        <form action="../backend/editar_cita.php" method="post">
                            <input type="hidden" name="cita_id" value="<?php echo $cita['cita_id']; ?>">
                            <div class="mb-3">
                                <label for="fecha<?php echo $cita['cita_id']; ?>" class="form-label">Fecha de la Cita</label>
                                <input type="date" class="form-control" id="fecha<?php echo $cita['cita_id']; ?>" name="fecha" value="<?php echo date('Y-m-d', strtotime($cita['fecha_cita'])); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="hora<?php echo $cita['cita_id']; ?>" class="form-label">Hora de la Cita</label>
                                <input type="time" class="form-control" id="hora<?php echo $cita['cita_id']; ?>" name="hora" value="<?php echo date('H:i', strtotime($cita['fecha_cita'])); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="mascota<?php echo $cita['cita_id']; ?>" class="form-label">Mascota de Interés</label>
                                <select class="form-select" id="mascota<?php echo $cita['cita_id']; ?>" name="mascota" required>
                                    <?php foreach ($mascotas as $mascota): ?>
                                        <option value="<?php echo $mascota['nombre']; ?>" <?php echo ($mascota['nombre'] == $cita['nombre_mascota']) ? 'selected' : ''; ?>><?php echo $mascota['nombre']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="motivo<?php echo $cita['cita_id']; ?>" class="form-label">Motivo</label>
                                <input type="text" class="form-control" id="motivo<?php echo $cita['cita_id']; ?>" name="motivo" value="<?php echo $cita['motivo']; ?>" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Boton y Panel  -->
    <div class="floating-panel">
        <button class="btn-panel" onclick="togglePanel()">Tus Citas</button>
        <div id="panel-options" class="panel-hidden">
            <ul>
                <li><a href="#nueva-cita" onclick="togglePanel()">Nueva Cita</a></li>
                <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalProximaCita">Cita Próxima</a></li>
            </ul>
        </div>
    </div>

    <!-- Modal de citas -->
    <div class="modal fade" id="modalTusCitas" tabindex="-1" aria-labelledby="modalTusCitasLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title" id="modalTusCitasLabel">Tus Citas</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-custom">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Mascota</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2024-11-25</td>
                                <td>10:00 AM</td>
                                <td>Bella</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de proxima cita -->
    <div class="modal fade" id="modalProximaCita" tabindex="-1" aria-labelledby="modalProximaCitaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-custom">
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title" id="modalProximaCitaLabel">Próxima Cita</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-body-custom">
                    <?php if ($proximaCita): ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Mascota</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo date('Y-m-d', strtotime($proximaCita['fecha_cita'])); ?></td>
                                    <td><?php echo date('H:i', strtotime($proximaCita['fecha_cita'])); ?></td>
                                    <td><?php echo $proximaCita['nombre_mascota']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No tienes citas próximas.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="logout-container">
        <button class="btn-logout" onclick="cerrarSesion()">Cerrar Sesión</button>
    </div>

       <!-- Footer -->
       <footer class="bg-light text-center text-lg-start mt-5">
        <div class="container p-4">
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Junt-Dogs</h5>
                    <p>
                        Dedicados a encontrar hogares amorosos para perros y gatos necesitados en Salamanca, Guanajuato.
                    </p>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Links Rápidos</h5>
                    <ul class="list-unstyled mb-0">
                        <li><a href="Adopciones.html" class="text-dark">Adopciones</a></li>
                        <li><a href="Nosotros.html" class="text-dark">Quiénes Somos</a></li>
                        <li><a href="Contacto.html" class="text-dark">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-0">Síguenos</h5>
                    <ul class="list-unstyled">
                        <li><a href="#!" class="text-dark">Facebook</a></li>
                        <li><a href="#!" class="text-dark">Instagram</a></li>
                        <li><a href="#!" class="text-dark">Twitter</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
            © 2024 Junt-Dogs - Adopta un amigo hoy mismo
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePanel() {
            const panel = document.getElementById('panel-options');
            panel.classList.toggle('panel-visible');
        }

        function cerrarSesion() {
            alert("Sesión cerrada exitosamente.");
            window.location.href = "InicioSesion.html";
        }
    </script>

    <script>
        function cerrarSesion() {
            // Cierre de sesion
            alert("Sesión cerrada exitosamente.");
            window.location.href = "InicioSesion.html";
        }
    </script>
</body>

</html>