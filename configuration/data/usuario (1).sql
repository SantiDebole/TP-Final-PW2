-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 20, 2024 at 12:33 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `juego_preguntas_respuestas`
--

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('a','e','ur') NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `fecha_creacion` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`id`, `nombre_completo`, `fecha_nacimiento`, `genero`, `email`, `usuario`, `password`, `rol`, `foto_perfil`, `pais`, `ciudad`, `fecha_creacion`) VALUES
(1, 'Juan Pérez', '1990-05-10', 'Masculino', 'juan.perez@example.com', 'juanperez', 'hashed_password1', 'a', 'juan.jpg', 'Argentina', 'Buenos Aires', '2024-10-01'),
(2, 'María López', '1988-11-15', 'Femenino', 'maria.lopez@example.com', 'marialopez', 'hashed_password2', 'e', 'maria.jpg', 'México', 'Ciudad de México', '2024-10-02'),
(3, 'Carlos Ruiz', '1992-04-22', 'Masculino', 'carlos.ruiz@example.com', 'carlosruiz', 'hashed_password3', 'ur', 'carlos.jpg', 'España', 'Madrid', '2024-10-03'),
(4, 'Ana García', '1995-08-30', 'Femenino', 'ana.garcia@example.com', 'anagarcia', 'hashed_password4', 'a', 'ana.jpg', 'Chile', 'Santiago', '2024-10-04'),
(5, 'Luis Fernández', '1985-02-12', 'Masculino', 'luis.fernandez@example.com', 'luisfernandez', 'hashed_password5', 'e', 'luis.jpg', 'Colombia', 'Bogotá', '2024-10-05'),
(6, 'Laura Martínez', '1993-06-18', 'Femenino', 'laura.martinez@example.com', 'lauramartinez', 'hashed_password6', 'ur', 'laura.jpg', 'Perú', 'Lima', '2024-10-06'),
(7, 'Pedro Sánchez', '1989-09-25', 'Masculino', 'pedro.sanchez@example.com', 'pedrosanchez', 'hashed_password7', 'a', 'pedro.jpg', 'Uruguay', 'Montevideo', '2024-10-07'),
(8, 'Claudia Gómez', '1991-12-05', 'Femenino', 'claudia.gomez@example.com', 'claudiagomez', 'hashed_password8', 'e', 'claudia.jpg', 'Venezuela', 'Caracas', '2024-10-08'),
(9, 'Diego Torres', '1994-03-14', 'Masculino', 'diego.torres@example.com', 'diegotorres', 'hashed_password9', 'ur', 'diego.jpg', 'Argentina', 'Córdoba', '2024-10-09'),
(10, 'Sofía Ramírez', '1987-07-20', 'Femenino', 'sofia.ramirez@example.com', 'sofiaramirez', 'hashed_password10', 'a', 'sofia.jpg', 'Chile', 'Valparaíso', '2024-10-10'),
(11, 'Juan Pérez', '1990-01-15', 'Masculino', 'juan.perez@example.com', 'juanperez', 'password123', '', 'perfil.jpg', 'Argentina', 'Buenos Aires', '2024-10-17'),
(12, 'asdasdsa', '2024-10-31', 'femenino', 'asdsadsa', 'asdsad', '$2y$10$m/tsxtd/BRwSAR2/rcTmaO41GllavPoPGdhFS3w.TwPWQEgjTAsma', 'ur', '', 'asdsad', 'asdasd', '2024-10-17'),
(13, 'Emilio Puertas', '2024-10-24', 'no especificar', 'tutancamon@gmail.com', 'chiqimafia', '$2y$10$7ZCRqk9BCrZ1mNM/umUareKoFQmXjDn7IYJlTYtcl5x1dPYTl7rLi', 'ur', '', 'angola', 'ramos mejia', '2024-10-17'),
(14, 'asd', '2024-10-14', 'femenino', 'asdsad', 'asdddd', '$2y$10$YDiGXwQqnKkVgKrSqMM6aOwnzLS2cbNMC8sHub9ZcJ2WqJp.t8zJG', 'ur', '', 'aa', 'aa', '2024-10-19'),
(15, 'asdaaa', '2024-10-14', 'femenino', 'asdsadaaaaaaaaaaaa', 'asddddssss', '$2y$10$oS4OSk.kPq2aRSLmOLWzte7rx3A15ElDVoCOSVoR5BfNRQVuMeCsC', 'ur', 'Array', 'aa', 'aa', '2024-10-19'),
(16, 'LEONIDAS', '2024-10-02', 'masculino', 'sambayonyyy@capomaster.com', 'zambazambayooo', '$2y$10$K25fyDqOjjUR1MbbF9SodOA0MXNs5p7bQRCeH4lymJoCZuMAQPa6S', 'ur', 'imagen_dado_4.png', '111', '1111', '2024-10-19'),
(17, 'asdsad', '2024-10-25', '', 'admin1@admin1.com', 'admin1', '$2y$10$VHZxj3jXIBraXFDXHLNHlO3yoXbloIzvmEpBSHWzStpG4kAM.HpEi', 'ur', '', 'a', 'a', '2024-10-19'),
(18, 'asdsadsad', '2024-10-23', '', 'sjkaodhsaiujhdgbaskd', 'admin2', 'admin2', 'ur', '', 'sadsad', 'asdsad', '2024-10-19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

