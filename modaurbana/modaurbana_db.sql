-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-11-2024 a las 15:39:44
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
-- Base de datos: `modaurbana_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `tallas` varchar(255) DEFAULT NULL,
  `colores` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `imagen`, `categoria`, `tallas`, `colores`, `stock`, `fecha_creacion`) VALUES
(30, 'Chaqueta Americana', 'Chaqueta elegante para hombre, ideal para un look sofisticado.', 160.00, 'Chaqueta_Negra.png', 'Hombre', NULL, NULL, 0, '2024-11-18 18:56:41'),
(31, 'Chaqueta Americana', 'Blazer elegante para mujer, estilo moderno y sofisticado.', 160.00, 'Chaqueta_Americana_Marron.png', 'Mujer', NULL, NULL, 0, '2024-11-18 19:11:20'),
(32, 'Camiseta Lisa', 'Camiseta blanca básica para hombre, perfecta para un look casual y cómodo.', 35.00, 'Camiseta_Blanca.png', 'Hombre', NULL, NULL, 0, '2024-11-18 19:18:45'),
(33, 'Camiseta Lisa', 'Camiseta blanca ajustada para mujer, ideal para un estilo casual y fresco.', 35.00, 'Camiseta_Blanca_Mujer.png', 'Mujer', NULL, NULL, 0, '2024-11-18 19:22:38'),
(34, 'Jeans Claros', 'Jeans ajustados de color azul claro para hombre, perfectos para un look casual y moderno.', 80.00, 'Vaqueros_Claros.png', 'Hombre', NULL, NULL, 0, '2024-11-18 19:30:20'),
(35, 'Jeans Azul Clásico ', 'Jeans de corte recto para mujer, en azul clásico. Ideales para un estilo casual y relajado.', 80.00, 'Pantalones.png', 'Mujer', NULL, NULL, 0, '2024-11-18 19:30:34'),
(36, 'Sandalias Marrones ', 'Sandalias marrones para hombre, con diseño moderno y tiras ajustables. Perfectas para un look casual y cómodo en días cálidos.', 64.99, 'Sandalias_Estilo.png', 'Hombre', NULL, NULL, 0, '2024-11-18 19:44:07'),
(37, 'Sanadalias Beige', 'Sandalias beige para mujer con tiras cruzadas, elegantes y cómodas. Ideales para un look veraniego y fresco.', 70.00, 'Sandalias_Beige.png', 'Mujer', NULL, NULL, 0, '2024-11-18 19:49:50'),
(38, 'Botas Cuero Marrones y Negras', 'Botas de cuero para hombre en tonos marrón y negro, estilo robusto con cordones. Perfectas para un look urbano y resistente.', 149.99, 'Botas_Marrones y Negras.png', 'Hombre', NULL, NULL, 0, '2024-11-18 20:44:33'),
(39, 'Botas Cuero Marrones y Negras', 'Botines de tacón para mujer en marrón y negro, diseño elegante con detalles de cordones. Perfectos para un look sofisticado y moderno.', 170.00, 'Botas_Marrones_y_Negras_Mujer.png', 'Mujer', NULL, NULL, 0, '2024-11-18 20:44:37'),
(40, 'Abrigo ', 'Abrigo largo para hombre en color gris oscuro, con doble botonadura. Perfecto para un look elegante y sofisticado en climas fríos.\r\n\r\n\r\n\r\n\r\n\r\n\r\n', 240.00, 'Abrigo.png', 'Hombre', NULL, NULL, 0, '2024-11-18 20:45:54'),
(42, 'Abrigo ', 'Abrigo gris oscuro para mujer con doble botonadura y cinturón ajustable. Ideal para un look sofisticado y cálido en días fríos.', 210.00, 'Abrigo_mujer.png', 'Mujer', NULL, NULL, 0, '2024-11-18 20:46:04'),
(43, 'Traje de Chaqueta', 'Elegante traje negro para hombre, ideal para ocasiones formales. Incluye chaqueta, chaleco y corbata para un look sofisticado y clásico.', 340.00, 'Traje_Negro.png', 'Hombre', NULL, NULL, 0, '2024-11-18 20:46:04'),
(44, 'Traje de corte holgado', 'Vestido beige de corte holgado para mujer, estilo minimalista y cómodo. Ideal para un look casual y relajado.', 310.00, 'Vestido_Tierra.png', 'Mujer', '', '', 0, '2024-11-18 20:47:55'),
(45, 'Camiseta Dibujo', 'Camiseta blanca con diseño gráfico de estatua y luna, estilo artístico y moderno para mujer. Ideal para un look casual con personalidad.', 19.99, 'Camiseta_Dibujo.png', 'Mujer', NULL, NULL, 0, '2024-11-19 08:50:26'),
(46, 'Camiseta Gris', 'Camiseta gris para hombre con estampado artístico, estilo casual y moderno. Ideal para un look relajado con un toque de diseño.', 19.99, 'Camiseta_Dibujo_Hombre.png', 'Hombre', NULL, NULL, 0, '2024-11-19 08:54:54'),
(47, 'Camisa Blanca', 'Blusa blanca de manga larga con cuello en V, estilo elegante y ligero. Perfecta para un look casual y sofisticado.\r\n\r\n\r\n\r\n\r\n\r\n\r\n', 60.00, 'Camisa Blanca.png', 'Mujer', NULL, NULL, 0, '2024-11-19 08:58:13'),
(48, 'Camisa Blanca', 'Camisa blanca ajustada para hombre, estilo clásico y elegante. Ideal para ocasiones formales o de negocios.', 55.99, 'Camisa_Blanca_Hombre.png', 'Hombre', NULL, NULL, 0, '2024-11-19 08:59:37'),
(49, 'Reloj Minimalista - Mujer', 'Reloj minimalista con correa de cuero negro, diseño elegante y versátil. Ideal para complementar cualquier estilo.', 110.00, 'Reloj_mujer.png', 'Accesorios', NULL, NULL, 0, '2024-11-19 09:03:08'),
(50, 'Reloj de Pulsera - Hombre', 'Reloj de pulsera con correa de cuero marrón y esfera de diseño sofisticado. Ideal para un estilo clásico y elegante.', 139.99, 'Reloj.png', 'Accesorios', NULL, NULL, 0, '2024-11-19 09:04:05'),
(51, 'Bolso Marrón', 'Bolso de cuero marrón con detalles en negro y hebilla dorada, diseño elegante y funcional. Perfecto para complementar cualquier look sofisticado.', 240.00, 'Bolso_Marron.png', 'Accesorios', NULL, NULL, 0, '2024-11-19 09:05:42'),
(52, 'Maletín cuero Marrón - Hombre', 'Maletín de cuero marrón con detalles en negro, ideal para un estilo profesional y elegante. Perfecto para llevar documentos o dispositivos electrónicos con estilo.', 140.00, 'Bolsa_Marron.png', 'Accesorios', NULL, NULL, 0, '2024-11-19 09:07:00'),
(53, 'Gemelos Acero Inoxidable - Hombre', 'Gemelos plateados de diseño clásico para hombre, perfectos para añadir un toque elegante y sofisticado a cualquier atuendo formal.', 14.99, 'Gemelos.png', 'Accesorios', NULL, NULL, 0, '2024-11-19 09:08:49'),
(54, 'Gemelos Acero Inoxidable - Mujer', 'Gemelos cilíndricos plateados con detalles decorativos para mujer, diseño moderno y elegante. Perfectos para un toque sofisticado en cualquier evento formal.', 19.99, 'Gemelos_Mujer.png', 'Accesorios', NULL, NULL, 0, '2024-11-19 09:09:53'),
(55, 'Pendientes Arco de Acero Inoxidable', 'Pendientes largos de diseño geométrico plateado, estilo minimalista y moderno. Perfectos para un look elegante y distintivo.', 29.99, 'Pendientes_Arco.png', 'Accesorios', NULL, NULL, 0, '2024-11-19 09:11:45'),
(56, 'Correa Cuero Marrón - Hombre', 'Cinturón de cuero marrón con hebilla metálica clásica, ideal para un estilo casual o formal. Perfecto para complementar cualquier look.', 29.99, 'Correa_Marrón.png', 'Accesorios', NULL, NULL, 0, '2024-11-19 09:13:23'),
(57, 'Pañuelo Seda Beige - Mujer', 'Pañuelo de seda en tonos beige y gris claro, suave y elegante. Perfecto para añadir un toque sofisticado a cualquier atuendo.', 39.99, 'Pañuelo_Marrón_Claro.png', 'Accesorios', NULL, NULL, 0, '2024-11-19 09:27:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL COMMENT '''usuario'',''admin''',
  `imagen` varchar(255) DEFAULT 'default.png',
  `confirmado` tinyint(1) DEFAULT 0,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellidos`, `email`, `password`, `rol`, `imagen`, `confirmado`, `fecha_registro`) VALUES
(1, 'Jorge', 'Romero Ariza', 'jorgmery@hotmail.com', '$2y$10$KjvWoKy7SWs6.xBV.BVlueKv/totV6gwg2H/uBRD7F5.8.NOTa0xG', 'admin', 'JRA.jpg', 0, '2024-11-06 21:07:21'),
(25, 'M.ª de los Ángeles', 'Macias Perez', 'jorgeromeroaiza@gmail.com', '$2y$10$fzBiTWiKGhUp7Oj5lSh9M.dLpX5bLtFK0g11eNQfxuC2KFluBqzOi', 'usuario', 'default.png', 1, '2024-11-19 11:28:23');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
