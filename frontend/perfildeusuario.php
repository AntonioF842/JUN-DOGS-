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

if ($conn) {
    try {
        $sql = "SELECT nombre FROM animales WHERE estado_adopcion = 'Disponible'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $mascotas = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Adopta una Mascota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../frontend/css/perfildeusuario.css">
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
            <button class="btn btn-dark">Registro</button>
        </div>
    </div>

    <hr class="divider">

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
    </main>

    <!-- Boton y Panel  -->
    <div class="floating-panel">
        <button class="btn-panel" onclick="togglePanel()">Tus Citas</button>
        <div id="panel-options" class="panel-hidden">
            <ul>
                <li><a href="#nueva-cita" onclick="togglePanel()">Nueva Cita</a></li>
                <li><a href="#" data-bs-toggle="modal" data-bs-target="#modalTusCitas">Tus Citas</a></li>
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

    <script>
        function togglePanel() {
            const panel = document.getElementById('panel-options');
            panel.classList.toggle('panel-visible');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <div class="logout-container">
        <button class="btn-logout" onclick="cerrarSesion()">Cerrar Sesión</button>
    </div>

    <script>
        function cerrarSesion() {
            // ciere de sesion
            alert("Sesión cerrada exitosamente.");
            window.location.href = "InicioSesion.html"; // Redireccion pagina de inicio
        }
    </script>
    <div class="sidebar-profile">
        <img src="imagenes/imagen2.jpg" alt="Avatar de usuario" class="profile-avatar">
        <ul class="sidebar-links">
            <li><a href="#mi-perfil">Mi Perfil</a></li>
            <li><a href="#configuracion">Configuración</a></li>
            <li><a href="#" onclick="cerrarSesion()">Cerrar Sesión</a></li>
        </ul>
    </div>
    <div class="profile-info">
        <h2>Bienvenido, <?php echo $_SESSION['nombre']; ?></h2>
        <p>Apellido Paterno: <?php echo $_SESSION['apellido_paterno']; ?></p>
        <p>Apellido Materno: <?php echo $_SESSION['apellido_materno']; ?></p>
        <p>Dirección: <?php echo $_SESSION['direccion']; ?></p>
        <p>Email: <?php echo $_SESSION['email']; ?></p>
    </div>
    <script>
        function cerrarSesion() {
            // Cierre de sesion
            alert("Sesión cerrada exitosamente.");
            window.location.href = "InicioSesion.html";
        }
    </script>
</body>

</html>