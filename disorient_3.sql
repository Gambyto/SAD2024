-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 20-02-2026 a las 19:52:09
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

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
-- Estructura de tabla para la tabla `cuentas_por_pagar2`
--

CREATE TABLE `cuentas_por_pagar2` (
  `id_cp` int(8) NOT NULL,
  `id_prestamo` int(8) DEFAULT NULL,
  `deuda` float DEFAULT NULL,
  `aporte` float DEFAULT NULL,
  `tpago` varchar(255) DEFAULT NULL,
  `refe` varchar(255) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `estado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cuentas_por_pagar2`
--

INSERT INTO `cuentas_por_pagar2` (`id_cp`, `id_prestamo`, `deuda`, `aporte`, `tpago`, `refe`, `fecha`, `estado`) VALUES
(1, 17, 0, 20, 'Efectivo', '', '2025-02-07', 0),
(2, 18, 10, 40, 'Pago movil', '0000', '2025-02-08', 0),
(3, 11, 0, 10, 'Sueldo', 'No aplica', '0000-00-00', 0),
(4, 11, 0, 10, 'Sueldo', 'No aplica', '0000-00-00', 0),
(5, 11, 0, 10, 'Sueldo', 'No aplica', '2025-02-08', 0),
(6, 11, 0, 1, 'Sueldo', 'No aplica', '2025-02-08', 0),
(7, 11, 0, 1, 'Sueldo', 'No aplica', '2025-02-08', 0),
(8, 11, 0, 1, 'Sueldo', 'No aplica', '2025-02-08', 0),
(9, 11, 0, 17, 'Sueldo', 'No aplica', '2025-02-08', 1),
(10, 22, 90, 10, 'Sueldo', 'No aplica', '2025-02-08', 1),
(11, 22, 60, 30, 'Efectivo', 'No aplica', '2025-02-08', 1),
(12, 41, 0, 200, 'Efectivo', 'No aplica', '2025-03-11', 1),
(31, 50, 100, 50, 'Efectivo', 'No aplica', '2025-04-07', 1),
(32, 50, 50, 50, 'Efectivo', 'No aplica', '2025-04-08', 1),
(33, 50, 0, 50, 'Efectivo', 'No aplica', '2025-04-08', 1),
(35, 56, 150, 50, 'Sueldo', 'Pago automatico', '2026-02-19', 1),
(36, 49, 58.39, 8.33, 'Sueldo', 'Pago automatico', '2026-02-19', 1),
(37, 56, 140, 10, 'Sueldo', 'No aplica', '2026-02-20', 1);

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
  `afeccion` varchar(255) DEFAULT NULL,
  `discapacidad` varchar(255) DEFAULT NULL,
  `estado` tinyint(1) DEFAULT NULL,
  `limite_credito` decimal(10,2) DEFAULT 2000.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`cedula`, `nombre`, `apellido`, `direccion`, `correo`, `sexo`, `edad`, `tlf`, `second_tlf`, `departamento`, `cargo`, `f_ingreso`, `sueldo`, `afeccion`, `discapacidad`, `estado`, `limite_credito`) VALUES
