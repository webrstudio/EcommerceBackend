-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 01-03-2025 a las 19:13:28
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

DROP TABLE IF EXISTS `administradores`;
CREATE TABLE IF NOT EXISTS `administradores` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `admin_nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `admin_usuario` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `admin_contrasena` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

DROP TABLE IF EXISTS `marcas`;
CREATE TABLE IF NOT EXISTS `marcas` (
  `marca_id` int NOT NULL AUTO_INCREMENT,
  `marca_nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`marca_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`marca_id`, `marca_nombre`) VALUES
(1, 'krolloshow'),
(2, 'mesmosshow');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
CREATE TABLE IF NOT EXISTS `pedidos` (
  `pedido_id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `producto_id` int NOT NULL,
  `pedido_fecha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`pedido_id`),
  KEY `FK_usuario_id` (`usuario_id`),
  KEY `FK_producto_id` (`producto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`pedido_id`, `usuario_id`, `producto_id`, `pedido_fecha`) VALUES
(5, 3, 2, '01/03/2025'),
(6, 3, 2, '01/03/2025'),
(7, 3, 2, '01/03/2025');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

DROP TABLE IF EXISTS `productos`;
CREATE TABLE IF NOT EXISTS `productos` (
  `producto_id` int NOT NULL AUTO_INCREMENT,
  `producto_nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `producto_precio` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `producto_imagen` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `marca_id` int NOT NULL,
  PRIMARY KEY (`producto_id`),
  KEY `FK_marca_id` (`marca_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`producto_id`, `producto_nombre`, `producto_precio`, `producto_imagen`, `marca_id`) VALUES
(2, 'Playera K Rollo Show', '299', 'https://test.webrstudio.com/assets/images/krollo-mockup.png', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `usuario_id` int NOT NULL AUTO_INCREMENT,
  `usuario_nombre` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `usuario_direccion` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `usuario_cp` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `usuario_estado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `usuario_municipio` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `usuario_colonia` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `usuario_email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `usuario_telefono` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `usuario_nombre`, `usuario_direccion`, `usuario_cp`, `usuario_estado`, `usuario_municipio`, `usuario_colonia`, `usuario_email`, `usuario_telefono`) VALUES
(3, 'Juan Pérez', 'Cerro Arenal 27 B', '54190', 'Estado de México', 'Tlalnepantla de Baz', 'Dr. Jorge Jimenez Cantú', 'juan@example.com', '123456789');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `FK_producto_id` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`producto_id`),
  ADD CONSTRAINT `FK_usuario_id` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `FK_marca_id` FOREIGN KEY (`marca_id`) REFERENCES `marcas` (`marca_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
