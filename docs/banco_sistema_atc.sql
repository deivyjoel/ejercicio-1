-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-05-2026 a las 09:17:34
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `banco_sistema_atc`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `b_servicio`
--

CREATE TABLE `b_servicio` (
  `ser_id` int(11) NOT NULL,
  `ser_nom` varchar(100) NOT NULL,
  `ser_dur_prom` int(11) NOT NULL,
  `ser_est` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `b_servicio`
--

INSERT INTO `b_servicio` (`ser_id`, `ser_nom`, `ser_dur_prom`, `ser_est`) VALUES
(14, 'Prestamos', 10, 1),
(15, 'Reclamos', 7, 1),
(16, 'Cuentas nuevas', 8, 1),
(17, 'Tarjetas', 5, 1),
(18, 'Transferencias', 6, 1),
(19, 'Consultas generales', 5, 1),
(20, 'ÉAlo', 10, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `b_turno`
--

CREATE TABLE `b_turno` (
  `tur_id` int(11) NOT NULL,
  `tur_usu_id` int(11) NOT NULL,
  `tur_ser_id` int(11) NOT NULL,
  `tur_n_tur` int(11) NOT NULL,
  `tur_est` int(11) NOT NULL,
  `tur_fec_hor` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `tur_fec_edi` timestamp NULL DEFAULT current_timestamp(),
  `tur_fec_del` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `b_usuario`
--

CREATE TABLE `b_usuario` (
  `usu_id` int(11) NOT NULL,
  `usu_nom` varchar(50) NOT NULL,
  `usu_pass` varchar(255) NOT NULL,
  `usu_email` varchar(100) NOT NULL,
  `usu_rol` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `b_usuario`
--

INSERT INTO `b_usuario` (`usu_id`, `usu_nom`, `usu_pass`, `usu_email`, `usu_rol`) VALUES
(17, 'Dei', '$2y$10$waE49wpGXtaU8OezoXm7JuJpNRKb1PE1NTP7Qkjc7.CFzmFu7ZLKS', 'dei@gmail.com', 2),
(18, 'Dei Admin', '$2y$10$ppBkMfBj/mnJDLMxiD/Z9O4WvA64DxJKgBjrKi3nwTl8XlIG5urYy', 'deiadmin@gmail.com', 1),
(21, 'Dei2', '$2y$10$CeC0/3OMppkX5zeZDgrxv.U8jLicpGnKHIQ5Xyu2iEbRfTBaK0JL.', 'dei2@gmail.com', 2);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `b_servicio`
--
ALTER TABLE `b_servicio`
  ADD PRIMARY KEY (`ser_id`);

--
-- Indices de la tabla `b_turno`
--
ALTER TABLE `b_turno`
  ADD PRIMARY KEY (`tur_id`),
  ADD KEY `tur_usu_id` (`tur_usu_id`,`tur_ser_id`),
  ADD KEY `tur_ser_id` (`tur_ser_id`);

--
-- Indices de la tabla `b_usuario`
--
ALTER TABLE `b_usuario`
  ADD PRIMARY KEY (`usu_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `b_servicio`
--
ALTER TABLE `b_servicio`
  MODIFY `ser_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `b_turno`
--
ALTER TABLE `b_turno`
  MODIFY `tur_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `b_usuario`
--
ALTER TABLE `b_usuario`
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `b_turno`
--
ALTER TABLE `b_turno`
  ADD CONSTRAINT `b_turno_ibfk_1` FOREIGN KEY (`tur_usu_id`) REFERENCES `b_usuario` (`usu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `b_turno_ibfk_2` FOREIGN KEY (`tur_ser_id`) REFERENCES `b_servicio` (`ser_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
