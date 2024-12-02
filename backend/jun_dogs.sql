-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 25, 2024 at 04:08 PM
-- Server version: 5.7.24
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jun_dogs`
CREATE DATABASE IF NOT EXISTS `jun_dogs` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `jun_dogs`;
--

-- --------------------------------------------------------

--
-- Table structure for table `adopciones`
--

CREATE TABLE `adopciones` (
  `adopcion_id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `cita_id` int(11) DEFAULT NULL,
  `fecha_adopcion` date NOT NULL,
  `estado_adopcion` enum('Pendiente','Aprobada','Rechazada') DEFAULT 'Pendiente',
  `comentarios` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `animales`
--

CREATE TABLE `animales` (
  `animal_id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `tipo_animal` enum('Perro','Gato','Otro') NOT NULL,
  `tamaño` enum('Pequeño','Mediano','Grande') DEFAULT NULL,
  `foto_url` varchar(255) DEFAULT NULL,
  `descripcion` text,
  `vacunas` varchar(255) DEFAULT NULL,
  `estado_adopcion` enum('Disponible','Adoptado','Fallecido') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `citas`
--

CREATE TABLE `citas` (
  `cita_id` int(11) NOT NULL,
  `animal_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fecha_cita` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `motivo` text,
  `estado_cita` enum('Pendiente','Aprobada','Rechazada') DEFAULT 'Pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `user_id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido_paterno` varchar(50) NOT NULL,
  `apellido_materno` varchar(50) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  `sexo` enum('Masculino','Femenino','Otro') DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`user_id`, `nombre`, `apellido_paterno`, `apellido_materno`, `fecha_nacimiento`, `direccion`, `sexo`, `email`, `contrasena`) VALUES
(1, 'judith', 'guzman', 'cortez', '2002-05-25', 'madero 234', 'Femenino', 'judith@judith.com', 'judith123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adopciones`
--
ALTER TABLE `adopciones`
  ADD PRIMARY KEY (`adopcion_id`),
  ADD KEY `animal_id` (`animal_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `cita_id` (`cita_id`);

--
-- Indexes for table `animales`
--
ALTER TABLE `animales`
  ADD PRIMARY KEY (`animal_id`),
  ADD KEY `idx_animal_estado_adopcion` (`estado_adopcion`);

--
-- Indexes for table `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`cita_id`),
  ADD KEY `animal_id` (`animal_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_usuario_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `adopciones`
--
ALTER TABLE `adopciones`
  MODIFY `adopcion_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `animales`
--
ALTER TABLE `animales`
  MODIFY `animal_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `citas`
--
ALTER TABLE `citas`
  MODIFY `cita_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adopciones`
--
ALTER TABLE `adopciones`
  ADD CONSTRAINT `adopciones_ibfk_1` FOREIGN KEY (`animal_id`) REFERENCES `animales` (`animal_id`),
  ADD CONSTRAINT `adopciones_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`user_id`),
  ADD CONSTRAINT `adopciones_ibfk_3` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`cita_id`);

--
-- Constraints for table `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`animal_id`) REFERENCES `animales` (`animal_id`),
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

INSERT INTO `animales` (`animal_id`, `nombre`, `tipo_animal`, `tamaño`, `foto_url`, `descripcion`, `vacunas`, `estado_adopcion`)
VALUES
(NULL, 'Max', 'Perro', 'Grande', 'https://cdn.britannica.com/79/232779-050-6B0411D7/German-Shepherd-dog-Alsatian.jpg', 'Un perro amistoso y juguetón que ama correr en el parque.', 'Rabia, Parvovirus', 'Disponible'),
(NULL, 'Mia', 'Gato', 'Pequeño', 'https://images.unsplash.com/photo-1543852786-1cf6624b9987?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8N3x8Y2F0c3xlbnwwfHwwfHx8MA%3D%3D', 'Una gata tranquila que disfruta dormir al sol.', 'Leucemia Felina, Rabia', 'Disponible'),
(NULL, 'Rocky', 'Perro', 'Mediano', 'https://spca.bc.ca/wp-content/uploads/2023/06/happy-samoyed-dog-outdoors-in-summer-field.jpg', 'Perro rescatado, valiente y leal, ideal para una familia activa.', 'Rabia, Moquillo', 'Disponible'),
(NULL, 'Luna', 'Otro', 'Pequeño', 'https://www.adiosmascota.es/wp-content/uploads/2022/01/white-rabbit-on-the-grass.jpg', 'Conejita blanca y suave, perfecta como mascota de interior.', 'Mixomatosis', 'Disponible'),
(NULL, 'Pelusa', 'Perro', 'Mediano', 'https://i.pinimg.com/originals/c8/bc/32/c8bc325ed1a96a2bc33e2e8a0527bc92.jpg', 'Perra cariñosa y enérgica, perfecta para niños.', 'Rabia, Parvovirus', 'Disponible'),
(NULL, 'Simba', 'Gato', 'Grande', 'https://fbi.cults3d.com/uploaders/32338244/illustration-file/f23d00d1-2861-40af-8e83-1e8f5eda7403/WhatsApp-Image-2024-08-27-at-6.36.25-PM.jpeg', 'Gato juguetón y curioso, le encanta explorar.', 'Leucemia Felina, Rabia', 'Disponible'),
(NULL, 'Charlie', 'Perro', 'Pequeño', 'https://s1.elespanol.com/2022/03/16/curiosidades/mascotas/657694610_222735436_1706x960.jpg', 'Perro pequeño y valiente, ideal para apartamentos.', 'Rabia, Moquillo', 'Disponible'),
(NULL, 'Nala', 'Otro', 'Mediano', 'https://supermascotas.cl/wp-content/uploads/2017/11/coneja_ed49.jpg', 'Conejita activa y curiosa, necesita espacio para correr.', 'Mixomatosis', 'Disponible');
