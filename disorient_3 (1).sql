-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-01-2025 a las 16:57:53
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
-- Base de datos: `disorient_3`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cuentas_por_pagar`
--

CREATE TABLE `cuentas_por_pagar` (
  `id_cuentasp` int(8) NOT NULL,
  `concepto` varchar(255) DEFAULT NULL,
  `descuento` float DEFAULT NULL,
  `monto` float DEFAULT NULL,
  `monto_desc` float DEFAULT NULL,
  `cuotas` varchar(255) DEFAULT NULL,
  `cedula_FK` int(8) DEFAULT NULL,
  `tasaBCV_FK` int(8) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cuentas_por_pagar`
--

INSERT INTO `cuentas_por_pagar` (`id_cuentasp`, `concepto`, `descuento`, `monto`, `monto_desc`, `cuotas`, `cedula_FK`, `tasaBCV_FK`, `fecha`) VALUES
(3, 'Cuentas por pagar', 10, 20, 0, 'semanal', 30505643, 34, '2023-12-04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `cedula` int(8) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `apellido` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `sexo` varchar(255) DEFAULT NULL,
  `edad` date DEFAULT NULL,
  `tlf` varchar(255) DEFAULT NULL,
  `second_tlf` varchar(255) DEFAULT NULL,
  `departamento` varchar(255) DEFAULT NULL,
  `cargo` varchar(255) DEFAULT NULL,
  `f_ingreso` date DEFAULT NULL,
  `sueldo` float DEFAULT NULL,
  `estado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`cedula`, `nombre`, `apellido`, `direccion`, `correo`, `sexo`, `edad`, `tlf`, `second_tlf`, `departamento`, `cargo`, `f_ingreso`, `sueldo`, `estado`) VALUES
(4044844, 'Jose Agustin', 'Ramos Marcano', 'Urbanizacion Barrio Sucre ', '', 'Masculino ', '1956-01-20', '04248160501', '', 'Gerencia', 'Gerente', '1986-03-01', 400, 1),
(5886341, 'Maria Auxiliadora', 'Barrios de Ramos', 'Urbanizacion Barrio Sucre', 'mariabarrios2007@gmail.com', 'femenino', '1958-07-07', '04248416663', '', 'Administracion', 'Sub gerente', '1986-03-01', 400, 1),
(6806908, 'Jose Rafael', 'Marcano Barrios', 'Urbanizacion Virgen del Valle', 'chelojorda@hotmail.com', 'Masculino', '2020-07-12', '04122966199', '', 'Contabilidad', 'Contador', '2021-08-16', 160, 1),
(8435158, 'Carmen Teresa', 'Yegres de la Rosa', 'Super Bloques', 'carmenyegres45@gmail.com', 'femenino', '1961-12-10', '04248548650', '', 'Contabilidad', 'Auxiliar Contable', '1997-02-21', 230, 1),
(15743047, 'Amado Jose', 'Bastardo Guevara', 'Avenida Bolivariano', '', 'Masculino', '1981-07-01', '04148214841', '', 'Almacen', 'Almacenista', '2009-09-29', 210, 1),
(16817936, 'Juan Victor', 'Guevara Colina', 'Bolivariano', 'jydelectronic@gmail.com', 'Masculino', '1985-12-12', '04160818715', '', 'Almacen', 'Almacenista', '2024-02-19', 170, 1),
(24873487, 'Giovanny Rafael ', 'Guacheque Ortiz', 'Los Chaguaramos', 'giovannyguacheque3@gmail.com', 'Masculino', '1992-04-17', '04160497024', '', 'Almacen', 'Almacenista', '2024-11-26', 140, 1),
(27494074, 'Sergio Andres', 'Barrios Morales', 'Urbanizacion Villa Olimpica', 'barriossergio415@gmail.com', 'Masculino', '2000-07-12', '04122933929', '', 'Ventas', 'Faturador', '2021-09-16', 160, 1),
(29721764, 'Fabiola Alejandra', 'Acuña Castañeda', 'Urbanizacion Barrio Sucre', 'facuna807@gmail.com', 'femenino', '2002-03-21', '04148262107', '', 'Ventas', 'Cobranza', '2024-04-05', 180, 1),
(30505643, 'Doranny Del Valle', 'Marcano Garcia', 'Urbanizacion Virge del Valle', 'dorannymarcano@gmail.com', 'femenino', '2003-10-24', '04123928296', '', 'Contailidad', 'Auxiliar Contable', '2024-01-29', 160, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fideicomiso`
--

CREATE TABLE `fideicomiso` (
  `id_fideicomiso` int(8) NOT NULL,
  `cedula_FK` int(8) DEFAULT NULL,
  `tasaBCV_FK` int(8) DEFAULT NULL,
  `t_servicio` int(8) DEFAULT NULL,
  `tasa_utilidad` float DEFAULT NULL,
  `t_bonovacacional` float DEFAULT NULL,
  `a_utilidad` float DEFAULT NULL,
  `a_bonovacional` float DEFAULT NULL,
  `sueldo_integral` float DEFAULT NULL,
  `sueldod_integral` float DEFAULT NULL,
  `dias_antiguedad` int(8) DEFAULT NULL,
  `dias_acumulados` int(8) DEFAULT NULL,
  `total_dias` int(8) DEFAULT NULL,
  `anticipo` float DEFAULT NULL,
  `monto` float DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `indicadorpagos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `indicadorpagos` (
`anio` int(4)
,`mes` int(2)
,`total_pagado` double
,`promedio_pagado` double
,`cantidad_empleados` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `islr`
--

CREATE TABLE `islr` (
  `id_islr` int(8) NOT NULL,
  `aporte` float DEFAULT NULL,
  `monto` float DEFAULT NULL,
  `cedula_FK` int(8) DEFAULT NULL,
  `tasaBCV_FK` int(8) DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `islr`
--

INSERT INTO `islr` (`id_islr`, `aporte`, `monto`, `cedula_FK`, `tasaBCV_FK`, `fecha`) VALUES
(7, 2, 38.02, 5886341, 146, '2024-12-19'),
(8, 2, 38.02, 4044844, 146, '2024-12-19'),
(9, 0.34, 3.8, 15743047, 146, '2024-12-19'),
(10, 0.04, 0.51, 8435158, 146, '2024-12-19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nomina`
--

CREATE TABLE `nomina` (
  `id_nomina` int(8) NOT NULL,
  `cedula_FK` int(8) DEFAULT NULL,
  `tasaBCV_FK` int(8) DEFAULT NULL,
  `cuentasp` int(8) DEFAULT NULL,
  `prestamos` int(8) DEFAULT NULL,
  `sueldosem` float DEFAULT NULL,
  `neto` float DEFAULT NULL,
  `bonificaciones` float DEFAULT NULL,
  `comisiones` float DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `nomina`
--

INSERT INTO `nomina` (`id_nomina`, `cedula_FK`, `tasaBCV_FK`, `cuentasp`, `prestamos`, `sueldosem`, `neto`, `bonificaciones`, `comisiones`, `fecha`, `estado`) VALUES
(90, 4044844, 146, NULL, NULL, 100, 5095, 0, 0, '2024-12-19', 1),
(93, 5886341, 146, NULL, NULL, 100, 100, 0, 0, '2024-12-19', 1),
(94, 8435158, 146, NULL, NULL, 55, 55, 0, 0, '2024-12-19', 1),
(95, 15743047, 146, NULL, NULL, 52.5, 52.5, 0, 0, '2024-12-19', 1),
(96, 27494074, 146, NULL, NULL, 40, 40, 0, 0, '2024-12-19', 1),
(97, 29721764, 146, NULL, NULL, 42.5, 42.5, 0, 0, '2024-12-19', 1),
(98, 6806908, 146, NULL, NULL, 65, 65, 0, 0, '2024-12-19', 1),
(99, 30505643, 146, NULL, NULL, 40, 40, 0, 0, '2024-12-19', 1),
(100, 16817936, 146, NULL, NULL, 42.5, 42.5, 0, 0, '2024-12-19', 1),
(101, 24873487, 146, NULL, NULL, 35, 35, 0, 0, '2024-12-19', 1),
(102, 4044844, 147, NULL, NULL, 100, 100, 0, 0, '2025-01-16', 1),
(103, 5886341, 147, NULL, NULL, 100, 100, 0, 0, '2025-01-16', 1),
(104, 8435158, 147, NULL, NULL, 55, 55, 0, 0, '2025-01-16', 1),
(105, 15743047, 147, NULL, NULL, 52.5, 52.5, 0, 0, '2025-01-16', 1),
(106, 27494074, 147, NULL, NULL, 40, 40, 0, 0, '2025-01-16', 1),
(107, 29721764, 147, NULL, NULL, 42.5, 42.5, 0, 0, '2025-01-16', 1),
(108, 6806908, 147, NULL, NULL, 65, 65, 0, 0, '2025-01-16', 1),
(109, 30505643, 147, NULL, NULL, 40, 40, 0, 0, '2025-01-16', 1),
(110, 16817936, 147, NULL, NULL, 42.5, 42.5, 0, 0, '2025-01-16', 1),
(111, 24873487, 147, NULL, NULL, 35, 35, 0, 0, '2025-01-16', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id_prestamos` int(8) NOT NULL,
  `concepto` varchar(255) DEFAULT NULL,
  `descuento` float DEFAULT NULL,
  `monto` float DEFAULT NULL,
  `monto_desc` float DEFAULT NULL,
  `cuotas` int(11) DEFAULT NULL,
  `cedula_FK` int(8) DEFAULT NULL,
  `tasaBCV_FK` int(8) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `date_limit` date DEFAULT NULL,
  `estado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasa_dolar`
--

CREATE TABLE `tasa_dolar` (
  `id_tasa` int(8) NOT NULL,
  `tasa_del_dia` float DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tasa_dolar`
--

INSERT INTO `tasa_dolar` (`id_tasa`, `tasa_del_dia`, `fecha`) VALUES
(1, 33.26, '2023-10-28'),
(2, 34.8, '2023-10-29'),
(3, 35.2, '2023-11-02'),
(4, 34.8, '2023-11-03'),
(5, 35.29, '2023-11-13'),
(6, 35.33, '2023-11-14'),
(7, 20.18, '2023-11-15'),
(8, 20.3, '2023-11-16'),
(9, 33.26, '2023-11-17'),
(10, 34.8, '2023-11-18'),
(11, 33.26, '2023-11-19'),
(12, 34.8, '2023-11-20'),
(13, 35.4, '2023-11-24'),
(14, 10.23, '2023-11-26'),
(27, 10.23, '2023-11-27'),
(28, 10.23, '2023-11-28'),
(29, 10.23, '2023-11-29'),
(30, 10.23, '2023-11-30'),
(31, 10.23, '2023-12-01'),
(32, 33.26, '2023-12-02'),
(33, 10.23, '2023-12-03'),
(34, 34.8, '2023-12-04'),
(35, 35.2, '2023-12-05'),
(36, 43.29, '2024-11-06'),
(37, 43.71, '2024-11-09'),
(38, 43.71, '2024-11-11'),
(39, 50, '2024-11-11'),
(40, 0, '2024-11-15'),
(41, 45, '2024-11-15'),
(42, 45.7894, '2024-11-15'),
(43, 45.7894, '2024-11-15'),
(44, 45.7894, '2024-11-15'),
(45, 45.7894, '2024-11-15'),
(46, 45.7894, '2024-11-15'),
(47, 45.7894, '2024-11-15'),
(48, 45.7894, '2024-11-15'),
(49, 45.7894, '2024-11-15'),
(50, 45.7894, '2024-11-15'),
(51, 45.7894, '2024-11-15'),
(52, 45.7894, '2024-11-15'),
(53, 45.7894, '2024-11-15'),
(54, 45.7894, '2024-11-15'),
(55, 45.7894, '2024-11-15'),
(56, 45.7894, '2024-11-15'),
(57, 45.7894, '2024-11-15'),
(58, 45.7894, '2024-11-15'),
(59, 45.7894, '2024-11-15'),
(60, 45.7894, '2024-11-15'),
(61, 45.7894, '2024-11-15'),
(62, 45.7894, '2024-11-15'),
(63, 45.7894, '2024-11-15'),
(64, 45.7894, '2024-11-15'),
(65, 45.7894, '2024-11-15'),
(66, 45.7894, '2024-11-15'),
(67, 45.7894, '2024-11-15'),
(68, 45.7894, '2024-11-15'),
(69, 45.7894, '2024-11-15'),
(70, 45.7894, '2024-11-15'),
(71, 45.7894, '2024-11-15'),
(72, 45.7894, '2024-11-15'),
(73, 45.7894, '2024-11-15'),
(74, 45.7894, '2024-11-15'),
(75, 45.7894, '2024-11-15'),
(76, 45.7894, '2024-11-15'),
(77, 45.7894, '2024-11-15'),
(78, 45.7894, '2024-11-15'),
(79, 45.7894, '2024-11-15'),
(80, 45.7894, '2024-11-15'),
(81, 45.7894, '2024-11-15'),
(82, 45.7894, '2024-11-15'),
(83, 45.7894, '2024-11-15'),
(88, 45.7894, '2024-11-16'),
(89, 45.7894, '2024-11-16'),
(90, 45.7894, '2024-11-16'),
(91, 45.7894, '2024-11-18'),
(92, 45.7894, '2024-11-18'),
(93, 45.7894, '2024-11-19'),
(94, 45.841, '2024-11-19'),
(95, 46.3273, '2024-11-21'),
(96, 46.3273, '2024-11-21'),
(97, 46.6176, '2024-11-24'),
(98, 46.6176, '2024-11-24'),
(99, 46.6176, '2024-11-24'),
(100, 46.6427, '2024-11-25'),
(101, 46.6427, '2024-11-25'),
(102, 46.6427, '2024-11-26'),
(103, 46.64, '2024-11-26'),
(104, 46.75, '2024-11-27'),
(105, 46.75, '2024-11-27'),
(106, 47.31, '2024-11-28'),
(107, 47.61, '2024-11-30'),
(108, 47.61, '2024-12-01'),
(109, 47.61, '2024-12-01'),
(110, 47.73, '2024-12-02'),
(111, 47.73, '2024-12-03'),
(112, 50.33, '2024-12-13'),
(113, 50.33, '2024-12-15'),
(114, 51.35, '2024-12-20'),
(115, 52.57, '2025-01-03'),
(116, 52.57, '2025-01-03'),
(117, 53.01, '2025-01-03'),
(118, 53.01, '2025-01-04'),
(119, 53.01, '2025-01-05'),
(120, 53.01, '2025-01-06'),
(121, 53.07, '2025-01-07'),
(122, 53.88, '2025-01-13'),
(123, 54.37, '2025-01-15'),
(124, 54.37, '2025-01-15'),
(125, 54.37, '2025-01-15'),
(128, 54.76, '2025-01-16'),
(130, 54.91, '2025-01-17'),
(131, 54.91, '2025-01-17'),
(132, 54.91, '2025-01-17'),
(133, 54.91, '2025-01-17'),
(134, 54.91, '2025-01-18'),
(135, 54.91, '2025-01-19'),
(136, 54.91, '2025-01-19'),
(137, 54.91, '2025-01-20'),
(141, 49.48, '2024-12-12'),
(142, 49.48, '2024-12-12'),
(143, 50.95, '2024-12-19'),
(144, 50.95, '2024-12-19'),
(145, 54.37, '2025-01-16'),
(146, 54.37, '2025-01-16'),
(147, 55.76, '2025-01-23'),
(148, 55.76, '2025-01-23');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(8) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `clave` varchar(255) DEFAULT NULL,
  `cedula` int(8) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `username`, `clave`, `cedula`, `type`, `estado`) VALUES
(1, 'admin', '1234', NULL, NULL, 1),
(2, 'angel', '1234', 28725234, 'Gerencia', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacaciones_y_utilidades`
--

CREATE TABLE `vacaciones_y_utilidades` (
  `vacaciones_id` int(8) NOT NULL,
  `dia_correspondido` int(8) DEFAULT NULL,
  `utilidades` int(11) DEFAULT NULL,
  `t_servicio` int(11) DEFAULT NULL,
  `ini_vacaciones` date DEFAULT NULL,
  `fin_vacaciones` date DEFAULT NULL,
  `ini_laboral` date DEFAULT NULL,
  `dia_descanso` int(8) DEFAULT NULL,
  `dia_feriado` int(8) DEFAULT NULL,
  `dia_otorgado` int(8) DEFAULT NULL,
  `sueldo_diario` float DEFAULT NULL,
  `cedula_FK` int(8) DEFAULT NULL,
  `tasaBCV_FK` int(8) DEFAULT NULL,
  `monto` float DEFAULT NULL,
  `ince` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_balance_prestamos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_balance_prestamos` (
`anio` int(4)
,`mes` int(2)
,`monto_total_reembolsado` double
,`monto_total_prestado` double
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_promedio_prestamos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_promedio_prestamos` (
`año` int(4)
,`mes` int(2)
,`semana` int(2)
,`promedio_semana` double
,`promedio_mensual` double
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_total_prestamos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_total_prestamos` (
`anio` int(4)
,`mes` int(2)
,`prestamos_realizados` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_variacion_nominia`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_variacion_nominia` (
`anio` int(4)
,`mes` int(2)
,`costo_nominia_actual` double
,`costo_nominia_anterior` double
,`variacion_porcentual` double
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_vendedores`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vista_vendedores` (
`anio` int(4)
,`mes` int(2)
,`t_comiciones` double
,`vendedores` int(8)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `indicadorpagos`
--
DROP TABLE IF EXISTS `indicadorpagos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `indicadorpagos`  AS SELECT year(`nomina`.`fecha`) AS `anio`, month(`nomina`.`fecha`) AS `mes`, sum(`nomina`.`neto`) AS `total_pagado`, avg(`nomina`.`neto`) AS `promedio_pagado`, count(distinct `nomina`.`cedula_FK`) AS `cantidad_empleados` FROM ((((`nomina` join `empleados` on(`nomina`.`cedula_FK` = `empleados`.`cedula`)) join `tasa_dolar` on(`nomina`.`tasaBCV_FK` = `tasa_dolar`.`id_tasa`)) left join `cuentas_por_pagar` on(`nomina`.`cuentasp` = `cuentas_por_pagar`.`id_cuentasp`)) left join `prestamos` on(`nomina`.`prestamos` = `prestamos`.`id_prestamos`)) WHERE `nomina`.`estado` = 1 GROUP BY year(`nomina`.`fecha`), month(`nomina`.`fecha`) ORDER BY year(`nomina`.`fecha`) ASC, month(`nomina`.`fecha`) ASC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_balance_prestamos`
--
DROP TABLE IF EXISTS `vista_balance_prestamos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_balance_prestamos`  AS SELECT year(`prestamos`.`fecha`) AS `anio`, month(`prestamos`.`fecha`) AS `mes`, sum(case when `prestamos`.`monto_desc` <= 0 then `prestamos`.`monto` else 0 end) AS `monto_total_reembolsado`, sum(`prestamos`.`monto`) AS `monto_total_prestado` FROM `prestamos` WHERE `prestamos`.`estado` = 1 GROUP BY year(`prestamos`.`fecha`), month(`prestamos`.`fecha`) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_promedio_prestamos`
--
DROP TABLE IF EXISTS `vista_promedio_prestamos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_promedio_prestamos`  AS SELECT `d1`.`año` AS `año`, `d1`.`mes` AS `mes`, `d1`.`semana` AS `semana`, `d1`.`promedio_semana` AS `promedio_semana`, `d2`.`promedio_mensual` AS `promedio_mensual` FROM ((select year(`prestamos`.`fecha`) AS `año`,month(`prestamos`.`fecha`) AS `mes`,week(`prestamos`.`fecha`) AS `semana`,avg(`prestamos`.`monto`) AS `promedio_semana` from `prestamos` where `prestamos`.`estado` = 1 group by year(`prestamos`.`fecha`),month(`prestamos`.`fecha`),week(`prestamos`.`fecha`)) `d1` join (select year(`prestamos`.`fecha`) AS `año`,month(`prestamos`.`fecha`) AS `mes`,avg(`prestamos`.`monto`) AS `promedio_mensual` from `prestamos` where `prestamos`.`estado` = 1 group by year(`prestamos`.`fecha`),month(`prestamos`.`fecha`)) `d2` on(`d1`.`año` = `d2`.`año` and `d1`.`mes` = `d2`.`mes`)) ORDER BY `d1`.`año` ASC, `d1`.`mes` ASC, `d1`.`semana` ASC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_total_prestamos`
--
DROP TABLE IF EXISTS `vista_total_prestamos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_total_prestamos`  AS SELECT year(`prestamos`.`fecha`) AS `anio`, month(`prestamos`.`fecha`) AS `mes`, count(`prestamos`.`id_prestamos`) AS `prestamos_realizados` FROM `prestamos` WHERE `prestamos`.`estado` = 1 GROUP BY month(`prestamos`.`fecha`) ORDER BY `prestamos`.`fecha` DESC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_variacion_nominia`
--
DROP TABLE IF EXISTS `vista_variacion_nominia`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_variacion_nominia`  AS SELECT `i1`.`anio` AS `anio`, `i1`.`mes` AS `mes`, `i1`.`total_pagado` AS `costo_nominia_actual`, `i2`.`total_pagado` AS `costo_nominia_anterior`, (`i1`.`total_pagado` - `i2`.`total_pagado`) / `i2`.`total_pagado` * 100 AS `variacion_porcentual` FROM (`indicadorpagos` `i1` left join `indicadorpagos` `i2` on(`i1`.`anio` = `i2`.`anio` and `i1`.`mes` = `i2`.`mes` + 1 or `i1`.`anio` = `i2`.`anio` + 1 and `i1`.`mes` = 1 and `i2`.`mes` = 12)) ORDER BY `i1`.`anio` DESC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_vendedores`
--
DROP TABLE IF EXISTS `vista_vendedores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_vendedores`  AS SELECT year(`nomina`.`fecha`) AS `anio`, month(`nomina`.`fecha`) AS `mes`, sum(`nomina`.`comisiones`) AS `t_comiciones`, `nomina`.`cedula_FK` AS `vendedores` FROM (`nomina` join `empleados` on(`nomina`.`cedula_FK` = `empleados`.`cedula`)) WHERE `nomina`.`estado` = 1 AND `empleados`.`estado` = 1 AND `empleados`.`cargo` = 'Vendedor' GROUP BY year(`nomina`.`fecha`), month(`nomina`.`fecha`), `nomina`.`cedula_FK` ORDER BY `nomina`.`fecha` DESC ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cuentas_por_pagar`
--
ALTER TABLE `cuentas_por_pagar`
  ADD PRIMARY KEY (`id_cuentasp`),
  ADD KEY `cedula_FK` (`cedula_FK`),
  ADD KEY `tasaBCV_FK` (`tasaBCV_FK`);

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`cedula`);

--
-- Indices de la tabla `fideicomiso`
--
ALTER TABLE `fideicomiso`
  ADD PRIMARY KEY (`id_fideicomiso`),
  ADD KEY `cedula_FK` (`cedula_FK`),
  ADD KEY `tasaBCV_FK` (`tasaBCV_FK`);

--
-- Indices de la tabla `islr`
--
ALTER TABLE `islr`
  ADD PRIMARY KEY (`id_islr`),
  ADD KEY `cedula_FK` (`cedula_FK`),
  ADD KEY `tasaBCV_FK` (`tasaBCV_FK`);

--
-- Indices de la tabla `nomina`
--
ALTER TABLE `nomina`
  ADD PRIMARY KEY (`id_nomina`),
  ADD KEY `cedula_fk0` (`cedula_FK`),
  ADD KEY `TasaBCV_fk1` (`tasaBCV_FK`),
  ADD KEY `prestamos` (`prestamos`),
  ADD KEY `cuentasp` (`cuentasp`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id_prestamos`),
  ADD KEY `cedula_FK` (`cedula_FK`),
  ADD KEY `tasaBCV_FK` (`tasaBCV_FK`);

--
-- Indices de la tabla `tasa_dolar`
--
ALTER TABLE `tasa_dolar`
  ADD PRIMARY KEY (`id_tasa`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_cedula` (`cedula`);

--
-- Indices de la tabla `vacaciones_y_utilidades`
--
ALTER TABLE `vacaciones_y_utilidades`
  ADD PRIMARY KEY (`vacaciones_id`),
  ADD KEY `cedula_FK` (`cedula_FK`),
  ADD KEY `tasa_dolar` (`tasaBCV_FK`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cuentas_por_pagar`
--
ALTER TABLE `cuentas_por_pagar`
  MODIFY `id_cuentasp` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `fideicomiso`
--
ALTER TABLE `fideicomiso`
  MODIFY `id_fideicomiso` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `islr`
--
ALTER TABLE `islr`
  MODIFY `id_islr` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `nomina`
--
ALTER TABLE `nomina`
  MODIFY `id_nomina` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id_prestamos` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `tasa_dolar`
--
ALTER TABLE `tasa_dolar`
  MODIFY `id_tasa` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `vacaciones_y_utilidades`
--
ALTER TABLE `vacaciones_y_utilidades`
  MODIFY `vacaciones_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cuentas_por_pagar`
--
ALTER TABLE `cuentas_por_pagar`
  ADD CONSTRAINT `cuentas_por_pagar_ibfk_1` FOREIGN KEY (`cedula_FK`) REFERENCES `empleados` (`cedula`),
  ADD CONSTRAINT `cuentas_por_pagar_ibfk_2` FOREIGN KEY (`tasaBCV_FK`) REFERENCES `tasa_dolar` (`id_tasa`);

--
-- Filtros para la tabla `fideicomiso`
--
ALTER TABLE `fideicomiso`
  ADD CONSTRAINT `fideicomiso_ibfk_1` FOREIGN KEY (`cedula_FK`) REFERENCES `empleados` (`cedula`),
  ADD CONSTRAINT `fideicomiso_ibfk_2` FOREIGN KEY (`tasaBCV_FK`) REFERENCES `tasa_dolar` (`id_tasa`);

--
-- Filtros para la tabla `islr`
--
ALTER TABLE `islr`
  ADD CONSTRAINT `cedula_FK` FOREIGN KEY (`cedula_FK`) REFERENCES `empleados` (`cedula`),
  ADD CONSTRAINT `tasaBCV_FK` FOREIGN KEY (`tasaBCV_FK`) REFERENCES `tasa_dolar` (`id_tasa`);

--
-- Filtros para la tabla `nomina`
--
ALTER TABLE `nomina`
  ADD CONSTRAINT `TasaBCV_fk1` FOREIGN KEY (`tasaBCV_FK`) REFERENCES `tasa_dolar` (`id_tasa`),
  ADD CONSTRAINT `cedula_fk0` FOREIGN KEY (`cedula_FK`) REFERENCES `empleados` (`cedula`),
  ADD CONSTRAINT `cuentasp` FOREIGN KEY (`cuentasp`) REFERENCES `cuentas_por_pagar` (`id_cuentasp`),
  ADD CONSTRAINT `prestamos` FOREIGN KEY (`prestamos`) REFERENCES `prestamos` (`id_prestamos`);

--
-- Filtros para la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD CONSTRAINT `prestamos_ibfk_1` FOREIGN KEY (`cedula_FK`) REFERENCES `empleados` (`cedula`),
  ADD CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`tasaBCV_FK`) REFERENCES `tasa_dolar` (`id_tasa`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `FK_cedula` FOREIGN KEY (`cedula`) REFERENCES `empleados` (`cedula`);

--
-- Filtros para la tabla `vacaciones_y_utilidades`
--
ALTER TABLE `vacaciones_y_utilidades`
  ADD CONSTRAINT `tasa_dolar` FOREIGN KEY (`tasaBCV_FK`) REFERENCES `tasa_dolar` (`id_tasa`),
  ADD CONSTRAINT `vacaciones_y_utilidades_ibfk_1` FOREIGN KEY (`cedula_FK`) REFERENCES `empleados` (`cedula`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
