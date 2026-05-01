-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-05-2026 a las 04:06:34
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `b_servicio`
--

INSERT INTO `b_servicio` (`ser_id`, `ser_nom`, `ser_dur_prom`, `ser_est`) VALUES
(1, 'SERTAMEN', 100, 1),
(2, 'Caja', 5, 1),
(3, 'Atención al cliente', 10, 1),
(4, 'Reclamos', 15, 1),
(5, 'Préstamos', 20, 1),
(6, 'Cuentas nuevas', 12, 1),
(7, 'Tarjetas', 8, 1),
(8, 'Consultas generales', 6, 1),
(9, 'Transferencias', 7, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `b_turno`
--

CREATE TABLE `b_turno` (
  `tur_id` int(11) NOT NULL,
  `tur_usu_id` int(11) NOT NULL,
  `tur_ser_id` int(11) NOT NULL,
  `tur_n_tur` int(11) NOT NULL,
  `tur_est` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `b_usuario`
--

CREATE TABLE `b_usuario` (
  `usu_id` int(11) NOT NULL,
  `usu_nom` varchar(50) NOT NULL,
  `usu_pass` int(11) NOT NULL,
  `usu_email` varchar(100) NOT NULL,
  `usu_rol` varchar(20) NOT NULL,
  `usu_est` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  MODIFY `ser_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `b_turno`
--
ALTER TABLE `b_turno`
  MODIFY `tur_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `b_usuario`
--
ALTER TABLE `b_usuario`
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT;

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
