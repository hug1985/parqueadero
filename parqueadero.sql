-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-03-2024 a las 05:01:10
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `parqueadero`
--

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `bicicletas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `bicicletas` (
`placa` varchar(10)
,`tipo` varchar(9)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `pico_placa_2y3`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `pico_placa_2y3` (
`placa` varchar(20)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros`
--

CREATE TABLE `registros` (
  `id_registros` int(11) NOT NULL,
  `placa` varchar(20) NOT NULL,
  `marca` varchar(50) NOT NULL,
  `fecha_entrada` datetime NOT NULL,
  `fecha_salida` datetime NOT NULL,
  `total_pagar` int(11) NOT NULL,
  `id_vehiculo` int(11) NOT NULL,
  `id_tarifas_vehiculos` int(11) DEFAULT NULL,
  `tipo_pago` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registros`
--

INSERT INTO `registros` (`id_registros`, `placa`, `marca`, `fecha_entrada`, `fecha_salida`, `total_pagar`, `id_vehiculo`, `id_tarifas_vehiculos`, `tipo_pago`) VALUES
(2, 'RTX306', 'kia', '2024-03-24 15:26:00', '2024-03-25 20:30:00', 72667, 3, 0, '3'),
(3, 'JDG-43C', 'yamaha', '2024-03-23 15:28:00', '2024-03-25 21:10:00', 134250, 4, 0, '5'),
(4, 'BMP152', 'Ural', '2024-03-25 05:17:00', '2024-03-25 21:14:00', 39875, 5, 0, '3'),
(12, 'RRR234', 'ford', '2023-10-26 10:37:00', '2024-03-25 22:38:00', 1515424, 11, NULL, '1'),
(13, 'DSC-56C', 'pulsar', '2024-03-23 10:41:00', '2024-03-25 22:43:00', 12507, 12, NULL, '5'),
(14, 'hgw768', 'GW', '2024-03-24 10:42:00', '2024-03-25 22:43:00', 36017, 13, NULL, '9');

--
-- Disparadores `registros`
--
DELIMITER $$
CREATE TRIGGER `after_update_fecha_entrada` AFTER UPDATE ON `registros` FOR EACH ROW BEGIN
    IF NEW.fecha_entrada <> OLD.fecha_entrada THEN
        INSERT INTO registros_historial_entrada (id_registro, fecha_entrada_anterior, fecha_entrada_nueva, fecha_actualizacion)
        VALUES (NEW.id_registros, OLD.fecha_entrada, NEW.fecha_entrada, NOW());
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_update_total_pagar` AFTER UPDATE ON `registros` FOR EACH ROW BEGIN
    IF NEW.total_pagar <> OLD.total_pagar THEN
        INSERT INTO registros_historial (id_registro, total_pagar_anterior, total_pagar_nuevo, fecha_actualizacion)
        VALUES (NEW.id_registros, OLD.total_pagar, NEW.total_pagar, NOW());
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_historial`
--

CREATE TABLE `registros_historial` (
  `id_registro_historial` int(11) NOT NULL,
  `id_registro` int(11) DEFAULT NULL,
  `total_pagar_anterior` int(11) DEFAULT NULL,
  `total_pagar_nuevo` int(11) DEFAULT NULL,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registros_historial`
--

INSERT INTO `registros_historial` (`id_registro_historial`, `id_registro`, `total_pagar_anterior`, `total_pagar_nuevo`, `fecha_actualizacion`) VALUES
(6, 12, 0, 1515424, '2024-03-26 03:38:39'),
(7, 13, 0, 12507, '2024-03-26 03:43:24'),
(8, 14, 0, 36017, '2024-03-26 03:43:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registros_historial_entrada`
--

CREATE TABLE `registros_historial_entrada` (
  `id_historial` int(11) NOT NULL,
  `id_registro` int(11) DEFAULT NULL,
  `fecha_entrada_anterior` datetime DEFAULT NULL,
  `fecha_entrada_nueva` datetime DEFAULT NULL,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `registros_historial_entrada`
--

INSERT INTO `registros_historial_entrada` (`id_historial`, `id_registro`, `fecha_entrada_anterior`, `fecha_entrada_nueva`, `fecha_actualizacion`) VALUES
(1, 6, '2024-03-24 17:23:00', '2024-03-25 21:22:00', '2024-03-26 02:22:24'),
(2, 7, '2024-03-24 17:23:00', '2024-03-25 21:22:00', '2024-03-26 02:22:24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarifas_vehiculos`
--

CREATE TABLE `tarifas_vehiculos` (
  `id_tarifas_vehiculos` int(11) NOT NULL,
  `tipo_pago` varchar(50) NOT NULL,
  `tarifa` int(11) NOT NULL,
  `tipo_vehiculo` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tarifas_vehiculos`
--

INSERT INTO `tarifas_vehiculos` (`id_tarifas_vehiculos`, `tipo_pago`, `tarifa`, `tipo_vehiculo`) VALUES
(1, 'automovil_mensualidad', 300000, 'automovil'),
(2, 'automovil_media_jornada', 5000, 'automovil'),
(3, 'automovil_por_hora', 2500, 'automovil'),
(4, 'motocicleta_mensualidad', 150000, 'motocicleta'),
(5, 'motocicleta_media_jornada', 2500, 'motocicleta'),
(6, 'motocicleta_por_hora', 1250, 'motocicleta'),
(7, 'bicicleta_mensualidad', 60000, 'bicicleta'),
(8, 'bicicleta_media_jornada', 2000, 'bicicleta'),
(9, 'bicicleta_por_hora', 1000, 'bicicleta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id_vehiculo` int(11) NOT NULL,
  `placa` varchar(10) NOT NULL,
  `marca` varchar(50) DEFAULT NULL,
  `tipo_vehiculo` varchar(255) NOT NULL,
  `id_tarifas_vehiculos` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id_vehiculo`, `placa`, `marca`, `tipo_vehiculo`, `id_tarifas_vehiculos`) VALUES
(3, 'RTX306', 'kia', 'automovil', NULL),
(4, 'JDG-43C', 'yamaha', 'motocicleta', NULL),
(5, 'BMP152', 'Ural', 'automovil', NULL),
(11, 'RRR234', 'ford', 'automovil', NULL),
(12, 'DSC-56C', 'pulsar', 'motocicleta', NULL),
(13, 'hgw768', 'GW', 'bicicleta', NULL);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_vehiculos_mensualidad`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_vehiculos_mensualidad` (
`placa` varchar(10)
,`tipo_vehiculo` varchar(255)
,`tipo_pago` varchar(50)
,`id_tarifas_vehiculos` int(11)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `bicicletas`
--
DROP TABLE IF EXISTS `bicicletas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bicicletas`  AS SELECT `vehiculos`.`placa` AS `placa`, 'bicicleta' AS `tipo` FROM `vehiculos` WHERE `vehiculos`.`tipo_vehiculo` = 'bicicleta' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `pico_placa_2y3`
--
DROP TABLE IF EXISTS `pico_placa_2y3`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `pico_placa_2y3`  AS SELECT `registros`.`placa` AS `placa` FROM `registros` WHERE cast(`registros`.`fecha_entrada` as date) = curdate() AND week(`registros`.`fecha_entrada`) = week(curdate()) AND hour(`registros`.`fecha_entrada`) < 19 AND hour(`registros`.`fecha_salida`) >= 19 ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_vehiculos_mensualidad`
--
DROP TABLE IF EXISTS `vista_vehiculos_mensualidad`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_vehiculos_mensualidad`  AS SELECT `v`.`placa` AS `placa`, `v`.`tipo_vehiculo` AS `tipo_vehiculo`, `t`.`tipo_pago` AS `tipo_pago`, `t`.`id_tarifas_vehiculos` AS `id_tarifas_vehiculos` FROM (`vehiculos` `v` join `tarifas_vehiculos` `t` on(`v`.`id_tarifas_vehiculos` = `t`.`id_tarifas_vehiculos`)) WHERE `t`.`tipo_pago` in ('automovil_mensualidad','motocicleta_mensualidad','bicicleta_mensualidad') ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `registros`
--
ALTER TABLE `registros`
  ADD PRIMARY KEY (`id_registros`),
  ADD KEY `placa` (`placa`),
  ADD KEY `fk_registro_vehiculo` (`id_vehiculo`),
  ADD KEY `fk_registro_tarifa` (`id_tarifas_vehiculos`);

--
-- Indices de la tabla `registros_historial`
--
ALTER TABLE `registros_historial`
  ADD PRIMARY KEY (`id_registro_historial`),
  ADD KEY `id_registro` (`id_registro`);

--
-- Indices de la tabla `registros_historial_entrada`
--
ALTER TABLE `registros_historial_entrada`
  ADD PRIMARY KEY (`id_historial`);

--
-- Indices de la tabla `tarifas_vehiculos`
--
ALTER TABLE `tarifas_vehiculos`
  ADD PRIMARY KEY (`id_tarifas_vehiculos`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id_vehiculo`),
  ADD UNIQUE KEY `placa` (`placa`),
  ADD KEY `fk_vehiculo_tarifa` (`id_tarifas_vehiculos`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `registros`
--
ALTER TABLE `registros`
  MODIFY `id_registros` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `registros_historial`
--
ALTER TABLE `registros_historial`
  MODIFY `id_registro_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `registros_historial_entrada`
--
ALTER TABLE `registros_historial_entrada`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tarifas_vehiculos`
--
ALTER TABLE `tarifas_vehiculos`
  MODIFY `id_tarifas_vehiculos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id_vehiculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `registros`
--
ALTER TABLE `registros`
  ADD CONSTRAINT `fk_registro_tarifa` FOREIGN KEY (`id_tarifas_vehiculos`) REFERENCES `tarifas_vehiculos` (`id_tarifas_vehiculos`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_registro_vehiculo` FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`id_vehiculo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `fk_vehiculo_tarifa` FOREIGN KEY (`id_tarifas_vehiculos`) REFERENCES `tarifas_vehiculos` (`id_tarifas_vehiculos`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
