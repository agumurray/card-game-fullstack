-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-04-2025 a las 00:19:36
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
-- Base de datos: `seminariophp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `atributo`
--

CREATE TABLE `atributo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `atributo`
--

INSERT INTO `atributo` (`id`, `nombre`) VALUES
(1, 'Fuego'),
(2, 'Agua'),
(3, 'Tierra'),
(4, 'Normal'),
(5, 'Volador'),
(6, 'Piedra'),
(7, 'Planta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carta`
--

CREATE TABLE `carta` (
  `id` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `ataque` int(11) NOT NULL,
  `ataque_nombre` varchar(40) NOT NULL,
  `imagen` blob DEFAULT NULL,
  `atributo_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `carta`
--

INSERT INTO `carta` (`id`, `nombre`, `ataque`, `ataque_nombre`, `imagen`, `atributo_id`) VALUES
(1, 'Charizard', 84, 'Lanzallamas', NULL, 1),
(2, 'Blastoise', 83, 'Hidrobomba', NULL, 2),
(3, 'Arcanine', 110, 'Colmillo Ígneo', NULL, 1),
(4, 'Golem', 120, 'Avalancha', NULL, 6),
(5, 'Pidgeot', 80, 'Tornado', NULL, 5),
(6, 'Sandslash', 100, 'Terremoto', NULL, 3),
(7, 'Rapidash', 105, 'Patada Ígnea', NULL, 1),
(8, 'Poliwrath', 95, 'Surf', NULL, 2),
(9, 'Kabutops', 115, 'Corte', NULL, 6),
(10, 'Dugtrio', 100, 'Excavar', NULL, 3),
(11, 'Tauros', 100, 'Embate', NULL, 4),
(12, 'Aerodactyl', 105, 'Pico Taladro', NULL, 5),
(13, 'Kingler', 130, 'Martillazo', NULL, 2),
(14, 'Ninetales', 81, 'Ascuas', NULL, 1),
(15, 'Marowak', 80, 'Huesomerang', NULL, 3),
(16, 'Dodrio', 110, 'Triataque', NULL, 5),
(17, 'Omastar', 90, 'Pistola Agua', NULL, 2),
(18, 'Rhydon', 130, 'Golpe Roca', NULL, 6),
(19, 'Farfetchd', 90, 'Corte', NULL, 4),
(20, 'Lapras', 85, 'Rayo Hielo', NULL, 2),
(21, 'Flareon', 130, 'Sofoco', NULL, 1),
(22, 'Kabuto', 80, 'Aqua Jet', NULL, 6),
(23, 'Persian', 70, 'Garra Rápida', NULL, 4),
(24, 'Fearow', 90, 'Ataque Aéreo', NULL, 5),
(25, 'Onix', 45, 'Lanzarrocas', NULL, 6),
(26, 'Venusaur', 82, 'Rayo Solar', NULL, 7),
(27, 'Victreebel', 105, 'Hoja Afilada', NULL, 7),
(28, 'Vileplume', 90, 'Danza Pétalo', NULL, 7),
(29, 'Tangela', 100, 'Latigazo', NULL, 7),
(30, 'Exeggutor', 95, 'Bomba Germen', NULL, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gana_a`
--

CREATE TABLE `gana_a` (
  `atributo_id` int(11) NOT NULL,
  `atributo_id2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `gana_a`
--

INSERT INTO `gana_a` (`atributo_id`, `atributo_id2`) VALUES
(1, 3),
(1, 6),
(2, 1),
(3, 6),
(5, 4),
(6, 2),
(7, 3),
(7, 6),
(7, 2),
(1, 7),
(5, 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jugada`
--

CREATE TABLE `jugada` (
  `id` int(11) NOT NULL,
  `partida_id` int(11) NOT NULL,
  `carta_id_a` int(11) NOT NULL,
  `carta_id_b` int(11) NOT NULL,
  `el_usuario` enum('gano','perdio','empato') NOT NULL DEFAULT 'empato'
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mazo`
--

CREATE TABLE `mazo` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `mazo`
--

INSERT INTO `mazo` (`id`, `usuario_id`, `nombre`) VALUES
(1, 1, 'Mazo A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mazo_carta`
--

CREATE TABLE `mazo_carta` (
  `id` int(11) NOT NULL,
  `carta_id` int(11) NOT NULL,
  `mazo_id` int(11) NOT NULL,
  `estado` enum('en_mazo','descartado','en_mano') NOT NULL DEFAULT 'en_mazo'
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `mazo_carta`
--

INSERT INTO `mazo_carta` (`id`, `carta_id`, `mazo_id`, `estado`) VALUES
(1, 1, 1, 'en_mazo'),
(2, 3, 1, 'en_mazo'),
(3, 4, 1, 'en_mazo'),
(4, 20, 1, 'en_mazo'),
(5, 25, 1, 'en_mazo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `partida`
--

CREATE TABLE `partida` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `el_usuario` enum('gano','perdio','empato') DEFAULT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `mazo_id` int(11) NOT NULL,
  `estado` enum('en_curso','finalizada') NOT NULL DEFAULT 'en_curso'
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `password` varchar(300) NOT NULL,
  `token` varchar(128) DEFAULT NULL,
  `vencimiento_token` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `usuario`, `password`, `token`, `vencimiento_token`) VALUES
(1, 'server', 'server', '', '', '2025-03-03 01:24:43');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `atributo`
--
ALTER TABLE `atributo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carta_id_idx` (`id`);

--
-- Indices de la tabla `carta`
--
ALTER TABLE `carta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `atributo_id_idx` (`atributo_id`);

--
-- Indices de la tabla `gana_a`
--
ALTER TABLE `gana_a`
  ADD KEY `atributo_id_idx` (`atributo_id`),
  ADD KEY `atributo_id2_idx` (`atributo_id2`);

--
-- Indices de la tabla `jugada`
--
ALTER TABLE `jugada`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `partida_id_idx` (`partida_id`),
  ADD KEY `carta_id_idx` (`carta_id_a`),
  ADD KEY `carta_id_idxx` (`carta_id_b`);

--
-- Indices de la tabla `mazo`
--
ALTER TABLE `mazo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id_idx` (`usuario_id`);

--
-- Indices de la tabla `mazo_carta`
--
ALTER TABLE `mazo_carta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carta_id_idx` (`carta_id`),
  ADD KEY `mazo_id_idx` (`mazo_id`);

--
-- Indices de la tabla `partida`
--
ALTER TABLE `partida`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id_idx` (`usuario_id`),
  ADD KEY `mazo_id_idx` (`mazo_id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `atributo`
--
ALTER TABLE `atributo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `carta`
--
ALTER TABLE `carta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `jugada`
--
ALTER TABLE `jugada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mazo`
--
ALTER TABLE `mazo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `mazo_carta`
--
ALTER TABLE `mazo_carta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `partida`
--
ALTER TABLE `partida`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carta`
--
ALTER TABLE `carta`
  ADD CONSTRAINT `carta_ibfk_1` FOREIGN KEY (`atributo_id`) REFERENCES `atributo` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `gana_a`
--
ALTER TABLE `gana_a`
  ADD CONSTRAINT `gana_a_ibfk_1` FOREIGN KEY (`atributo_id`) REFERENCES `atributo` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `gana_a_ibfk_2` FOREIGN KEY (`atributo_id2`) REFERENCES `atributo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `jugada`
--
ALTER TABLE `jugada`
  ADD CONSTRAINT `jugada_ibfk_1` FOREIGN KEY (`partida_id`) REFERENCES `partida` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `jugada_ibfk_2` FOREIGN KEY (`carta_id_a`) REFERENCES `carta` (`id`),
  ADD CONSTRAINT `jugada_ibfk_3` FOREIGN KEY (`carta_id_b`) REFERENCES `carta` (`id`);

--
-- Filtros para la tabla `mazo`
--
ALTER TABLE `mazo`
  ADD CONSTRAINT `usuario_id_idx` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`);

--
-- Filtros para la tabla `mazo_carta`
--
ALTER TABLE `mazo_carta`
  ADD CONSTRAINT `mazo_carta_ibfk_2` FOREIGN KEY (`mazo_id`) REFERENCES `mazo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mazo_carta_ibfk_3` FOREIGN KEY (`carta_id`) REFERENCES `carta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `partida`
--
ALTER TABLE `partida`
  ADD CONSTRAINT `partida_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `partida_ibfk_2` FOREIGN KEY (`mazo_id`) REFERENCES `mazo` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
