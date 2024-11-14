-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS JUN_DOGS;
USE JUN_DOGS;

-- Tabla de Usuarios
CREATE TABLE IF NOT EXISTS Usuarios (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido_paterno VARCHAR(50) NOT NULL,
    apellido_materno VARCHAR(50),
    fecha_nacimiento DATE,
    direccion VARCHAR(100),
    sexo ENUM('Masculino', 'Femenino', 'Otro'),
    identificacion_oficial VARCHAR(20) UNIQUE,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de Eventos
CREATE TABLE IF NOT EXISTS Eventos (
    evento_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_evento VARCHAR(100) NOT NULL,
    descripcion TEXT,
    fecha_evento DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de Animales
CREATE TABLE IF NOT EXISTS Animales (
    animal_id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    tipo_animal ENUM('Perro', 'Gato', 'Otro') NOT NULL,
    tamaño ENUM('Pequeño', 'Mediano', 'Grande'),
    foto_url VARCHAR(255),
    descripcion TEXT,
    vacunas VARCHAR(255),
    estado_adopcion ENUM('Disponible', 'Adoptado', 'Fallecido') NOT NULL,
    comportamiento TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de Citas
CREATE TABLE IF NOT EXISTS Citas (
    cita_id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT NOT NULL,
    user_id INT NOT NULL,
    fecha_cita TIMESTAMP NOT NULL,
    motivo TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (animal_id) REFERENCES Animales(animal_id),
    FOREIGN KEY (user_id) REFERENCES Usuarios(user_id)
);

-- Tabla de Adopciones
CREATE TABLE IF NOT EXISTS Adopciones (
    adopcion_id INT AUTO_INCREMENT PRIMARY KEY,
    animal_id INT NOT NULL,
    user_id INT NOT NULL,
    cita_id INT, -- Nueva columna para la llave foránea de citas
    fecha_inicial DATE NOT NULL,
    estado_adopcion ENUM('Pendiente', 'Aprobada', 'Rechazada') DEFAULT 'Pendiente',
    fecha_final DATE,
    comentarios TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (animal_id) REFERENCES Animales(animal_id),
    FOREIGN KEY (user_id) REFERENCES Usuarios(user_id),
    FOREIGN KEY (cita_id) REFERENCES Citas(cita_id) -- Llave foránea para citas
);


CREATE TABLE IF NOT EXISTS Donaciones (
    donacion_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tipo_donacion ENUM('Dinero', 'Comida', 'Otros') NOT NULL,
    cantidad DECIMAL(10, 2) CHECK (cantidad > 0), -- Para dinero u otra cantidad en caso de bienes
    descripcion TEXT,
    fecha_donacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Cambiado a TIMESTAMP
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Usuarios(user_id)
);

-- Tabla intermedia de Participacion en Eventos
CREATE TABLE IF NOT EXISTS Participacion_Evento (
    participacion_id INT AUTO_INCREMENT PRIMARY KEY,
    evento_id INT NOT NULL,
    user_id INT NOT NULL,
    monto_aportado DECIMAL(10, 2) CHECK (monto_aportado >= 0),
    fecha_participacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Cambiado a TIMESTAMP
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (evento_id) REFERENCES Eventos(evento_id),
    FOREIGN KEY (user_id) REFERENCES Usuarios(user_id)
);

-- Opcional: Crear índices en campos frecuentemente buscados
CREATE INDEX idx_usuario_email ON Usuarios(email);
CREATE INDEX idx_animal_estado_adopcion ON Animales(estado_adopcion);