(20, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2000.00),
(30, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2000.00),
(40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2000.00),
(50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2000.00),
(70, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2000.00),
(80, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 2000.00),
(3030, 'Lfkfgkfkgktt', 'Glkjflkgjlkf', 'Por alla por boliva8ri55a2n2o558', 'v1.corep@jflkdjflk.com', 'H', '1965-03-12', '0425 - 3467777', '0293 - 4521717', 'Contabilidad', 'Aux Contable', '1955-02-12', 1250, 'Sordera', 'Sensorial', 0, 2000.00),
(1234567, 'Pedro', 'Gonzalez', 'Bermudez', 'angel@gmail.com', 'H', '1978-08-10', '0424895463', '', 'Operador', 'Vendedor', '2006-06-19', 450, 'No aplica', 'Ninguna', 1, 2000.00),
(1234568, 'Iñigo', 'Martinez', 'Perimetral', 'Ini@gmail.com', 'H', '2025-04-07', '0424 - 8656432', '', 'Ventas', 'Facturador', '2025-01-13', 200, 'Ninguna', 'Ninguna', 1, 2000.00),
(9978769, 'henrye', 'garcia', 'la villa', 'a@a.com', 'H', '1984-01-04', '0424000256', '', 's', 's', '1970-01-01', 200, 'No aplica', 'Ninguna', 1, 2000.00),
(11111111, 'rafael', 'marin', 'vivo en mi casa', 'jo@pm.@.com', 'H', '2025-01-27', '0424 - 0000011', '0458 - 9202020', 'Almacén', 'Almacenista', '2025-01-27', 100, 'No aplica', 'Ninguna', 0, 2000.00),
(11380865, 'milagros', 'arcia', 'urb.llanada,sector 01,vereda 01, numeo06', NULL, 'M', '1975-05-16', '04168947406', NULL, NULL, 'obrera', '2023-11-08', 100, 'No aplica', 'Ninguna', 1, 2000.00),
(12345625, 'Alexis', 'Rivas', 'Puero de la madera ', 'a@a.com', 'H', '2025-04-07', '0424 - 8656788', '', 'Almacén', 'Almacenista', '2024-12-02', 200, 'No aplica', 'Ninguna', 1, 2000.00),
(12345672, 'Pedro', 'Gonzalez', 'Bermudez', 'angel@gmail.com', 'H', '2025-04-07', '0424895463', '', 'Operador', 'Vendedor', '1980-10-16', 400, '', 'Ninguna', 0, 2000.00),
(12345673, 'Pedro', 'Gonzalez', 'Bermudez', 'angel@gmail.com', 'H', '2025-04-07', '0424895463', '', 'Operador', 'Vendedor', '1980-10-16', 400, '', 'Ninguna', 0, 2000.00),
(12345674, 'Pedro', 'Gonzalez', 'Bermudez', 'angel@gmail.com', 'H', '2002-04-07', '0424895463', '', 'Operador', 'Vendedor', '2025-04-09', 400, 'Ninguna', 'Ninguna', 0, 2000.00),
(12345675, 'Pedro', 'Gonzalez', 'Bermudez', 'angel@gmail.com', 'H', '2025-04-07', '0424895463', '', 'Operador', 'Vendedor', '1980-10-16', 400, 'No aplica', 'Ninguna', 0, 2000.00),
(12345678, 'Raul', 'Ascencio', 'Mi casa', 'a@a.com', 'H', '1999-09-03', '0424 - 1234564', '', 'Operador', 'Vendedor', '2025-03-03', 200, 'Lesión de la Médula Espinal', 'Física', 1, 2000.00),
(12345679, 'María ', 'Rojas rivero', 'A', 'a@gmail.com', 'M', '2006-03-16', '0424 - 8656413', '', 'Operador', 'Vendedor', '2025-03-25', 200, 'Parálisis Cerebral', 'Física', 1, 2000.00),
(13631637, 'Rafael', 'Marin', 'Cumana 3', 'anel7810@gmail.com', 'H', '1978-08-10', '0416 - 4536789', '', 'Administración', 'Sub gerente', '2006-09-01', 130, 'Ninguna', 'Ninguna', 1, 2000.00),
(13631645, 'Pedro', 'Gonzalez', 'Bermudez', 'angel@gmail.com', 'H', '1978-08-10', '0424895463', '', 'Operador', 'Vendedor', '2006-06-19', 450, 'No aplica', 'Ninguna', 1, 2000.00),
(16997905, 'Jose', 'Ramos', 'Urb. san jose casa b18', 'RAMOSJOSEDANIEL01@GMAIL.COM', 'H', '1985-08-10', '0412 - 9713957', '', 'Administración', 'Sub gerente', '2017-01-20', 280, 'No aplica', 'Ninguna', 1, 2000.00),
(22222222, 'aa', 'aa', 'a', 'a@a.com', 'H', '2005-03-10', '1111 - 1111111', '', 'Administración', 'Sub gerente', '2025-02-02', 2000, 'No aplica', 'Ninguna', 0, 2000.00),
(23321541, 'a', 'a', 'a', 'a@a.com', 'H', '1998-01-23', '2013 - 0220230', '', 's', 's', '1970-01-01', 20, 'No aplica', 'Ninguna', 0, 2000.00),
(23333333, 'Pedro', 'Gonzalez', 'Bermudez', 'angel@gmail.com', 'H', '2006-04-07', '0424895463', '', 'Operador', 'Vendedor', '2025-04-08', 400, 'No aplica', 'Ninguna', 1, 2000.00),
(23646851, 'prueba', 'prueba', 'a', 'a', 'H', '2009-08-27', '1234 - 5678912', '1234 - 5678912', '...', '...', '2025-01-07', 22.22, 'No aplica', 'Ninguna', 0, 2000.00),
(28725234, 'Angel ', 'Rojas ', 'Bermudez', 'angel@gmail.com', 'H', '1989-09-23', '0424895463', '', 'Operador', 'Vendedor', '1980-10-16', 400, 'Ansiedad', 'Salud mental', 1, 2000.00),
(28725237, 'Gabi', 'Rojas', 'Calle sucre', 'anel7810@gmail.com', 'H', '2003-03-11', '0424 - 895463', '', 'Gerencia', 'Gerente', '2025-03-08', 130, 'No aplica', 'Ninguna', 1, 2000.00),
(30505643, 'dora', 'marcano', 'urb virgen del valle', 'a@a.com', 'M', '1999-05-20', '04123928296', '', 'Contabilidad', 'Aux Contable', '2023-11-02', 160, 'No aplica', 'Ninguna', 1, 2000.00),
(31425756, 'Gabi', 'Arias', 'urb pantanillo', NULL, 'M', '1992-07-16', '04123333332', NULL, NULL, 'gerente', '2023-11-17', 300, 'No aplica', 'Ninguna', 1, 2000.00),
(55555555, 'Pedro', 'Gonzalez', 'Bermudez', 'angel@gmail.com', 'H', '2000-04-07', '0424895463', '', 'Operador', 'Vendedor', '2018-10-16', 400, 'Ninguna', 'Ninguna', 1, 2000.00);

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

--
-- Volcado de datos para la tabla `fideicomiso`
--

