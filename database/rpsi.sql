-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-04-2018 a las 02:34:44
-- Versión del servidor: 10.1.30-MariaDB
-- Versión de PHP: 5.6.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `rpsi`
--
CREATE DATABASE IF NOT EXISTS `rpsi` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `rpsi`;

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `getmenu`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getmenu` (IN `_id_rol` INT)  BEGIN
    
	SELECT m.* 
	FROM rol_menu rm, menu m
	where rm.id_rol = _id_rol
	and m.id = rm.id_menu
	UNION
	SELECT m.* 
	FROM menu m
	where m.id in (SELECT DISTINCT m2.padre 
	FROM rol_menu rm2, menu m2
	where rm2.id_rol = _id_rol
	and m2.id = rm2.id_menu)
	order by id_grupo, orden;
	
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

DROP TABLE IF EXISTS `empleado`;
CREATE TABLE `empleado` (
  `id` int(11) NOT NULL,
  `id_tipo_documento` int(2) NOT NULL,
  `documento_identidad` int(10) NOT NULL,
  `nombre` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `apellido` varchar(255) CHARACTER SET latin1 COLLATE latin1_spanish_ci NOT NULL,
  `id_cargo` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id`, `id_tipo_documento`, `documento_identidad`, `nombre`, `apellido`, `id_cargo`) VALUES
(1, 1, 14017972, 'Jose Manuel', 'Martinez', 1),
(2, 1, 15666972, 'Carlos', 'Rodriguez', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo_menu`
--

DROP TABLE IF EXISTS `grupo_menu`;
CREATE TABLE `grupo_menu` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `grupo_menu`
--

INSERT INTO `grupo_menu` (`id`, `nombre`) VALUES
(1, 'Registro Prueba MV'),
(2, 'Reinicio Prueba MV'),
(3, 'Generación de Reportes'),
(4, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) CHARACTER SET latin1 NOT NULL,
  `url` varchar(255) CHARACTER SET latin1 NOT NULL,
  `nivel` int(10) NOT NULL,
  `padre` int(10) NOT NULL,
  `id_grupo` int(2) NOT NULL,
  `orden` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`id`, `nombre`, `url`, `nivel`, `padre`, `id_grupo`, `orden`) VALUES
(1, 'Registro Prueba MV', '#', 0, 0, 1, 0),
(2, 'Item 1', '#', 1, 1, 1, 1),
(3, 'Reinicio Prueba MV', '#', 0, 0, 2, 0),
(4, 'Item 1', '#', 1, 3, 2, 1),
(5, 'Generación de Reportes', '#', 0, 0, 3, 0),
(6, 'Item 1', '#', 1, 5, 3, 1),
(7, 'Usuarios', '#', 0, 0, 4, 0),
(8, 'Gestionar Usuarios', '#', 1, 7, 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

DROP TABLE IF EXISTS `rol`;
CREATE TABLE `rol` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) CHARACTER SET latin1 NOT NULL,
  `descripcion` varchar(100) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id`, `nombre`, `descripcion`) VALUES
(1, 'GERENTE ADMINISTRADOR', 'GERENTE ADMINISTRADOR'),
(2, 'OPERADOR', 'OPERADOR');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol_menu`
--

DROP TABLE IF EXISTS `rol_menu`;
CREATE TABLE `rol_menu` (
  `id_rol` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `rol_menu`
--

INSERT INTO `rol_menu` (`id_rol`, `id_menu`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(2, 5),
(2, 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento_identidad`
--

DROP TABLE IF EXISTS `tipo_documento_identidad`;
CREATE TABLE `tipo_documento_identidad` (
  `id` int(2) NOT NULL,
  `nombre` char(1) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL,
  `descripcion` varchar(50) CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tipo_documento_identidad`
--

INSERT INTO `tipo_documento_identidad` (`id`, `nombre`, `descripcion`) VALUES
(1, 'V', 'Venezolano'),
(2, 'E', 'Extranjero'),
(3, 'P', 'Pasaporte'),
(4, 'J', 'Juridico'),
(5, 'G', 'Gobierno');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `correo` varchar(100) CHARACTER SET latin1 NOT NULL,
  `clave` varchar(255) CHARACTER SET latin1 NOT NULL,
  `id_empleado` int(10) NOT NULL,
  `estatus` varchar(50) CHARACTER SET latin1 NOT NULL,
  `id_rol` int(4) NOT NULL,
  `fecha_hora_ultima_conexion` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `correo`, `clave`, `id_empleado`, `estatus`, `id_rol`, `fecha_hora_ultima_conexion`) VALUES
(1, 'admin@admin.com', '$2y$10$y8Xh/VjDgsCDem.9tx9KOOinKYXLwODsg1AxsZcYxd4tX8AnMwUvi', 1, 'activo', 1, '0000-00-00 00:00:00'),
(2, 'operador@operador.com', '$2y$10$y8Xh/VjDgsCDem.9tx9KOOinKYXLwODsg1AxsZcYxd4tX8AnMwUvi', 2, 'activo', 2, '0000-00-00 00:00:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `documento_identidad` (`documento_identidad`),
  ADD KEY `id_tipo_documento` (`id_tipo_documento`),
  ADD KEY `documento_identidad_2` (`documento_identidad`);

--
-- Indices de la tabla `grupo_menu`
--
ALTER TABLE `grupo_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_grupo` (`id_grupo`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rol_menu`
--
ALTER TABLE `rol_menu`
  ADD PRIMARY KEY (`id_rol`,`id_menu`),
  ADD KEY `id_rol` (`id_rol`,`id_menu`),
  ADD KEY `id_menu` (`id_menu`);

--
-- Indices de la tabla `tipo_documento_identidad`
--
ALTER TABLE `tipo_documento_identidad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `grupo_menu`
--
ALTER TABLE `grupo_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_documento_identidad`
--
ALTER TABLE `tipo_documento_identidad`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`id_tipo_documento`) REFERENCES `tipo_documento_identidad` (`id`);

--
-- Filtros para la tabla `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`id_grupo`) REFERENCES `grupo_menu` (`id`);

--
-- Filtros para la tabla `rol_menu`
--
ALTER TABLE `rol_menu`
  ADD CONSTRAINT `rol_menu_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id`),
  ADD CONSTRAINT `rol_menu_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id`),
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
