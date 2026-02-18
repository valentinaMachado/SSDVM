-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3308
-- Tiempo de generación: 18-02-2026 a las 18:19:32
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
-- Base de datos: `vm_ssd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vm_carrito`
--

CREATE TABLE `vm_carrito` (
  `ID_Carrito` int(11) NOT NULL,
  `ID_Usuario` int(11) NOT NULL,
  `ID_Producto` int(11) NOT NULL,
  `Nombre` varchar(255) NOT NULL,
  `Precio` decimal(10,2) NOT NULL,
  `Cantidad` int(11) NOT NULL,
  `Fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vm_orden`
--

CREATE TABLE `vm_orden` (
  `ID_Pedido` int(11) NOT NULL,
  `Nombre` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `Direccion` varchar(255) DEFAULT NULL,
  `Fecha` datetime DEFAULT current_timestamp(),
  `Estado` enum('Pendiente','Completado') DEFAULT 'Pendiente',
  `Total` decimal(10,2) DEFAULT 0.00,
  `ID_Usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `vm_orden`
--

INSERT INTO `vm_orden` (`ID_Pedido`, `Nombre`, `Email`, `Telefono`, `Direccion`, `Fecha`, `Estado`, `Total`, `ID_Usuario`) VALUES
(1, 'valentina', 'vale@gmail.com', '3124567733', 'CL 31 A    N° 27- 39', '2026-02-17 21:17:37', 'Completado', 20000.00, 1),
(2, 'lukas', 'lukas@gmail.com', '3124567733', 'CL 31 A    N° 27- 39', '2026-02-17 21:22:34', 'Pendiente', 5000.00, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vm_pedidodetalle`
--

CREATE TABLE `vm_pedidodetalle` (
  `ID_Detalle` int(11) NOT NULL,
  `ID_Pedido` int(11) NOT NULL,
  `NombreProducto` varchar(100) NOT NULL,
  `Precio` decimal(10,2) NOT NULL,
  `Cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `vm_pedidodetalle`
--

INSERT INTO `vm_pedidodetalle` (`ID_Detalle`, `ID_Pedido`, `NombreProducto`, `Precio`, `Cantidad`) VALUES
(1, 1, 'POLLO', 20000.00, 1),
(2, 2, 'PIZZA', 5000.00, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vm_producto`
--

CREATE TABLE `vm_producto` (
  `ID_Producto` int(11) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Descripcion` varchar(255) NOT NULL,
  `Precio` decimal(10,2) NOT NULL,
  `Categoria` varchar(50) NOT NULL,
  `Imagen` varchar(255) DEFAULT NULL,
  `Fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `vm_producto`
--

INSERT INTO `vm_producto` (`ID_Producto`, `Nombre`, `Descripcion`, `Precio`, `Categoria`, `Imagen`, `Fecha_creacion`) VALUES
(8, 'SALPICON', 'Refrescante y deliciosa mezcla de frutas frescas picadas, y dulces bañadas en jugo natural y ligeramente endulzadas.', 15000.00, 'Bebidas', '1770837325_img31.jpg.png', '2026-02-11 19:15:25'),
(9, 'MULTICOLOR', 'Combinación de intensos tonos vibrantes y sabores frutales cuidadosamente seleccionados crea una mezcla irresistible que no solo hidrata, sino que también sorprende visualmente.', 9000.00, 'Bebidas', '1770837633_1770835768_img19.jpg.png', '2026-02-11 19:20:33'),
(10, 'TRIO FUSION', 'Bebidas refrescantes y vibrantes, preparadas con ingredientes frescos y servidas con hielo para realzar su sabor y frescura. Una combinación de colores intensos y aromas frutales que invitan a disfrutar cada sorbo, perfectas para acompañar cualquier ocasi', 25000.00, 'Bebidas', '1770837967_1759424838_5e7f16846813.png', '2026-02-11 19:26:07'),
(11, 'POLLO', 'Plato principal cuidadosamente preparado, con presentación elegante y apetitosa. Una opción jugosa y llena de sabor, acompañada de ingredientes frescos que realzan su aroma y textura, ideal para disfrutar de una experiencia gastronómica reconfortante y de', 20000.00, 'Comida rápida', '1770838255_1755651035_home-img-3.png', '2026-02-11 19:30:55'),
(12, 'PERRO', 'Deliciosa especialidad horneada, preparada con pan suave y dorado, cubierta con ingredientes seleccionados y una generosa capa que resalta su sabor y textura. Una opción abundante y llena de sabor, perfecta para disfrutar en cualquier momento del día.', 15000.00, 'Comida rápida', '1770838328_1755654236_img2.jpg.png', '2026-02-11 19:32:08'),
(13, 'HAMBURGUESA MAX ', 'Hamburguesa artesanal preparada con pan suave y dorado, jugosa carne a la parrilla y una combinación equilibrada de ingredientes frescos que realzan su sabor. Una opción clásica y deliciosa, perfecta para quienes buscan disfrutar de una experiencia abunda', 25000.00, 'Comida rápida', '1770838391_1755652900_img1.jpg.png', '2026-02-11 19:33:11'),
(14, 'PIZZA', 'pizza jugosa, de alta calidad, con verduras frescas ', 5000.00, 'Comida rápida', '1771381260_img3.jpg.png', '2026-02-18 02:21:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vm_usuario`
--

CREATE TABLE `vm_usuario` (
  `ID_Usuario` int(11) NOT NULL,
  `Tipo_usuario` varchar(20) DEFAULT NULL,
  `Nombre` varchar(45) NOT NULL,
  `Email` varchar(45) NOT NULL,
  `Contrasena` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `vm_usuario`
--

INSERT INTO `vm_usuario` (`ID_Usuario`, `Tipo_usuario`, `Nombre`, `Email`, `Contrasena`) VALUES
(1, 'CLIENTE', 'valentina', 'vale@gmail.com', '$2y$10$93mQdikBMmdgqg5KWWsUx.g7PLFQndn48iQlMLZNipF6OKQivIzmK'),
(2, 'ADMINISTRADOR', 'paola', 'paola@gmail.com', '$2y$10$jNPRdb80iLQ/xEsbp2zPsuFgK46mjE.sTJWy5R/8z7Wyqz52HJyxu'),
(3, 'CLIENTE', 'Lukas ', 'lukas@gmail.com', '$2y$10$cCa4vChtlH5Ik108BqxcCe6fgksaEPc6uBWdSxR36hGG.mQGmUx0a');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `vm_carrito`
--
ALTER TABLE `vm_carrito`
  ADD PRIMARY KEY (`ID_Carrito`);

--
-- Indices de la tabla `vm_orden`
--
ALTER TABLE `vm_orden`
  ADD PRIMARY KEY (`ID_Pedido`);

--
-- Indices de la tabla `vm_pedidodetalle`
--
ALTER TABLE `vm_pedidodetalle`
  ADD PRIMARY KEY (`ID_Detalle`),
  ADD KEY `ID_Pedido` (`ID_Pedido`);

--
-- Indices de la tabla `vm_producto`
--
ALTER TABLE `vm_producto`
  ADD PRIMARY KEY (`ID_Producto`);

--
-- Indices de la tabla `vm_usuario`
--
ALTER TABLE `vm_usuario`
  ADD PRIMARY KEY (`ID_Usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `vm_carrito`
--
ALTER TABLE `vm_carrito`
  MODIFY `ID_Carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `vm_orden`
--
ALTER TABLE `vm_orden`
  MODIFY `ID_Pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `vm_pedidodetalle`
--
ALTER TABLE `vm_pedidodetalle`
  MODIFY `ID_Detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `vm_producto`
--
ALTER TABLE `vm_producto`
  MODIFY `ID_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `vm_usuario`
--
ALTER TABLE `vm_usuario`
  MODIFY `ID_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `vm_pedidodetalle`
--
ALTER TABLE `vm_pedidodetalle`
  ADD CONSTRAINT `vm_pedidodetalle_ibfk_1` FOREIGN KEY (`ID_Pedido`) REFERENCES `vm_orden` (`ID_Pedido`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
