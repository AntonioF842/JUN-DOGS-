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
(NULL, 'Max', 'Perro', 'Grande', 'https://www.ngenespanol.com/wp-content/uploads/2024/03/estos-son-los-animales-que-no-deberias-tener-como-mascotas.jpg', 'Un perro amistoso y juguetón que ama correr en el parque.', 'Rabia, Parvovirus', 'Disponible'),
(NULL, 'Mia', 'Gato', 'Pequeño', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT5IsWeGH1zA3ZHMEHRLkiRCRUc4q7PzZwRsg&s', 'Una gata tranquila que disfruta dormir al sol.', 'Leucemia Felina, Rabia', 'Disponible'),
(NULL, 'Rocky', 'Perro', 'Mediano', 'https://images.pexels.com/photos/47547/squirrel-animal-cute-rodents-47547.jpeg?cs=srgb&dl=pexels-pixabay-47547.jpg&fm=jpg', 'Perro rescatado, valiente y leal, ideal para una familia activa.', 'Rabia, Moquillo', 'Disponible'),
(NULL, 'Luna', 'Otro', 'Pequeño', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTaBkz0geqhCevYfegYusMwyJjsV_3Tthrq3w&s', 'Conejita blanca y suave, perfecta como mascota de interior.', 'Mixomatosis', 'Disponible');
