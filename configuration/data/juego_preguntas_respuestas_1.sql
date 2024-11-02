-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-10-2024 a las 23:15:25
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
(1, 'Geografia', 'azul'),
(2, 'Deporte', 'Verde'),
(3, 'Historia', 'Amarillo'),
(4, 'Videojuegos', 'Violeta');

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
(1, 'inactivo', '2024-10-14 16:48:41', 1),
(2, 'inactivo', '2024-09-16 16:48:41', 2),
(3, 'inactivo', '2024-08-07 16:48:41', 3),
(4, 'inactivo', '2024-10-12 16:48:41', 4),
(5, 'inactivo', '2024-10-13 16:48:41', 5),
(6, 'inactivo', '2024-09-27 16:48:41', 6),
(7, 'inactivo', '2024-10-07 16:48:41', 7),
(8, 'inactivo', '2024-10-15 16:48:41', 8),
(9, 'inactivo', '2024-10-21 16:48:41', 9),
(10, 'inactivo', '2024-10-28 16:48:41', 10),
(11, 'inactivo', '2024-08-20 16:48:41', 1),
(12, 'inactivo', '2024-08-12 16:48:41', 2),
(13, 'inactivo', '2024-10-25 16:48:41', 3),
(14, 'inactivo', '2024-09-04 16:48:41', 4),
(15, 'inactivo', '2024-08-05 16:48:41', 5),
(16, 'inactivo', '2024-08-09 16:48:41', 6),
(17, 'inactivo', '2024-08-28 16:48:41', 7),
(18, 'inactivo', '2024-08-23 16:48:41', 8),
(19, 'inactivo', '2024-08-27 16:48:41', 9),
(20, 'inactivo', '2024-10-06 16:48:41', 10),
(21, 'inactivo', '2024-10-10 16:48:41', 1),
(22, 'inactivo', '2024-10-04 16:48:41', 2),
(23, 'inactivo', '2024-08-18 16:48:41', 3),
(24, 'inactivo', '2024-10-15 16:48:41', 4),
(25, 'inactivo', '2024-09-23 16:48:41', 5),
(26, 'inactivo', '2024-09-09 16:48:41', 6),
(27, 'inactivo', '2024-09-05 16:48:41', 7),
(28, 'inactivo', '2024-09-27 16:48:41', 8),
(29, 'inactivo', '2024-10-29 16:48:41', 9),
(30, 'inactivo', '2024-08-04 16:48:41', 10),
(31, 'inactivo', '2024-08-21 16:48:41', 1),
(32, 'inactivo', '2024-10-29 16:48:41', 2),
(33, 'inactivo', '2024-08-27 16:48:41', 3),
(34, 'inactivo', '2024-09-12 16:48:41', 4),
(35, 'inactivo', '2024-09-11 16:48:41', 5),
(36, 'inactivo', '2024-10-18 16:48:41', 6),
(37, 'inactivo', '2024-10-28 16:48:41', 7),
(38, 'inactivo', '2024-08-28 16:48:41', 9),
(39, 'inactivo', '2024-09-18 16:48:41', 10),
(40, 'inactivo', '2024-10-10 16:48:41', 1),
(41, 'inactivo', '2024-08-25 16:48:41', 2),
(42, 'inactivo', '2024-10-29 16:48:41', 3),
(43, 'inactivo', '2024-08-16 16:48:41', 4),
(44, 'inactivo', '2024-10-17 16:48:41', 5),
(45, 'inactivo', '2024-10-11 16:48:41', 6),
(46, 'inactivo', '2024-09-02 16:48:41', 7),
(47, 'inactivo', '2024-09-05 16:48:41', 8),
(48, 'inactivo', '2024-10-18 16:48:41', 9),
(49, 'inactivo', '2024-09-01 17:03:22', 1),
(50, 'inactivo', '2024-08-18 17:03:22', 1),
(73, 'inactivo', '2024-10-24 17:03:22', 2),
(74, 'inactivo', '2024-08-09 17:03:22', 2),
(75, 'inactivo', '2024-09-29 17:03:22', 3),
(76, 'inactivo', '2024-08-02 17:03:22', 3),
(77, 'inactivo', '2024-08-06 17:03:22', 4),
(78, 'inactivo', '2024-08-25 17:03:22', 4),
(79, 'inactivo', '2024-08-16 17:03:22', 5),
(80, 'inactivo', '2024-08-03 17:03:22', 5),
(81, 'inactivo', '2024-09-23 17:03:22', 6),
(82, 'inactivo', '2024-10-18 17:03:22', 6),
(83, 'inactivo', '2024-09-22 17:03:22', 7),
(84, 'inactivo', '2024-08-28 17:03:22', 7),
(85, 'inactivo', '2024-10-07 17:03:22', 8),
(86, 'inactivo', '2024-10-15 17:03:22', 8),
(87, 'inactivo', '2024-10-24 17:03:22', 9),
(88, 'inactivo', '2024-08-14 17:03:22', 9),
(89, 'inactivo', '2024-10-25 17:03:22', 10),
(90, 'inactivo', '2024-08-23 17:03:22', 10),
(91, 'inactivo', '2024-09-06 17:03:22', 1),
(92, 'inactivo', '2024-08-24 17:03:22', 2),
(93, 'inactivo', '2024-08-07 17:03:22', 3),
(94, 'inactivo', '2024-09-20 17:03:22', 4),
(95, 'inactivo', '2024-09-22 17:03:22', 5),
(96, 'inactivo', '2024-08-20 17:03:22', 6),
(97, 'inactivo', '2024-08-29 17:03:22', 7),
(98, 'inactivo', '2024-10-24 17:03:22', 8),
(99, 'inactivo', '2024-10-03 17:03:22', 9),
(100, 'inactivo', '2024-10-05 17:03:22', 10),
(101, 'inactivo', '2024-09-13 17:03:22', 1),
(102, 'inactivo', '2024-08-22 17:03:22', 2),
(103, 'inactivo', '2024-10-05 17:03:22', 3),
(104, 'inactivo', '2024-10-22 17:03:22', 4),
(105, 'inactivo', '2024-09-03 17:03:22', 5),
(106, 'inactivo', '2024-08-09 17:03:22', 6),
(107, 'inactivo', '2024-08-31 17:03:22', 7),
(108, 'inactivo', '2024-09-05 17:03:22', 8),
(109, 'inactivo', '2024-10-25 17:03:22', 9),
(110, 'inactivo', '2024-09-20 17:03:22', 10),
(111, 'inactivo', '2024-10-24 17:03:22', 1),
(112, 'inactivo', '2024-10-28 17:03:22', 2),
(113, 'inactivo', '2024-08-12 17:03:22', 3),
(114, 'inactivo', '2024-09-27 17:03:22', 4),
(115, 'inactivo', '2024-10-12 17:03:22', 5),
(116, 'inactivo', '2024-08-11 17:03:22', 6),
(117, 'inactivo', '2024-08-12 17:03:22', 7),
(118, 'inactivo', '2024-08-26 17:03:22', 8),
(119, 'inactivo', '2024-08-03 17:03:22', 9),
(120, 'inactivo', '2024-08-26 17:03:22', 10);

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
(1, '¿Cuál es el país más grande del mundo?', 'activa', 1),
(2, '¿Qué deporte se juega en una cancha de césped con 11 jugadores?', 'activa', 2),
(3, '¿Quién fue el primer presidente de los Estados Unidos?', 'activa', 3),
(4, '¿En qué año se lanzó el primer videojuego comercial?', 'activa', 4),
(5, '¿Cuál es el río más largo del mundo?', 'activa', 1),
(6, '¿Qué deporte es conocido como el rey de los deportes?', 'activa', 2),
(7, '¿Quién descubrió América en 1492?', 'activa', 3),
(8, '¿Cuál es el personaje principal de \"The Legend of Zelda\"?', 'activa', 4),
(9, '¿Cuál es la capital de Japón?', 'activa', 1),
(10, '¿En qué deporte se utiliza una raqueta?', 'activa', 2),
(11, '¿Qué imperio fue conocido como el más grande de la historia?', 'activa', 3),
(12, '¿Cuál es el juego más vendido de todos los tiempos?', 'activa', 4),
(13, '¿Qué continente es conocido como el \"viejo mundo\"?', 'activa', 1),
(14, '¿Quién ganó el Balón de Oro en 2021?', 'activa', 2),
(15, '¿En qué año terminó la Segunda Guerra Mundial?', 'activa', 3),
(16, '¿Cuál es el nombre del protagonista de \"Super Mario\"?', 'activa', 4),
(17, '¿Cuál es el país con más habitantes del mundo?', 'activa', 1),
(18, '¿Qué deporte se juega en los Juegos Olímpicos de invierno?', 'activa', 2),
(19, '¿Qué famoso científico desarrolló la teoría de la relatividad?', 'activa', 3),
(20, '¿Qué juego de video se lanzó en 1985 y revolucionó la industria?', 'activa', 4);

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
(1, 'Madrid', 1, 1),
(2, 'Barcelona', 0, 1),
(3, 'Valencia', 0, 1),
(4, 'Sevilla', 0, 1),
(5, 'Voleibol', 1, 2),
(6, 'Fútbol', 0, 2),
(7, 'Tenis', 0, 2),
(8, 'Baloncesto', 0, 2),
(9, 'Miguel Ángel', 1, 3),
(10, 'Leonardo da Vinci', 0, 3),
(11, 'Pablo Picasso', 0, 3),
(12, 'Salvador Dalí', 0, 3),
(13, '1972', 1, 4),
(14, '1980', 0, 4),
(15, '1990', 0, 4),
(16, '2000', 0, 4),
(17, 'Amazonas', 1, 5),
(18, 'Nilo', 0, 5),
(19, 'Yangtsé', 0, 5),
(20, 'Misisipi', 0, 5),
(21, 'Alemania', 1, 6),
(22, 'Brasil', 0, 6),
(23, 'Argentina', 0, 6),
(24, 'Italia', 0, 6),
(25, 'Gabriel García Márquez', 1, 7),
(26, 'Pablo Neruda', 0, 7),
(27, 'Julio Cortázar', 0, 7),
(28, 'Jorge Luis Borges', 0, 7),
(29, 'Pong', 1, 8),
(30, 'Tetris', 0, 8),
(31, 'Pac-Man', 0, 8),
(32, 'Super Mario Bros', 0, 8),
(33, 'Europa', 1, 9),
(34, 'América', 0, 9),
(35, 'Asia', 0, 9),
(36, 'África', 0, 9),
(37, 'Golf', 1, 10),
(38, 'Fútbol', 0, 10),
(39, 'Béisbol', 0, 10),
(40, 'Rugby', 0, 10),
(41, 'Hamlet', 1, 11),
(42, 'Romeo y Julieta', 0, 11),
(43, 'Otelo', 0, 11),
(44, 'Macbeth', 0, 11),
(45, 'Minecraft', 1, 12),
(46, 'FIFA', 0, 12),
(47, 'Call of Duty', 0, 12),
(48, 'The Legend of Zelda', 0, 12),
(49, 'Everest', 1, 13),
(50, 'K2', 0, 13),
(51, 'Makalu', 0, 13),
(52, 'Kangchenjunga', 0, 13),
(53, 'Tenis', 1, 14),
(54, 'Béisbol', 0, 14),
(55, 'Fútbol', 0, 14),
(56, 'Baloncesto', 0, 14),
(57, 'George Washington', 1, 15),
(58, 'Thomas Jefferson', 0, 15),
(59, 'Abraham Lincoln', 0, 15),
(60, 'Theodore Roosevelt', 0, 15),
(61, 'PlayStation 2', 1, 16),
(62, 'Xbox 360', 0, 16),
(63, 'Wii', 0, 16),
(64, 'GameCube', 0, 16),
(65, 'Pacífico', 1, 17),
(66, 'Atlántico', 0, 17),
(67, 'Índico', 0, 17),
(68, 'Ártico', 0, 17),
(69, 'Baloncesto', 1, 18),
(70, 'Fútbol', 0, 18),
(71, 'Hockey', 0, 18),
(72, 'Rugby', 0, 18),
(73, 'Miguel de Cervantes', 1, 19),
(74, 'Gabriel García Márquez', 0, 19),
(75, 'Pablo Neruda', 0, 19),
(76, 'Jorge Luis Borges', 0, 19),
(77, 'Mario Kart', 1, 20),
(78, 'Need for Speed', 0, 20),
(79, 'Gran Turismo', 0, 20),
(80, 'Forza Horizon', 0, 20);

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
(1, 3, 1),
(1, 4, 0),
(1, 10, 1),
(2, 1, 0),
(2, 2, 1),
(2, 3, 1),
(2, 4, 1),
(2, 20, 1),
(3, 1, 1),
(3, 2, 0),
(3, 11, 1),
(3, 18, 1),
(3, 19, 1),
(4, 1, 1),
(4, 2, 1),
(4, 3, 1),
(4, 4, 0),
(5, 1, 1),
(5, 2, 0),
(5, 3, 1),
(6, 1, 0),
(6, 2, 1),
(6, 3, 1),
(6, 4, 1),
(6, 5, 1),
(6, 7, 1),
(6, 8, 1),
(6, 13, 1),
(6, 17, 1),
(7, 1, 1),
(7, 3, 1),
(7, 4, 0),
(8, 1, 0),
(8, 2, 1),
(8, 7, 1),
(8, 9, 1),
(8, 10, 1),
(8, 11, 1),
(9, 1, 1),
(9, 2, 1),
(9, 3, 1),
(9, 4, 0),
(10, 3, 1),
(10, 11, 0),
(10, 12, 1),
(10, 18, 1),
(10, 19, 1),
(10, 20, 1),
(11, 2, 1),
(11, 3, 0),
(11, 4, 1),
(12, 1, 1),
(12, 2, 0),
(12, 3, 1),
(12, 4, 1),
(13, 2, 1),
(13, 3, 1),
(13, 4, 0),
(14, 1, 1),
(14, 2, 0),
(14, 4, 1),
(15, 1, 1),
(15, 2, 1),
(15, 3, 1),
(15, 4, 0),
(16, 1, 1),
(16, 2, 1),
(16, 3, 0),
(16, 4, 1),
(17, 1, 1),
(17, 2, 1),
(17, 3, 0),
(17, 4, 1),
(18, 1, 0),
(18, 3, 1),
(18, 4, 1),
(19, 1, 1),
(19, 2, 1),
(19, 3, 1),
(19, 4, 0),
(20, 1, 1),
(20, 3, 0),
(20, 4, 1),
(21, 1, 0),
(21, 2, 1),
(21, 3, 1),
(21, 4, 1),
(22, 1, 1),
(22, 2, 1),
(22, 3, 1),
(22, 4, 0),
(23, 2, 1),
(23, 3, 0),
(23, 4, 1),
(24, 1, 1),
(24, 4, 0),
(25, 1, 1),
(25, 2, 0),
(25, 3, 1),
(26, 1, 1),
(26, 2, 0),
(26, 3, 1),
(26, 4, 1),
(27, 1, 1),
(27, 2, 1),
(27, 4, 0),
(28, 1, 1),
(28, 2, 0),
(28, 3, 1),
(28, 4, 1),
(29, 1, 1),
(29, 2, 1),
(29, 3, 1),
(29, 4, 0),
(30, 1, 1),
(30, 2, 0),
(30, 3, 1),
(31, 1, 1),
(31, 2, 0),
(31, 3, 1),
(31, 4, 1),
(32, 1, 1),
(32, 3, 0),
(33, 1, 1),
(33, 2, 0),
(33, 4, 1),
(34, 1, 1),
(34, 2, 1),
(34, 4, 0),
(35, 1, 1),
(35, 2, 1),
(35, 3, 1),
(35, 4, 0),
(36, 2, 0),
(36, 4, 1),
(37, 1, 1),
(37, 2, 1),
(37, 4, 0),
(38, 1, 1),
(38, 3, 1),
(38, 4, 0),
(39, 1, 1),
(39, 2, 1),
(39, 3, 0),
(39, 4, 1),
(40, 1, 1),
(40, 2, 1),
(40, 3, 0),
(41, 1, 1),
(41, 2, 0),
(41, 3, 1),
(41, 4, 1),
(42, 1, 1),
(42, 2, 1),
(42, 4, 0),
(43, 1, 1),
(43, 2, 1),
(43, 3, 1),
(43, 4, 0),
(44, 1, 1),
(44, 2, 1),
(44, 3, 1),
(44, 4, 0),
(45, 1, 1),
(45, 3, 0),
(45, 4, 1),
(46, 1, 1),
(46, 2, 1),
(46, 3, 0),
(46, 4, 1),
(47, 1, 0),
(47, 2, 1),
(47, 3, 1),
(47, 4, 1),
(48, 2, 1),
(48, 3, 1),
(48, 4, 1),
(48, 6, 1),
(48, 10, 1),
(48, 12, 1),
(48, 14, 0),
(49, 1, 1),
(49, 15, 1),
(49, 20, 0),
(50, 1, 1),
(50, 2, 1),
(50, 4, 0),
(50, 6, 1),
(50, 7, 1),
(50, 8, 1),
(50, 9, 1),
(50, 10, 1),
(50, 12, 1),
(50, 20, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
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
  `fecha_creacion` date NOT NULL DEFAULT curdate(),
  `esta_verificado` tinyint(1) NOT NULL DEFAULT 0,
  `token_verificacion` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre_completo`, `fecha_nacimiento`, `genero`, `email`, `usuario`, `password`, `rol`, `foto_perfil`, `pais`, `ciudad`, `fecha_creacion`, `esta_verificado`, `token_verificacion`) VALUES
(1, 'Juan Pérez', '1990-05-10', 'Masculino', 'juan.perez@example.com', 'juanperez', 'hashed_password1', 'a', 'juan.jpg', 'Argentina', 'Buenos Aires', '2024-10-01', 1, NULL),
(2, 'María López', '1988-11-15', 'Femenino', 'maria.lopez@example.com', 'marialopez', 'hashed_password2', 'e', 'maria.jpg', 'México', 'Ciudad de México', '2024-10-02', 1, NULL),
(3, 'Carlos Ruiz', '1992-04-22', 'Masculino', 'carlos.ruiz@example.com', 'carlosruiz', 'hashed_password3', 'ur', 'carlos.jpg', 'España', 'Madrid', '2024-10-03', 1, NULL),
(4, 'Ana García', '1995-08-30', 'Femenino', 'ana.garcia@example.com', 'anagarcia', 'hashed_password4', 'a', 'ana.jpg', 'Chile', 'Santiago', '2024-10-04', 1, NULL),
(5, 'Luis Fernández', '1985-02-12', 'Masculino', 'luis.fernandez@example.com', 'luisfernandez', 'hashed_password5', 'e', 'luis.jpg', 'Colombia', 'Bogotá', '2024-10-05', 1, NULL),
(6, 'Laura Martínez', '1993-06-18', 'Femenino', 'laura.martinez@example.com', 'lauramartinez', 'hashed_password6', 'ur', 'laura.jpg', 'Perú', 'Lima', '2024-10-06', 1, NULL),
(7, 'Pedro Sánchez', '1989-09-25', 'Masculino', 'pedro.sanchez@example.com', 'pedrosanchez', 'hashed_password7', 'a', 'pedro.jpg', 'Uruguay', 'Montevideo', '2024-10-07', 1, NULL),
(8, 'Claudia Gómez', '1991-12-05', 'Femenino', 'claudia.gomez@example.com', 'claudiagomez', 'hashed_password8', 'e', 'claudia.jpg', 'Venezuela', 'Caracas', '2024-10-08', 1, NULL),
(9, 'Diego Torres', '1994-03-14', 'Masculino', 'diego.torres@example.com', 'diegotorres', 'hashed_password9', 'ur', 'diego.jpg', 'Argentina', 'Córdoba', '2024-10-09', 1, NULL),
(10, 'Sofía Ramírez', '1987-07-20', 'Femenino', 'sofia.ramirez@example.com', 'sofiaramirez', 'hashed_password10', 'a', 'sofia.jpg', 'Chile', 'Valparaíso', '2023-10-10', 1, NULL),
(11, 'Juan Pérez', '1990-05-15', 'masculino', 'juan.perez@example.com', 'juanp', 'hashed_password', 'ur', 'juan_foto.jpg', 'México', 'Ciudad de México', '2024-10-17', 1, NULL);

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
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT de la tabla `pregunta`
--
ALTER TABLE `pregunta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `respuesta`
--
ALTER TABLE `respuesta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
