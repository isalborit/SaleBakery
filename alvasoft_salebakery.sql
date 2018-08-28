-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-08-2018 a las 10:08:34
-- Versión del servidor: 10.1.31-MariaDB
-- Versión de PHP: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `alvasoft_salebakery`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_acceso`
--

CREATE TABLE `tbl_acceso` (
  `acceso_id` int(11) NOT NULL,
  `acceso_registro` datetime NOT NULL,
  `usu_id` int(1) NOT NULL,
  `acceso_empleado` int(1) NOT NULL,
  `acceso_usuario` int(1) NOT NULL,
  `acceso_cliente` int(1) NOT NULL,
  `acceso_proveedor` int(1) NOT NULL,
  `acceso_producto` int(1) NOT NULL,
  `acceso_ventas` int(1) NOT NULL,
  `acceso_compras` int(1) NOT NULL,
  `acceso_nota_credito` int(1) NOT NULL,
  `acceso_reporte` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_acceso`
--

INSERT INTO `tbl_acceso` (`acceso_id`, `acceso_registro`, `usu_id`, `acceso_empleado`, `acceso_usuario`, `acceso_cliente`, `acceso_proveedor`, `acceso_producto`, `acceso_ventas`, `acceso_compras`, `acceso_nota_credito`, `acceso_reporte`) VALUES
(1, '2017-08-16 00:00:00', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_area`
--

CREATE TABLE `tbl_area` (
  `area_id` int(11) NOT NULL,
  `area_nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_area`
--

INSERT INTO `tbl_area` (`area_id`, `area_nombre`) VALUES
(1, 'Administracion'),
(2, 'Almacen'),
(3, 'Caja'),
(4, 'Contabilidad'),
(5, 'Ventas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_categoria`
--

CREATE TABLE `tbl_categoria` (
  `categ_id` int(11) NOT NULL,
  `categ_valor` varchar(50) NOT NULL,
  `categ_img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_categoria`
--

INSERT INTO `tbl_categoria` (`categ_id`, `categ_valor`, `categ_img`) VALUES
(1, 'PANADERIA', 'UEFOQURFUklB1534694598.png'),
(2, 'PASTELERIA', 'UEFTVEVMRVJJQQ1534694624.png'),
(3, 'BEBIDAS', 'QkVCSURBUw1534694634.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_cliente`
--

CREATE TABLE `tbl_cliente` (
  `cli_id` int(11) NOT NULL,
  `cli_registro` datetime NOT NULL,
  `cli_documento` varchar(11) NOT NULL,
  `cli_tipo_doc_sunat` int(1) NOT NULL,
  `cli_nombre` text NOT NULL,
  `cli_direccion` varchar(200) DEFAULT NULL,
  `cli_telefono` varchar(12) DEFAULT NULL,
  `cli_estado` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_cliente`
--

INSERT INTO `tbl_cliente` (`cli_id`, `cli_registro`, `cli_documento`, `cli_tipo_doc_sunat`, `cli_nombre`, `cli_direccion`, `cli_telefono`, `cli_estado`) VALUES
(1, '2018-01-01 00:00:00', '11111111', 0, 'Cliente Varios', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_compra`
--

CREATE TABLE `tbl_compra` (
  `compra_id` int(11) NOT NULL,
  `compra_serie` varchar(8) NOT NULL,
  `compra_numero` varchar(12) NOT NULL,
  `compra_tipo` char(1) NOT NULL,
  `compra_fecha` datetime NOT NULL,
  `compra_moneda` char(3) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `movimiento_id` int(11) NOT NULL,
  `credito_id` int(11) NOT NULL,
  `contado_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `igv_porcentaje` decimal(9,2) NOT NULL,
  `compra_estado` int(1) NOT NULL,
  `compra_subtotal` decimal(9,3) NOT NULL,
  `compra_descuento` decimal(9,3) NOT NULL,
  `compra_igv` decimal(9,3) NOT NULL,
  `compra_neto` decimal(9,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_compra_detalle`
--

CREATE TABLE `tbl_compra_detalle` (
  `detalle_id` int(11) NOT NULL,
  `compra_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `detalle_precio` decimal(9,3) NOT NULL,
  `detalle_precio_igv` decimal(9,3) NOT NULL,
  `detalle_precio_transporte` decimal(9,3) NOT NULL,
  `detalle_precio_compra` decimal(9,3) NOT NULL,
  `detalle_precio_compra_gasto` decimal(9,3) NOT NULL,
  `detalle_porc_min` decimal(9,2) NOT NULL,
  `detalle_porc_max` decimal(9,2) NOT NULL,
  `detalle_precio_min` decimal(9,3) NOT NULL,
  `detalle_precio_max` decimal(9,3) NOT NULL,
  `detalle_unidad` varchar(20) NOT NULL,
  `detalle_cantidad` decimal(9,3) NOT NULL,
  `detalle_descuento` decimal(9,2) NOT NULL,
  `detalle_impuesto` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_configuracion`
--

CREATE TABLE `tbl_configuracion` (
  `config_id` int(11) NOT NULL,
  `config_valor` varchar(50) NOT NULL,
  `config_descripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_configuracion`
--

INSERT INTO `tbl_configuracion` (`config_id`, `config_valor`, `config_descripcion`) VALUES
(1, 'empresa_nombre', 'Mister Pan'),
(2, 'empresa_correo', 'www.misterpan.com.pe'),
(3, 'proveedor_nombre', 'Corp. AlvaSoft SAC'),
(4, 'sistema_nombre', 'Sistema de Ventas'),
(5, 'sistema_titulo', 'SaleBakery'),
(6, 'sistema_version', 'v 1.0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_contado`
--

CREATE TABLE `tbl_contado` (
  `contado_id` int(11) NOT NULL,
  `contado_registro` datetime NOT NULL,
  `contado_descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_contado`
--

INSERT INTO `tbl_contado` (`contado_id`, `contado_registro`, `contado_descripcion`) VALUES
(1, '2017-10-14 00:00:00', 'Efectivo'),
(2, '2017-10-14 00:00:00', 'Cheque'),
(3, '2017-10-14 00:00:00', 'Tarjeta de credito'),
(4, '2017-10-14 00:00:00', 'Tarjeta de Debito');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_credito`
--

CREATE TABLE `tbl_credito` (
  `credito_id` int(11) NOT NULL,
  `credito_registro` datetime NOT NULL,
  `credito_descripcion` varchar(50) NOT NULL,
  `credito_numero_dias` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_credito`
--

INSERT INTO `tbl_credito` (`credito_id`, `credito_registro`, `credito_descripcion`, `credito_numero_dias`) VALUES
(1, '2018-01-01 00:00:00', 'Credito a 8 dias', 8),
(2, '2018-01-01 00:00:00', 'Credito a 15 dias', 15),
(3, '2018-01-01 00:00:00', 'Credito a 30 dias', 30),
(4, '2018-01-01 00:00:00', 'Credito a 45 dias', 45),
(5, '2018-01-01 00:00:00', 'Credito a 60 dias', 60),
(6, '2018-01-01 00:00:00', 'Credito a 90 dias', 90),
(7, '2018-01-01 00:00:00', 'Credito a 120 dias', 120);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_datos_economicos`
--

CREATE TABLE `tbl_datos_economicos` (
  `datos_id` int(11) NOT NULL,
  `datos_gasto_mensual` decimal(9,2) NOT NULL,
  `datos_impuesto_renta` decimal(9,2) NOT NULL,
  `datos_porcentaje_gastos` decimal(9,3) NOT NULL,
  `datos_tipo_cambio` decimal(9,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_datos_economicos`
--

INSERT INTO `tbl_datos_economicos` (`datos_id`, `datos_gasto_mensual`, `datos_impuesto_renta`, `datos_porcentaje_gastos`, `datos_tipo_cambio`) VALUES
(1, '850.00', '2.00', '0.001', '3.24');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_empleado`
--

CREATE TABLE `tbl_empleado` (
  `emp_id` int(11) NOT NULL,
  `emp_registro` datetime NOT NULL,
  `emp_documento` char(8) NOT NULL,
  `emp_nombre` varchar(50) NOT NULL,
  `emp_apellido` varchar(50) NOT NULL,
  `emp_direccion` varchar(200) NOT NULL,
  `emp_telefono` varchar(12) NOT NULL,
  `emp_sexo` char(1) NOT NULL,
  `temp_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL,
  `emp_estado` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_empleado`
--

INSERT INTO `tbl_empleado` (`emp_id`, `emp_registro`, `emp_documento`, `emp_nombre`, `emp_apellido`, `emp_direccion`, `emp_telefono`, `emp_sexo`, `temp_id`, `area_id`, `emp_estado`) VALUES
(1, '2017-11-08 18:12:30', '70788635', 'Edgar', 'Alvarez', 'Nuevo Ilo', '953901454', 'M', 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_igv`
--

CREATE TABLE `tbl_igv` (
  `igv_id` int(11) NOT NULL,
  `igv_registro` datetime NOT NULL,
  `igv_porcentaje` decimal(10,2) NOT NULL,
  `igv_estado` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_igv`
--

INSERT INTO `tbl_igv` (`igv_id`, `igv_registro`, `igv_porcentaje`, `igv_estado`) VALUES
(1, '2018-01-01 00:00:00', '0.18', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_marca`
--

CREATE TABLE `tbl_marca` (
  `marca_id` int(11) NOT NULL,
  `marca_nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_marca`
--

INSERT INTO `tbl_marca` (`marca_id`, `marca_nombre`) VALUES
(1, 'MISTER PAN'),
(2, 'BIMBO'),
(3, 'COCA COLA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_movimiento`
--

CREATE TABLE `tbl_movimiento` (
  `mov_id` int(11) NOT NULL,
  `mov_registro` datetime NOT NULL,
  `mov_detalle` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_movimiento`
--

INSERT INTO `tbl_movimiento` (`mov_id`, `mov_registro`, `mov_detalle`) VALUES
(1, '2017-10-14 00:00:00', 'Contado'),
(2, '2017-10-14 00:00:00', 'Credito');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_nota`
--

CREATE TABLE `tbl_nota` (
  `nota_id` int(11) NOT NULL,
  `nota_fecha` datetime NOT NULL,
  `nota_tipo` enum('FC','BC') NOT NULL,
  `nota_serie` varchar(4) NOT NULL,
  `nota_numero` varchar(8) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `nota_motivo` text NOT NULL,
  `tipo_doc_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `nota_sunat_hash` varchar(50) NOT NULL,
  `nota_sunat_codigo` varchar(2) NOT NULL,
  `nota_sunat_respuesta` varchar(100) NOT NULL,
  `nota_pdf_html` text NOT NULL,
  `nota_xml_html` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_producto`
--

CREATE TABLE `tbl_producto` (
  `prod_id` int(11) NOT NULL,
  `prod_codigo` varchar(12) NOT NULL,
  `prod_nombre` varchar(200) NOT NULL,
  `prod_unidad` varchar(20) NOT NULL,
  `prod_precio_compra` decimal(9,2) NOT NULL,
  `prod_precio_transporte` decimal(9,2) NOT NULL,
  `prod_precio_gastos` decimal(9,2) NOT NULL,
  `prod_precio_vp1` decimal(9,2) NOT NULL,
  `prod_precio_vp2` decimal(9,2) NOT NULL,
  `prod_precio_venta` decimal(9,2) NOT NULL,
  `prod_stock_min` decimal(9,3) NOT NULL,
  `prod_stock_real` decimal(9,3) NOT NULL,
  `marca_id` int(11) NOT NULL,
  `categ_id` int(11) NOT NULL,
  `prod_referencia` int(11) NOT NULL,
  `prod_estado` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_proveedor`
--

CREATE TABLE `tbl_proveedor` (
  `prov_id` int(11) NOT NULL,
  `prov_registro` datetime NOT NULL,
  `prov_documento` varchar(11) NOT NULL,
  `prov_nombre` varchar(200) NOT NULL,
  `prov_direccion` varchar(200) NOT NULL,
  `prov_telefono` varchar(12) NOT NULL,
  `prov_correo` varchar(100) NOT NULL,
  `prov_estado` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_serie`
--

CREATE TABLE `tbl_serie` (
  `serie_id` int(11) NOT NULL,
  `serie_venta_factura` varchar(4) NOT NULL,
  `serie_venta_boleta` varchar(4) NOT NULL,
  `serie_venta_alternativa` varchar(4) NOT NULL,
  `serie_nota_credito_factura` varchar(4) NOT NULL,
  `serie_nota_credito_boleta` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_serie`
--

INSERT INTO `tbl_serie` (`serie_id`, `serie_venta_factura`, `serie_venta_boleta`, `serie_venta_alternativa`, `serie_nota_credito_factura`, `serie_nota_credito_boleta`) VALUES
(1, 'F001', 'B001', 'A001', 'FC01', 'BC01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_tipo_documento`
--

CREATE TABLE `tbl_tipo_documento` (
  `tipo_doc_id` int(11) NOT NULL,
  `tipo_doc_registro` datetime NOT NULL,
  `tipo_doc_codigo` varchar(2) NOT NULL,
  `tipo_doc_nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_tipo_documento`
--

INSERT INTO `tbl_tipo_documento` (`tipo_doc_id`, `tipo_doc_registro`, `tipo_doc_codigo`, `tipo_doc_nombre`) VALUES
(1, '2018-02-07 00:00:00', '01', 'Factura'),
(2, '2018-02-07 00:00:00', '03', 'Boleta de venta'),
(3, '2018-02-07 00:00:00', '07', 'Nota de Credito');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_tipo_empleado`
--

CREATE TABLE `tbl_tipo_empleado` (
  `temp_id` int(11) NOT NULL,
  `temp_valor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_tipo_empleado`
--

INSERT INTO `tbl_tipo_empleado` (`temp_id`, `temp_valor`) VALUES
(1, 'Administrador'),
(2, 'Almacenero'),
(3, 'Cajero'),
(4, 'Vendedor'),
(5, 'Contador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuario`
--

CREATE TABLE `tbl_usuario` (
  `usu_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `usu_registro` datetime NOT NULL,
  `usu_nombre` varchar(20) NOT NULL,
  `usu_clave` text NOT NULL,
  `usu_perfil` text NOT NULL,
  `usu_rol` varchar(5) NOT NULL,
  `usu_estado` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tbl_usuario`
--

INSERT INTO `tbl_usuario` (`usu_id`, `emp_id`, `usu_registro`, `usu_nombre`, `usu_clave`, `usu_perfil`, `usu_rol`, `usu_estado`) VALUES
(1, 1, '2018-01-01 00:00:00', 'admin', 'VZlSzZlVsNnUsRGVU1GeXVGSOhVVB1TP', '', 'ADMIN', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_venta`
--

CREATE TABLE `tbl_venta` (
  `venta_id` int(11) NOT NULL,
  `venta_serie` varchar(4) NOT NULL,
  `venta_numero` varchar(8) NOT NULL,
  `venta_tipo` char(1) NOT NULL,
  `venta_fecha` datetime NOT NULL,
  `venta_moneda` char(3) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `movimiento_id` int(11) NOT NULL,
  `credito_id` int(11) NOT NULL,
  `contado_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `igv_porcentaje` decimal(9,2) NOT NULL,
  `venta_estado` int(1) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `venta_tipo_documento` int(1) NOT NULL,
  `venta_monto_recibido` decimal(9,2) NOT NULL,
  `venta_cancelada` int(1) NOT NULL,
  `venta_subtotal` decimal(9,2) NOT NULL,
  `venta_descuento` decimal(9,2) NOT NULL,
  `venta_igv` decimal(9,2) NOT NULL,
  `venta_neto` decimal(9,2) NOT NULL,
  `venta_sunat_hash` varchar(50) DEFAULT NULL,
  `venta_sunat_codigo` varchar(2) DEFAULT NULL,
  `venta_sunat_respuesta` varchar(100) DEFAULT NULL,
  `venta_pdf_html` text,
  `venta_xml_html` text,
  `venta_numero_detalles` int(11) DEFAULT NULL,
  `venta_web` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_venta_detalle`
--

CREATE TABLE `tbl_venta_detalle` (
  `detalle_id` int(11) NOT NULL,
  `venta_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `detalle_precio` decimal(9,2) NOT NULL,
  `detalle_precio_sin_igv` decimal(9,2) NOT NULL,
  `detalle_unidad` varchar(20) NOT NULL,
  `detalle_cantidad` decimal(9,3) NOT NULL,
  `detalle_descuento` decimal(9,2) NOT NULL,
  `detalle_descuento_sin_igv` decimal(9,2) NOT NULL,
  `detalle_impuesto` int(1) NOT NULL,
  `detalle_referencia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbl_acceso`
--
ALTER TABLE `tbl_acceso`
  ADD PRIMARY KEY (`acceso_id`);

--
-- Indices de la tabla `tbl_area`
--
ALTER TABLE `tbl_area`
  ADD PRIMARY KEY (`area_id`);

--
-- Indices de la tabla `tbl_categoria`
--
ALTER TABLE `tbl_categoria`
  ADD PRIMARY KEY (`categ_id`);

--
-- Indices de la tabla `tbl_cliente`
--
ALTER TABLE `tbl_cliente`
  ADD PRIMARY KEY (`cli_id`);

--
-- Indices de la tabla `tbl_compra`
--
ALTER TABLE `tbl_compra`
  ADD PRIMARY KEY (`compra_id`);

--
-- Indices de la tabla `tbl_compra_detalle`
--
ALTER TABLE `tbl_compra_detalle`
  ADD PRIMARY KEY (`detalle_id`);

--
-- Indices de la tabla `tbl_configuracion`
--
ALTER TABLE `tbl_configuracion`
  ADD PRIMARY KEY (`config_id`);

--
-- Indices de la tabla `tbl_contado`
--
ALTER TABLE `tbl_contado`
  ADD PRIMARY KEY (`contado_id`);

--
-- Indices de la tabla `tbl_credito`
--
ALTER TABLE `tbl_credito`
  ADD PRIMARY KEY (`credito_id`);

--
-- Indices de la tabla `tbl_datos_economicos`
--
ALTER TABLE `tbl_datos_economicos`
  ADD PRIMARY KEY (`datos_id`);

--
-- Indices de la tabla `tbl_empleado`
--
ALTER TABLE `tbl_empleado`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indices de la tabla `tbl_igv`
--
ALTER TABLE `tbl_igv`
  ADD PRIMARY KEY (`igv_id`);

--
-- Indices de la tabla `tbl_marca`
--
ALTER TABLE `tbl_marca`
  ADD PRIMARY KEY (`marca_id`);

--
-- Indices de la tabla `tbl_movimiento`
--
ALTER TABLE `tbl_movimiento`
  ADD PRIMARY KEY (`mov_id`);

--
-- Indices de la tabla `tbl_nota`
--
ALTER TABLE `tbl_nota`
  ADD PRIMARY KEY (`nota_id`);

--
-- Indices de la tabla `tbl_producto`
--
ALTER TABLE `tbl_producto`
  ADD PRIMARY KEY (`prod_id`);

--
-- Indices de la tabla `tbl_proveedor`
--
ALTER TABLE `tbl_proveedor`
  ADD PRIMARY KEY (`prov_id`);

--
-- Indices de la tabla `tbl_serie`
--
ALTER TABLE `tbl_serie`
  ADD PRIMARY KEY (`serie_id`);

--
-- Indices de la tabla `tbl_tipo_documento`
--
ALTER TABLE `tbl_tipo_documento`
  ADD PRIMARY KEY (`tipo_doc_id`);

--
-- Indices de la tabla `tbl_tipo_empleado`
--
ALTER TABLE `tbl_tipo_empleado`
  ADD PRIMARY KEY (`temp_id`);

--
-- Indices de la tabla `tbl_usuario`
--
ALTER TABLE `tbl_usuario`
  ADD PRIMARY KEY (`usu_id`);

--
-- Indices de la tabla `tbl_venta`
--
ALTER TABLE `tbl_venta`
  ADD PRIMARY KEY (`venta_id`);

--
-- Indices de la tabla `tbl_venta_detalle`
--
ALTER TABLE `tbl_venta_detalle`
  ADD PRIMARY KEY (`detalle_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_acceso`
--
ALTER TABLE `tbl_acceso`
  MODIFY `acceso_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_area`
--
ALTER TABLE `tbl_area`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tbl_categoria`
--
ALTER TABLE `tbl_categoria`
  MODIFY `categ_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbl_cliente`
--
ALTER TABLE `tbl_cliente`
  MODIFY `cli_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_compra`
--
ALTER TABLE `tbl_compra`
  MODIFY `compra_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_compra_detalle`
--
ALTER TABLE `tbl_compra_detalle`
  MODIFY `detalle_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_configuracion`
--
ALTER TABLE `tbl_configuracion`
  MODIFY `config_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `tbl_contado`
--
ALTER TABLE `tbl_contado`
  MODIFY `contado_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tbl_credito`
--
ALTER TABLE `tbl_credito`
  MODIFY `credito_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tbl_datos_economicos`
--
ALTER TABLE `tbl_datos_economicos`
  MODIFY `datos_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_empleado`
--
ALTER TABLE `tbl_empleado`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_igv`
--
ALTER TABLE `tbl_igv`
  MODIFY `igv_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_marca`
--
ALTER TABLE `tbl_marca`
  MODIFY `marca_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbl_movimiento`
--
ALTER TABLE `tbl_movimiento`
  MODIFY `mov_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tbl_nota`
--
ALTER TABLE `tbl_nota`
  MODIFY `nota_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_producto`
--
ALTER TABLE `tbl_producto`
  MODIFY `prod_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_proveedor`
--
ALTER TABLE `tbl_proveedor`
  MODIFY `prov_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_serie`
--
ALTER TABLE `tbl_serie`
  MODIFY `serie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_tipo_documento`
--
ALTER TABLE `tbl_tipo_documento`
  MODIFY `tipo_doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tbl_tipo_empleado`
--
ALTER TABLE `tbl_tipo_empleado`
  MODIFY `temp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tbl_usuario`
--
ALTER TABLE `tbl_usuario`
  MODIFY `usu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_venta`
--
ALTER TABLE `tbl_venta`
  MODIFY `venta_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tbl_venta_detalle`
--
ALTER TABLE `tbl_venta_detalle`
  MODIFY `detalle_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