INSERT INTO `fideicomiso` (`id_fideicomiso`, `cedula_FK`, `tasaBCV_FK`, `t_servicio`, `tasa_utilidad`, `t_bonovacacional`, `a_utilidad`, `a_bonovacional`, `sueldo_integral`, `sueldod_integral`, `dias_antiguedad`, `dias_acumulados`, `total_dias`, `anticipo`, `monto`, `fecha`) VALUES
(1, 28725234, 35, 16, 0.25, 0.08, 17.5, 5.83, 93.33, 3.111, 15, 30, 45, 140, 105, '2023-12-05'),
(2, 28725234, 35, 16, 0.25, 0.08, 17.5, 5.83, 93.33, 3.11, 15, 30, 45, 139.95, 104.96, '2023-12-05'),
(3, 28725234, NULL, 0, 0, 0.08, 5.775, 1.93, 30.81, 1.03, 15, 30, 0, 0, 0, '2024-11-28'),
(4, 28725234, NULL, 0, 0, 0.08, 5.775, 1.93, 30.81, 1.03, 15, 30, 0, 0, 0, '2024-11-28'),
(5, 28725234, NULL, 0, 0, 0.08, 5.775, 1.93, 30.81, 1.03, 15, 30, 45, 0, 0, '2024-11-28'),
(6, 28725234, NULL, 0, 0, 0.08, 5.775, 1.93, 30.81, 1.03, 15, 30, 45, 0, 0, '2024-11-28'),
(7, 28725234, NULL, 0, 0, 0.08, 5.775, 1.93, 30.81, 1.03, 15, 30, 45, 46.35, 34.76, '2024-11-28'),
(8, 28725234, 150, 0, 0, 0.08, 5.775, 1.93, 30.81, 1.03, 15, 30, 45, 46.35, 34.76, '2025-01-27'),
(9, 28725234, 150, 0, 0, 0.08, 5.775, 1.93, 30.81, 1.03, 15, 30, 45, 34.76, 46.35, '2025-01-27'),
(10, 30505643, 197, 0, 0, 0.04, 13.2, 2.2, 68.2, 2.27, 15, 15, 30, 51.07, 68.1, '2025-04-07'),
(11, 13631637, 200, 0, 0, 0.08, 10.725, 3.58, 57.21, 1.91, 15, 30, 45, 64.46, 85.95, '2025-04-10');

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
(1, 2, 46.56, 28725234, 11, '2023-11-19'),
(2, 2, 48.72, 28725234, 12, '2023-11-20'),
(3, 2, 69.6, 30505643, 34, '2023-12-04'),
(4, 2, 65.59, 28725234, 136, '2025-02-07'),
(6, 3, 115.31, 28725234, 137, '2025-01-20'),
(7, 1, 133.58, 12345678, 186, '2025-03-17'),
(8, 1, 187.01, 16997905, 187, '2025-03-18'),
(9, 1, 133.58, 9978769, 187, '2025-03-18'),
(10, 1, 66.79, 11380865, 187, '2025-03-18'),
(11, 2, 231.01, 30505643, 197, '2025-04-07'),
(12, 2, 197.08, 13631637, 200, '2025-04-10');

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
(2, 28725234, 4, NULL, NULL, 17.5, 17.5, NULL, NULL, '2023-11-03', 1),
(3, 28725234, 4, NULL, NULL, 17.5, 37.5, NULL, NULL, '2023-11-03', 1),
(4, 28725234, 4, NULL, NULL, 17.5, 17.5, NULL, NULL, '2023-05-23', 1),
(5, 11380865, 4, NULL, NULL, 25, 35, NULL, NULL, '2023-11-03', 1),
(6, 30505643, 4, NULL, NULL, 25, 45, NULL, NULL, '2023-11-03', 1),
(7, 28725234, 6, NULL, NULL, 17.5, 47.5, NULL, NULL, '2023-11-14', 1),
(8, 28725234, 6, NULL, NULL, 17.5, 52.5, NULL, NULL, '2023-11-14', 1),
(11, 28725234, 9, NULL, NULL, 17.5, 17.5, NULL, NULL, '2023-11-17', 1),
(12, 28725234, 13, NULL, NULL, 17.5, 37.5, NULL, NULL, '2023-11-24', 1),
(27, 28725234, 33, NULL, NULL, 17.5, 13.69, NULL, NULL, '2023-12-03', 1),
(28, 30505643, 33, NULL, NULL, 25, 25, NULL, NULL, '2023-12-03', 1),
(29, 28725234, 34, NULL, NULL, 17.5, 12.5, NULL, NULL, '2023-12-04', 1),
(30, 28725234, 34, NULL, NULL, 17.5, 12.5, NULL, NULL, '2023-12-04', 1),
(31, 28725234, 34, NULL, NULL, 17.5, 12.5, NULL, NULL, '2023-12-04', 1),
(32, 28725234, 34, NULL, NULL, 17.5, 12.5, NULL, NULL, '2023-12-04', 1),
(33, 28725234, 34, NULL, NULL, 17.5, 17.5, NULL, NULL, '2023-12-04', 1),
(34, 28725234, 34, NULL, NULL, 17.5, 17.5, 0, 0, '2023-12-04', 1),
(35, 30505643, 34, NULL, 4, 25, 35, 20, 10, '2023-12-04', 1),
(36, 30505643, 35, NULL, 4, 25, 35, 10, 20, '2023-12-05', 1),
(38, 30505643, 35, NULL, 4, 25, 25, 10, 10, '2023-12-05', 1),
(39, 30505643, 35, NULL, 4, 25, 25, 10, 10, '2023-12-05', 1),
(40, 30505643, 35, 3, 4, 25, 25, 10, 10, '2023-12-05', 1),
(41, 30505643, 35, 3, NULL, 25, 35, 20, 0, '2023-12-05', 1),
(42, 30505643, 35, NULL, NULL, 25, 25, 10, 0, '2023-12-05', 1),
(61, 28725234, 35, NULL, NULL, 17.5, 17.5, 0, 0, '2023-12-05', 1),
(62, 30505643, 35, 3, NULL, 25, 15, 0, 0, '2023-12-05', 1),
(63, 30505643, 35, 3, NULL, 25, 15, 0, 0, '2023-12-05', 1),
(64, 30505643, 35, 3, NULL, 25, 35, 0, 20, '2023-12-05', 1),
(65, 28725234, 35, NULL, NULL, 17.5, 17.5, 0, 0, '2023-12-05', 1),
(66, 9978769, NULL, NULL, NULL, 50, 0, 0, 0, '2024-11-26', 1),
(67, 9978769, 102, NULL, NULL, 50, 0, 0, 0, '2024-11-26', 1),
(68, 28725234, 102, NULL, 6, 17.5, 12.5, 0, 0, '2024-11-26', 1),
(69, 28725234, 102, NULL, 6, 17.5, 72.5, 28, 32, '2024-11-26', 1),
(70, 28725234, 103, NULL, 6, 17.5, 12.5, 0, 0, '2024-11-26', 1),
(71, 28725234, 103, NULL, 6, 17.5, 12.5, 0, 0, '2024-11-26', 1),
(72, 28725234, 103, NULL, NULL, 17.5, 17.5, 0, 0, '2024-11-26', 1),
(75, 28725234, 128, NULL, 7, 17.5, 5, 0, 0, '2025-01-16', 1),
(76, 28725234, 128, NULL, 7, 17.5, 5, 0, 0, '2025-01-16', 1),
(77, 28725234, 128, NULL, 9, 17.5, 2.5, 0, 0, '2025-01-16', 1),
(78, 30505643, 133, 3, NULL, 25, 15, 0, 0, '2025-01-17', 1),
(79, 30505643, 109, 3, NULL, 25, 15, 0, 0, '2024-12-24', 1),
(84, 28725234, 137, NULL, NULL, 17.5, 17.5, 0, 0, '2025-01-20', 1),
(85, 28725234, 150, NULL, NULL, 17.5, 27.5, 10, 0, '2025-01-27', 1),
(86, 28725234, 155, NULL, NULL, 17.5, 17.5, 0, 0, '2025-02-01', 1),
(87, 28725234, 165, NULL, 17, 17.5, 5, 0, 0, '2025-02-07', 1),
(88, 28725234, 166, NULL, 18, 17.5, 7.5, 0, 0, '2025-02-08', 1),
(89, 28725234, 166, NULL, 11, 17.5, 7.5, 0, 0, '2025-02-08', 1),
(90, 28725234, 166, NULL, 11, 17.5, 7.5, 0, 0, '2025-02-08', 1),
(91, 28725234, 166, NULL, 11, 17.5, 7.5, 0, 0, '2025-02-08', 1),
(92, 28725234, 166, NULL, 11, 17.5, 7.5, 0, 0, '2025-02-08', 1),
(93, 28725234, 166, NULL, 11, 17.5, 16.5, 0, 0, '2025-02-08', 1),
(94, 28725234, 166, NULL, 11, 17.5, 16.5, 0, 0, '2025-02-08', 1),
(95, 28725234, 166, NULL, 11, 17.5, 16.5, 0, 0, '2025-02-08', 1),
(96, 28725234, 166, NULL, 11, 17.5, 0.5, 0, 0, '2025-02-08', 1),
(97, 28725234, 166, NULL, 22, 17.5, 7.5, 0, 0, '2025-02-08', 1),
(98, 9978769, 166, NULL, NULL, 50, 0, 0, 0, '2025-02-08', 1),
(99, 11380865, 166, NULL, NULL, 25, 25, 0, 0, '2025-02-08', 1),
(100, 30505643, 167, NULL, NULL, 25, 55, 0, 30, '2025-02-09', 1),
(101, 9978769, 181, NULL, 38, 50, 0, 0, 0, '2025-03-09', 1),
(102, 28725234, 182, NULL, 45, 17.5, 7.5, 0, 0, '2025-03-10', 1),
(103, 28725237, 182, NULL, NULL, 32.5, 32.5, 0, 0, '2025-03-10', 1),
(104, 12345678, 187, NULL, 46, 50, 33.33, 0, 0, '2025-03-18', 1),
(105, 28725234, 194, NULL, NULL, 100, 100, 0, 0, '2025-04-03', 1),
(106, 12345678, 194, NULL, NULL, 50, 50, 0, 0, '2025-04-03', 1),
(107, 30505643, 197, NULL, NULL, 40, 40, 0, 0, '2025-04-07', 1),
(108, 13631637, 200, NULL, NULL, 32.5, 57.5, 10, 15, '2025-04-10', 1),
(125, 1234568, 222, NULL, NULL, 50, 50, 0, 0, '2026-02-19', 1),
(126, 9978769, 222, NULL, 49, 50, 41.67, 0, 0, '2026-02-19', 1),
(127, 11380865, 222, NULL, NULL, 25, 25, 0, 0, '2026-02-19', 1),
(128, 12345625, 222, NULL, NULL, 50, 50, 0, 0, '2026-02-19', 1),
(129, 16997905, 222, NULL, NULL, 70, 70, 0, 0, '2026-02-19', 1),
(130, 28725237, 222, NULL, NULL, 32.5, 32.5, 0, 0, '2026-02-19', 1),
(131, 30505643, 222, NULL, NULL, 40, 40, 0, 0, '2026-02-19', 1),
(132, 31425756, 222, NULL, NULL, 75, 75, 0, 0, '2026-02-19', 1),
(134, 13631637, 223, NULL, 56, 32.5, 22.5, 0, 0, '2026-02-20', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

CREATE TABLE `prestamos` (
  `id_prestamos` int(8) NOT NULL,
  `concepto` varchar(255) DEFAULT NULL,
  `solicitud_FK` int(11) DEFAULT NULL,
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

--
-- Volcado de datos para la tabla `prestamos`
--

INSERT INTO `prestamos` (`id_prestamos`, `concepto`, `solicitud_FK`, `descuento`, `monto`, `monto_desc`, `cuotas`, `cedula_FK`, `tasaBCV_FK`, `fecha`, `date_limit`, `estado`) VALUES
(3, 'Prestamo', NULL, 5, 20, 0, 0, 28725234, 34, '2023-12-04', NULL, 1),
(4, 'Prestamo', NULL, 10, 100, 0, 0, 30505643, 34, '2023-12-04', NULL, 1),
(5, 'Prestamo', NULL, 10, 40, 0, 0, 30505643, 34, '2023-12-04', NULL, 1),
(6, 'Prestamo', NULL, 5, 20, 0, 0, 28725234, 98, '2024-11-25', NULL, 1),
(7, 'a', NULL, 12.5, 25, 0, 2, 28725234, NULL, '2025-01-02', '2025-01-16', 0),
(8, 'prueba', NULL, 10, 20, 0, 2, 28725234, NULL, '2025-01-02', '2025-01-16', 1),
(9, 'prueba', NULL, 15, 30, 0, 2, 28725234, 116, '2025-01-10', '2025-01-24', 1),
(10, 'prueba ', NULL, 15, 60, 0, 4, 28725234, 116, '2025-01-03', '2025-01-31', 1),
(11, 'prueba', NULL, 60, 60, 0, 1, 28725234, 134, '2025-01-18', '2025-01-25', 1),
(12, 'mi presta ideal', NULL, 1.53, 2000, 0, 1304, 11111111, 151, '2025-01-27', '2050-01-27', 0),
(17, 'prueba', 3, 12.5, 50, 0, 4, 28725234, 165, '2025-02-07', '2025-03-07', 0),
(18, 'prueba', 4, 12.5, 50, 0, 4, 28725234, 166, '2025-02-08', '2025-03-08', 1),
(22, 'null', 5, 25, 100, 0, 4, 28725234, 166, '2025-02-08', '2025-03-08', 1),
(37, 'prueba', NULL, 33.33, 400, 200, 12, 28725234, 166, '2025-02-08', '2025-05-03', 0),
(38, 'adelanto', NULL, 50, 200, 58.39, 4, 9978769, 177, '2025-03-02', '2025-03-30', 0),
(39, 'null', NULL, 50, 200, 0, 4, 12345678, 181, '2025-03-10', '2025-04-07', 0),
(40, 'null', NULL, 100, 400, 200, 4, 28725234, NULL, '2025-03-10', '2025-04-07', 0),
(41, 'null', NULL, 50, 200, 0, 4, 12345678, NULL, '2025-03-10', '2025-04-07', 1),
(42, 'null', NULL, 125, 500, 500, 4, 11380865, NULL, '2025-03-10', '2025-04-07', 0),
(43, 'null', NULL, 25, 100, 100, 4, 11380865, NULL, '2025-03-10', '2025-04-07', 0),
(44, 'null', NULL, 37.5, 150, 0, 4, 28725234, 182, '2025-03-10', '2025-04-07', 0),
(45, 'null', NULL, 125, 500, 300, 4, 28725234, 182, '2025-03-10', '2025-04-07', 0),
(46, 'null', NULL, 16.67, 200, 180, 12, 12345678, 182, '2025-03-11', '2025-06-03', 0),
(47, 'null', NULL, 10.42, 125, -25, 12, 28725234, 182, '2025-03-11', '2025-06-03', 0),
(48, 'null', NULL, 10.42, 500, 500, 48, 28725237, 182, '2025-03-11', '2026-02-10', 0),
(49, 'null', NULL, 8.33, 200, 58.39, 24, 9978769, 194, '2025-04-03', '2025-09-18', 1),
(50, 'null', NULL, 50, 200, 0, 4, 28725234, 196, '2025-04-06', '2025-05-04', 1),
(51, 'otro prestamo ', 8, 5, 20, 20, 4, 12345678, 196, '2025-04-07', '2025-05-05', 0),
(56, 'null', 14, 50, 200, 140, 4, 13631637, 200, '2025-04-10', '2025-05-08', 1),
(57, 'null', 13, 75, 300, 250, 4, 13631637, 200, '2025-04-10', '2025-05-08', 1),
(58, 'null', 12, 175, 700, 650, 4, 13631637, 200, '2025-04-10', '2025-05-08', 1),
(59, 'oto mas', 9, 10.42, 250, 200, 24, 13631637, 200, '2025-04-10', '2025-09-25', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes`
--

CREATE TABLE `solicitudes` (
  `id_solicitud` int(8) NOT NULL,
  `cedula_FK` int(8) DEFAULT NULL,
  `monto` float DEFAULT NULL,
  `descuento` float DEFAULT NULL,
  `cuotas` int(2) DEFAULT NULL,
  `concepto` varchar(255) DEFAULT NULL,
  `f_solicitud` date DEFAULT NULL,
  `f_aprobacion` date DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitudes`
--

INSERT INTO `solicitudes` (`id_solicitud`, `cedula_FK`, `monto`, `descuento`, `cuotas`, `concepto`, `f_solicitud`, `f_aprobacion`, `estado`) VALUES
(1, 28725234, 110, 9.17, 12, 'prueba', NULL, '0000-00-00', 'Denegado'),
(2, 28725234, 105, 26.25, 4, 'prueba', '2025-02-07', '0000-00-00', 'Aprovado'),
(3, 28725234, 50, 12.5, 4, 'prueba', '2025-02-07', '2025-02-07', 'Aprovado'),
(4, 28725234, 50, 12.5, 4, 'prueba', '2025-02-08', '2025-02-08', 'Aprovado'),
(5, 28725234, 100, 25, 4, 'null', '2025-02-08', '2025-02-08', 'Aprovado'),
(6, 28725234, 100, 8.33, 12, 'prueba ', '2025-02-28', '2025-03-01', 'Denegado'),
(7, 12345678, 100, 8.33, 12, 'pretamos prueba ', '2025-03-31', '2025-04-06', 'Denegado'),
(8, 12345678, 20, 5, 4, 'otro prestamo ', '2025-04-02', '2025-11-06', 'Denegado'),
(9, 13631637, 250, 10.42, 24, 'oto mas', '2025-04-10', '2025-04-10', 'Aprovado'),
(10, 13631637, 566.66, 141.66, 4, 'yuyuyuy', '2025-04-10', '2025-04-10', 'Denegado'),
(11, 13631637, 3.43, 0.86, 4, 'null', '2025-04-10', '2025-04-10', 'Denegado'),
(12, 13631637, 700, 175, 4, 'null', '2025-04-10', '2025-04-10', 'Aprovado'),
(13, 13631637, 300, 75, 4, 'null', '2025-04-10', '2025-04-10', 'Aprovado'),
(14, 13631637, 200, 50, 4, 'null', '2025-04-10', '2025-04-10', 'Aprovado'),
(25, 12345678, 200, 50, 4, 'null', '2026-01-28', '2026-01-31', 'Denegado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasa_dolar`
--

CREATE TABLE `tasa_dolar` (
  `id_tasa` int(8) NOT NULL,
  `tasa_del_dia` float DEFAULT NULL,
  `tasa_eur` float DEFAULT NULL,
  `fecha` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tasa_dolar`
--

INSERT INTO `tasa_dolar` (`id_tasa`, `tasa_del_dia`, `tasa_eur`, `fecha`) VALUES
(1, 33.26, NULL, '2023-10-28'),
(2, 34.8, NULL, '2023-10-29'),
(3, 35.2, NULL, '2023-11-02'),
(4, 34.8, NULL, '2023-11-03'),
(5, 35.29, NULL, '2023-11-13'),
(6, 35.33, NULL, '2023-11-14'),
(7, 20.18, NULL, '2023-11-15'),
(8, 20.3, NULL, '2023-11-16'),
(9, 33.26, NULL, '2023-11-17'),
(10, 34.8, NULL, '2023-11-18'),
(11, 33.26, NULL, '2023-11-19'),
(12, 34.8, NULL, '2023-11-20'),
(13, 35.4, NULL, '2023-11-24'),
(14, 10.23, NULL, '2023-11-26'),
(27, 10.23, NULL, '2023-11-27'),
(28, 10.23, NULL, '2023-11-28'),
(29, 10.23, NULL, '2023-11-29'),
(30, 10.23, NULL, '2023-11-30'),
(31, 10.23, NULL, '2023-12-01'),
(32, 33.26, NULL, '2023-12-02'),
(33, 10.23, NULL, '2023-12-03'),
(34, 34.8, NULL, '2023-12-04'),
(35, 35.2, NULL, '2023-12-05'),
(36, 43.29, NULL, '2024-11-06'),
(37, 43.71, NULL, '2024-11-09'),
(38, 43.71, NULL, '2024-11-11'),
(39, 50, NULL, '2024-11-11'),
(40, 0, NULL, '2024-11-15'),
(41, 45, NULL, '2024-11-15'),
(42, 45.7894, NULL, '2024-11-15'),
(43, 45.7894, NULL, '2024-11-15'),
(44, 45.7894, NULL, '2024-11-15'),
(45, 45.7894, NULL, '2024-11-15'),
(46, 45.7894, NULL, '2024-11-15'),
(47, 45.7894, NULL, '2024-11-15'),
(48, 45.7894, NULL, '2024-11-15'),
(49, 45.7894, NULL, '2024-11-15'),
(50, 45.7894, NULL, '2024-11-15'),
(51, 45.7894, NULL, '2024-11-15'),
(52, 45.7894, NULL, '2024-11-15'),
(53, 45.7894, NULL, '2024-11-15'),
(54, 45.7894, NULL, '2024-11-15'),
(55, 45.7894, NULL, '2024-11-15'),
(56, 45.7894, NULL, '2024-11-15'),
(57, 45.7894, NULL, '2024-11-15'),
(58, 45.7894, NULL, '2024-11-15'),
(59, 45.7894, NULL, '2024-11-15'),
(60, 45.7894, NULL, '2024-11-15'),
(61, 45.7894, NULL, '2024-11-15'),
(62, 45.7894, NULL, '2024-11-15'),
(63, 45.7894, NULL, '2024-11-15'),
(64, 45.7894, NULL, '2024-11-15'),
(65, 45.7894, NULL, '2024-11-15'),
(66, 45.7894, NULL, '2024-11-15'),
(67, 45.7894, NULL, '2024-11-15'),
(68, 45.7894, NULL, '2024-11-15'),
(69, 45.7894, NULL, '2024-11-15'),
(70, 45.7894, NULL, '2024-11-15'),
(71, 45.7894, NULL, '2024-11-15'),
(72, 45.7894, NULL, '2024-11-15'),
(73, 45.7894, NULL, '2024-11-15'),
(74, 45.7894, NULL, '2024-11-15'),
(75, 45.7894, NULL, '2024-11-15'),
(76, 45.7894, NULL, '2024-11-15'),
(77, 45.7894, NULL, '2024-11-15'),
(78, 45.7894, NULL, '2024-11-15'),
(79, 45.7894, NULL, '2024-11-15'),
(80, 45.7894, NULL, '2024-11-15'),
(81, 45.7894, NULL, '2024-11-15'),
(82, 45.7894, NULL, '2024-11-15'),
(83, 45.7894, NULL, '2024-11-15'),
(88, 45.7894, NULL, '2024-11-16'),
(89, 45.7894, NULL, '2024-11-16'),
(90, 45.7894, NULL, '2024-11-16'),
(91, 45.7894, NULL, '2024-11-18'),
(92, 45.7894, NULL, '2024-11-18'),
(93, 45.7894, NULL, '2024-11-19'),
(94, 45.841, NULL, '2024-11-19'),
(95, 46.3273, NULL, '2024-11-21'),
(96, 46.3273, NULL, '2024-11-21'),
(97, 46.6176, NULL, '2024-11-24'),
(98, 46.6176, NULL, '2024-11-24'),
(99, 46.6176, NULL, '2024-11-24'),
(100, 46.6427, NULL, '2024-11-25'),
(101, 46.6427, NULL, '2024-11-25'),
(102, 46.6427, NULL, '2024-11-26'),
(103, 46.64, NULL, '2024-11-26'),
(104, 46.75, NULL, '2024-11-27'),
(105, 46.75, NULL, '2024-11-27'),
(106, 47.31, NULL, '2024-11-28'),
(107, 47.61, NULL, '2024-11-30'),
(108, 47.61, NULL, '2024-12-01'),
(109, 47.61, NULL, '2024-12-01'),
(110, 47.73, NULL, '2024-12-02'),
(111, 47.73, NULL, '2024-12-03'),
(112, 50.33, NULL, '2024-12-13'),
(113, 50.33, NULL, '2024-12-15'),
(114, 51.35, NULL, '2024-12-20'),
(115, 52.57, NULL, '2025-01-03'),
(116, 52.57, NULL, '2025-01-03'),
(117, 53.01, NULL, '2025-01-03'),
(118, 53.01, NULL, '2025-01-04'),
(119, 53.01, NULL, '2025-01-05'),
(120, 53.01, NULL, '2025-01-06'),
(121, 53.07, NULL, '2025-01-07'),
(122, 53.88, NULL, '2025-01-13'),
(123, 54.37, NULL, '2025-01-15'),
(124, 54.37, NULL, '2025-01-15'),
(125, 54.37, NULL, '2025-01-15'),
(128, 54.76, NULL, '2025-01-16'),
(130, 54.91, NULL, '2025-01-17'),
(131, 54.91, NULL, '2025-01-17'),
(132, 54.91, NULL, '2025-01-17'),
(133, 54.91, NULL, '2025-01-17'),
(134, 54.91, NULL, '2025-01-18'),
(135, 54.91, NULL, '2025-01-19'),
(136, 54.91, NULL, '2025-01-19'),
(137, 54.91, NULL, '2025-01-20'),
(138, 54.91, NULL, '2025-01-20'),
(139, 55.3, NULL, '2025-01-21'),
(140, 55.3, NULL, '2025-01-21'),
(141, 55.3, NULL, '2025-01-21'),
(142, 55.3, NULL, '2025-01-22'),
(143, 55.3, NULL, '2025-01-22'),
(144, 55.3, NULL, '2025-01-22'),
(145, 55.3, NULL, '2025-01-22'),
(146, 55.3, NULL, '2025-01-22'),
(147, 55.3, NULL, '2025-01-22'),
(148, 56.65, NULL, '2025-01-25'),
(149, 56.65, NULL, '2025-01-26'),
(150, 56.65, NULL, '2025-01-27'),
(151, 56.65, NULL, '2025-01-27'),
(152, 56.86, NULL, '2025-01-28'),
(153, 58.44, NULL, '2025-01-31'),
(154, 58.44, NULL, '2025-02-01'),
(155, 58.44, NULL, '2025-02-01'),
(156, 58.44, NULL, '2025-02-01'),
(161, 58.44, NULL, '2025-02-03'),
(162, 58.54, NULL, '2025-02-04'),
(163, 59.46, NULL, '2025-02-05'),
(164, 59.46, NULL, '2025-02-06'),
(165, 60.14, NULL, '2025-02-07'),
(166, 60.52, NULL, '2025-02-08'),
(167, 60.52, NULL, '2025-02-09'),
(168, 60.52, NULL, '2025-02-10'),
(169, 60.54, NULL, '2025-02-11'),
(170, 61.35, NULL, '2025-02-13'),
(171, 61.82, NULL, '2025-02-14'),
(172, 63.41, NULL, '2025-02-21'),
(173, 63.49, NULL, '2025-02-25'),
(174, 64.25, NULL, '2025-02-27'),
(175, 64.48, NULL, '2025-02-28'),
(176, 64.48, NULL, '2025-03-01'),
(177, 64.48, NULL, '2025-03-02'),
(178, 64.48, NULL, '2025-03-03'),
(179, 65.27, NULL, '2025-03-07'),
(180, 65.27, NULL, '2025-03-08'),
(181, 65.27, NULL, '2025-03-09'),
(182, 65.27, NULL, '2025-03-10'),
(183, 65.42, NULL, '2025-03-11'),
(184, 65.63, NULL, '2025-03-12'),
(185, 66.44, NULL, '2025-03-14'),
(186, 66.79, NULL, '2025-03-17'),
(187, 66.79, NULL, '2025-03-18'),
(188, 68.31, NULL, '2025-03-24'),
(189, 68.7, NULL, '2025-03-25'),
(190, 69.44, NULL, '2025-03-28'),
(191, 69.57, NULL, '2025-03-30'),
(192, 69.57, NULL, '2025-03-31'),
(193, 70.25, NULL, '2025-04-02'),
(194, 70.25, NULL, '2025-04-03'),
(195, 72.19, NULL, '2025-04-04'),
(196, 72.19, NULL, '2025-04-06'),
(197, 72.19, NULL, '2025-04-07'),
(198, 73.36, NULL, '2025-04-08'),
(199, 75.8, NULL, '2025-04-09'),
(200, 75.8, NULL, '2025-04-10'),
(201, 80.96, NULL, '2025-04-21'),
(202, 88.72, NULL, '2025-05-06'),
(203, 95.24, NULL, '2025-05-27'),
(204, 103.74, NULL, '2025-06-19'),
(205, 193.3, NULL, '2025-10-10'),
(206, 197.25, NULL, '2025-10-14'),
(207, 199.11, NULL, '2025-10-15'),
(208, 212.48, NULL, '2025-10-23'),
(209, 219.87, NULL, '2025-10-29'),
(210, 221.74, NULL, '2025-10-30'),
(212, 227.56, 261.16, '2025-11-06'),
(213, 231.09, 267.28, '2025-11-11'),
(214, 237.75, 275.81, '2025-11-19'),
(215, 330.38, 384.33, '2026-01-12'),
(216, 330.38, 384.33, '2026-01-13'),
(217, 336.46, 391.88, '2026-01-14'),
(218, 355.55, 417.59, '2026-01-26'),
(219, 361.49, 432.72, '2026-01-28'),
(220, 370.25, 440.48, '2026-01-31'),
(221, 398.74, 471.41, '0000-00-00'),
(222, 398.75, 471.42, '2026-02-19'),
(223, 402.33, 472.83, '2026-02-20');

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
(2, 'angel23', '1234', 28725234, 'Gerencia', 1),
(3, 'prueba1', '1234', 9978769, 'Administrador', 0),
(4, 'dora', '1234', 30505643, 'Administrador', 1),
(5, 'prueba2', '1234', 31425756, 'Gerencia', 1),
(6, 'gerencia', '1234', 23321541, 'Gerencia', 1),
(7, 'prueba4', '12345', 12345678, 'Trabajador', 1),
(8, 'Iñigo', '1234', 1234568, 'Trabajador', 1),
(9, 'AlexisRivas', '1234', 12345625, 'Administrador', 1),
(10, 'guaro', '12345', 13631637, 'Trabajador', 1);

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

--
-- Volcado de datos para la tabla `vacaciones_y_utilidades`
--

INSERT INTO `vacaciones_y_utilidades` (`vacaciones_id`, `dia_correspondido`, `utilidades`, `t_servicio`, `ini_vacaciones`, `fin_vacaciones`, `ini_laboral`, `dia_descanso`, `dia_feriado`, `dia_otorgado`, `sueldo_diario`, `cedula_FK`, `tasaBCV_FK`, `monto`, `ince`) VALUES
(1, 30, NULL, NULL, '2023-12-02', '2024-01-12', '2024-01-15', 5, 5, 5, 2.33, 28725234, 32, NULL, NULL),
(2, 30, NULL, NULL, '2023-12-02', '2024-01-12', '2024-01-15', 0, 0, 0, 2.33, 28725234, 32, NULL, NULL),
(3, 30, NULL, NULL, '2023-12-02', '2024-01-12', '2024-01-15', 0, 0, 0, 2.33, 28725234, 32, NULL, NULL),
(4, 14, NULL, NULL, '2023-12-22', '2024-01-11', '2024-01-12', 0, 3, 90, 3.33, 30505643, 34, NULL, NULL),
(8, 14, NULL, 0, '2023-12-05', '2023-12-25', '2023-12-26', 0, 0, 0, 3.33, 30505643, 35, 112.65, 0.57),
(9, 14, 0, 0, '2023-12-05', '2023-12-25', '2023-12-26', 6, 0, 0, 3.33, 30505643, 35, 112.65, 0.57),
(10, 30, 16, 0, '2024-12-02', '2025-01-13', '2025-01-14', 12, 0, 0, 2.8, 28725234, 110, 200.59, 1.01),
(11, 30, 16, 0, '2024-12-02', '2025-01-13', '2025-01-14', 12, 0, 0, 2.8, 28725234, 110, 200.59, 1.01),
(12, 14, 0, 0, '2025-01-31', '2025-02-20', '2025-02-21', 6, 0, 0, 4, 11111111, 150, 135.32, 0.68),
(13, 15, 1, 0, '2024-12-20', '2025-01-10', '2025-01-13', 6, 21, 4, 160, 30505643, 197, 211.9, 1.06),
(14, 30, 16, 0, '2025-04-08', '2025-05-20', '2025-05-21', 12, 0, 0, 400, 28725234, 198, 577.9, 2.9),
(15, 15, 0, 1, '2024-12-20', '2025-01-10', '2025-01-13', 6, 4, 21, 160, 30505643, 198, 211.9, 1.06),
(16, 15, 60, 1, '2024-12-20', '2025-01-10', '2025-01-13', 6, 4, 21, 160, 30505643, 198, 211.9, 1.06),
(17, 30, 60, 16, '2024-12-20', '2025-01-31', '2025-02-03', 12, 5, 30, 400, 28725234, 198, 731.13, 3.67),
(18, 30, 0, 16, '2025-12-20', '2026-01-30', '2026-02-02', 11, 0, 0, 130, 13631637, 200, 101.02, 0.51);

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
,`vendedor_nombre` varchar(255)
,`vendedor_apellido` varchar(255)
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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_variacion_nominia`  AS SELECT `i1`.`anio` AS `anio`, `i1`.`mes` AS `mes`, `i1`.`total_pagado` AS `costo_nominia_actual`, `i2`.`total_pagado` AS `costo_nominia_anterior`, (`i1`.`total_pagado` - `i2`.`total_pagado`) / `i2`.`total_pagado` * 100 AS `variacion_porcentual` FROM (`indicadorpagos` `i1` left join `indicadorpagos` `i2` on(`i1`.`anio` = `i2`.`anio` and `i1`.`mes` = `i2`.`mes` + 1 or `i1`.`anio` = `i2`.`anio` + 1 and `i1`.`mes` = 1 and `i2`.`mes` = 12)) ORDER BY `i1`.`anio` DESC, `i1`.`mes` DESC ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_vendedores`
--
DROP TABLE IF EXISTS `vista_vendedores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_vendedores`  AS SELECT year(`nomina`.`fecha`) AS `anio`, month(`nomina`.`fecha`) AS `mes`, sum(`nomina`.`comisiones`) AS `t_comiciones`, `nomina`.`cedula_FK` AS `vendedores`, `empleados`.`nombre` AS `vendedor_nombre`, `empleados`.`apellido` AS `vendedor_apellido` FROM (`nomina` join `empleados` on(`nomina`.`cedula_FK` = `empleados`.`cedula`)) WHERE `nomina`.`estado` = 1 AND `empleados`.`estado` = 1 AND `empleados`.`cargo` = 'Vendedor' GROUP BY year(`nomina`.`fecha`), month(`nomina`.`fecha`), `nomina`.`cedula_FK` ORDER BY `nomina`.`fecha` DESC ;

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
-- Indices de la tabla `cuentas_por_pagar2`
--
ALTER TABLE `cuentas_por_pagar2`
  ADD PRIMARY KEY (`id_cp`),
  ADD KEY `id_prestamo` (`id_prestamo`);

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
  ADD KEY `cuentas_p` (`cuentasp`);

--
-- Indices de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  ADD PRIMARY KEY (`id_prestamos`),
  ADD KEY `cedula_FK` (`cedula_FK`),
  ADD KEY `tasaBCV_FK` (`tasaBCV_FK`),
  ADD KEY `solicitud_FK` (`solicitud_FK`);

--
-- Indices de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD PRIMARY KEY (`id_solicitud`),
  ADD KEY `cedula_FK` (`cedula_FK`);

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
-- AUTO_INCREMENT de la tabla `cuentas_por_pagar2`
--
ALTER TABLE `cuentas_por_pagar2`
  MODIFY `id_cp` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `fideicomiso`
--
ALTER TABLE `fideicomiso`
  MODIFY `id_fideicomiso` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `islr`
--
ALTER TABLE `islr`
  MODIFY `id_islr` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `nomina`
--
ALTER TABLE `nomina`
  MODIFY `id_nomina` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT de la tabla `prestamos`
--
ALTER TABLE `prestamos`
  MODIFY `id_prestamos` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  MODIFY `id_solicitud` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `tasa_dolar`
--
ALTER TABLE `tasa_dolar`
  MODIFY `id_tasa` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=224;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `vacaciones_y_utilidades`
--
ALTER TABLE `vacaciones_y_utilidades`
  MODIFY `vacaciones_id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
-- Filtros para la tabla `cuentas_por_pagar2`
--
ALTER TABLE `cuentas_por_pagar2`
  ADD CONSTRAINT `cuentas_por_pagar2_ibfk_1` FOREIGN KEY (`id_prestamo`) REFERENCES `prestamos` (`id_prestamos`);

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
  ADD CONSTRAINT `prestamos_ibfk_2` FOREIGN KEY (`tasaBCV_FK`) REFERENCES `tasa_dolar` (`id_tasa`),
  ADD CONSTRAINT `prestamos_ibfk_3` FOREIGN KEY (`solicitud_FK`) REFERENCES `solicitudes` (`id_solicitud`);

--
-- Filtros para la tabla `solicitudes`
--
ALTER TABLE `solicitudes`
  ADD CONSTRAINT `solicitudes_ibfk_1` FOREIGN KEY (`cedula_FK`) REFERENCES `empleados` (`cedula`);

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
