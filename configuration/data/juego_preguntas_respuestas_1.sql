-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-10-2024 a las 01:01:24
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
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `color` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id`, `descripcion`, `color`) VALUES
(1, 'paises', 'azul'),
(2, 'Geografía', 'marron'),
(3, 'Astronomía', 'celeste'),
(4, 'Literatura', 'amarillo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida`
--

CREATE TABLE `partida` (
  `id` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `fecha` datetime NOT NULL,
  `idUsuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `partida`
--

INSERT INTO `partida` (`id`, `estado`, `fecha`, `idUsuario`) VALUES
(10, 'inactivo', '2024-10-25 00:45:05', 10),
(11, 'inactivo', '2024-10-25 00:48:33', 10),
(12, 'inactivo', '2024-10-25 00:53:02', 10),
(13, 'inactivo', '2024-10-25 00:55:59', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pregunta`
--

CREATE TABLE `pregunta` (
  `id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `estado` varchar(50) NOT NULL,
  `idCategoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pregunta`
--

INSERT INTO `pregunta` (`id`, `descripcion`, `estado`, `idCategoria`) VALUES
(1, '¿Cuál es la capital de Francia?', 'activa', 1),
(2, '¿Cuál es el planeta más cercano al Sol?', 'activa', 3),
(3, '¿Quién escribió \"Cien años de soledad\"?', 'activa', 4),
(4, '¿Cuál es el río más largo del mundo?', 'activa', 1),
(5, '¿En qué continente se encuentra el desierto del Sahara?', 'activa', 1),
(6, '¿Qué país tiene la mayor cantidad de islas en el mundo?', 'activa', 1),
(7, '¿Qué planeta es conocido como el \"planeta rojo\"?', 'activa', 2),
(8, '¿Cuántas lunas tiene la Tierra?', 'activa', 2),
(9, '¿Qué planeta es el más grande del sistema solar?', 'activa', 2),
(10, '¿Quién es el autor de \"Don Quijote de la Mancha\"?', 'activa', 3),
(11, '¿En qué siglo se escribió \"La Odisea\" de Homero?', 'activa', 3),
(12, '¿Cuál de los siguientes es un libro escrito por Gabriel García Márquez?', 'activa', 3),
(13, '¿Quién escribió \"Crimen y Castigo\"?', 'activa', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `respuesta`
--

CREATE TABLE `respuesta` (
  `id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `esCorrecta` int(11) NOT NULL,
  `idPregunta` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `respuesta`
--

INSERT INTO `respuesta` (`id`, `descripcion`, `esCorrecta`, `idPregunta`) VALUES
(1, 'París', 1, 1),
(2, 'Madrid', 0, 1),
(3, 'Berlín', 0, 1),
(4, 'Mercurio', 1, 2),
(5, 'Venus', 0, 2),
(6, 'Tierra', 0, 2),
(7, 'Gabriel García Márquez', 1, 3),
(8, 'Pablo Neruda', 0, 3),
(9, 'Julio Cortázar', 0, 3),
(10, 'Amazonas', 1, 4),
(11, 'Nilo', 0, 4),
(12, 'Misisipi', 0, 4),
(13, 'África', 1, 5),
(14, 'Asia', 0, 5),
(15, 'América del Sur', 0, 5),
(16, 'Suecia', 1, 6),
(17, 'Japón', 0, 6),
(18, 'Canadá', 0, 6),
(19, 'Marte', 1, 7),
(20, 'Júpiter', 0, 7),
(21, 'Venus', 0, 7),
(22, '1', 1, 8),
(23, '2', 0, 8),
(24, '3', 0, 8),
(25, 'Júpiter', 1, 9),
(26, 'Saturno', 0, 9),
(27, 'Neptuno', 0, 9),
(28, 'Miguel de Cervantes', 1, 10),
(29, 'Federico García Lorca', 0, 10),
(30, 'Mario Vargas Llosa', 0, 10),
(31, 'Siglo VIII a.C.', 1, 11),
(32, 'Siglo V a.C.', 0, 11),
(33, 'Siglo III d.C.', 0, 11),
(34, 'Cien años de soledad', 1, 12),
(35, 'Pedro Páramo', 0, 12),
(36, 'El amor en los tiempos del cólera', 0, 12),
(37, 'Fiódor Dostoyevski', 1, 13),
(38, 'Lev Tolstói', 0, 13),
(39, 'Franz Kafka', 0, 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tienen`
--

CREATE TABLE `tienen` (
  `idPartida` int(11) NOT NULL,
  `idPregunta` int(11) NOT NULL,
  `puntaje` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tienen`
--

INSERT INTO `tienen` (`idPartida`, `idPregunta`, `puntaje`) VALUES
(10, 1, 1),
(10, 3, 1),
(12, 11, 1),
(13, 1, 1),
(13, 6, 1),
(13, 10, 1),
(13, 11, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `usuario` varchar(255) DEFAULT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` enum('a','e','ur') NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `pais` varchar(50) DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `fecha_creacion` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre_completo`, `usuario`, `fecha_nacimiento`, `genero`, `email`, `password`, `rol`, `foto_perfil`, `pais`, `ciudad`, `fecha_creacion`) VALUES
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
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `partida`
--
ALTER TABLE `partida`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario` (`idUsuario`);

--
-- Indices de la tabla `pregunta`
--
ALTER TABLE `pregunta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_idCategoria` (`idCategoria`);

--
-- Indices de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_idPreguntaEnRta` (`idPregunta`);

--
-- Indices de la tabla `tienen`
--
ALTER TABLE `tienen`
  ADD PRIMARY KEY (`idPartida`,`idPregunta`),
  ADD KEY `fk_idPregunta` (`idPregunta`);

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
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `partida`
--
ALTER TABLE `partida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `pregunta`
--
ALTER TABLE `pregunta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `partida`
--
ALTER TABLE `partida`
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `pregunta`
--
ALTER TABLE `pregunta`
  ADD CONSTRAINT `fk_idCategoria` FOREIGN KEY (`idCategoria`) REFERENCES `categoria` (`id`);

--
-- Filtros para la tabla `respuesta`
--
ALTER TABLE `respuesta`
  ADD CONSTRAINT `fk_idPreguntaEnRta` FOREIGN KEY (`idPregunta`) REFERENCES `pregunta` (`id`);

--
-- Filtros para la tabla `tienen`
--
ALTER TABLE `tienen`
  ADD CONSTRAINT `fk_idPartida` FOREIGN KEY (`idPartida`) REFERENCES `partida` (`id`),
  ADD CONSTRAINT `fk_idPregunta` FOREIGN KEY (`idPregunta`) REFERENCES `pregunta` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
