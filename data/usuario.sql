-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-10-2024 a las 22:15:31
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `juego_preguntas_respuestas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('a','e','ur') NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `fecha_creacion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre_completo`, `username`, `fecha_nacimiento`, `genero`, `email`, `contrasena`, `rol`, `foto_perfil`, `pais`, `ciudad`, `fecha_creacion`) VALUES
(1, 'Juan Pérez', '@juanperez', '1990-03-15', 'M', 'juan.perez@mail.com', '12345Juan*', 'ur', 'juan_perez.jpg', 'Argentina', 'Buenos Aires', '2023-05-10'),
(2, 'María López', '@maria_lopez', '1985-07-20', 'F', 'maria.lopez@mail.com', 'maria*85', 'e', 'maria_lopez.jpg', 'México', 'Ciudad de México', '2023-06-15'),
(3, 'Carlos Fernández', '@carlos_fernandez', '1992-11-10', 'M', 'carlos.fernandez@mail.com', 'carlosFern@92', 'ur', 'carlos_fernandez.jpg', 'España', 'Madrid', '2023-07-20'),
(4, 'Ana Martínez', '@ana_martinez', '1995-01-25', 'F', 'ana.martinez@mail.com', 'AnaM@rtinez95', 'ur', 'ana_martinez.jpg', 'Chile', 'Santiago', '2023-08-05'),
(5, 'Luis García', '@luis_garcia', '1988-05-05', 'M', 'luis.garcia@mail.com', 'LuisGarc88', 'a', 'luis_garcia.jpg', 'Colombia', 'Bogotá', '2023-09-01'),
(6, 'Sofía Gómez', '@sofia_gomez', '1993-08-30', 'F', 'sofia.gomez@mail.com', 'Sofia*1234', 'ur', 'sofia_gomez.jpg', 'Perú', 'Lima', '2023-10-10'),
(7, 'Pedro Rojas', '@pedro_rojas', '1986-02-14', 'M', 'pedro.rojas@mail.com', 'PedroR86', 'e', 'pedro_rojas.jpg', 'Argentina', 'Córdoba', '2023-11-15'),
(8, 'Lucía Torres', '@lucia_torres', '1994-12-18', 'F', 'lucia.torres@mail.com', 'LuciaTorres94', 'ur', 'lucia_torres.jpg', 'Uruguay', 'Montevideo', '2023-12-20'),
(9, 'Diego Castro', '@diego_castro', '1989-06-22', 'M', 'diego.castro@mail.com', 'Diego@Castro89', 'ur', 'diego_castro.jpg', 'Paraguay', 'Asunción', '2024-01-05'),
(10, 'Marta Díaz', '@marta_diaz', '1991-04-12', 'F', 'marta.diaz@mail.com', 'MartaD91', 'ur', 'marta_diaz.jpg', 'Bolivia', 'La Paz', '2024-02-15');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
