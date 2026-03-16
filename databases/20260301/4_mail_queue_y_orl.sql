-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-09-2024 a las 13:17:19
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `altiraautomations`
--
DROP TABLE IF EXISTS `mail_queue`;
DROP TABLE IF EXISTS `orl`;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mail_queue`
--

CREATE TABLE `mail_queue` (
  `id` int(11) NOT NULL,
  `send` datetime DEFAULT NULL,
  `priority` int(11) DEFAULT NULL,
  `sent` datetime DEFAULT NULL,
  `to_address` varchar(100) DEFAULT NULL,
  `to_name` varchar(100) DEFAULT NULL,
  `cc_address` varchar(100) DEFAULT NULL,
  `cc_name` varchar(100) DEFAULT NULL,
  `bcc_address` varchar(100) DEFAULT NULL,
  `bcc_name` varchar(100) DEFAULT NULL,
  `from_address` varchar(100) DEFAULT NULL,
  `from_name` varchar(100) DEFAULT NULL,
  `template` varchar(50) DEFAULT NULL,
  `process` varchar(500) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `pre_header` varchar(255) DEFAULT NULL,
  `locale` varchar(10) DEFAULT NULL,
  `message` longtext DEFAULT NULL,
  `headers` varchar(255) DEFAULT NULL,
  `images` longtext DEFAULT NULL,
  `assign_vars` longtext DEFAULT NULL,
  `block_name` varchar(100) DEFAULT NULL,
  `assign_block_vars` longtext DEFAULT NULL,
  `attached` longtext DEFAULT NULL,
  `token` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orl`
--

CREATE TABLE `orl` (
  `id` int(11) NOT NULL,
  `createdby` int(11) DEFAULT NULL,
  `createddate` datetime DEFAULT NULL,
  `entity` varchar(255) DEFAULT NULL,
  `old` longtext DEFAULT NULL,
  `new` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Indices de la tabla `mail_queue`
--
ALTER TABLE `mail_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `orl`
--
ALTER TABLE `orl`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de la tabla `mail_queue`
--
ALTER TABLE `mail_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `orl`
--
ALTER TABLE `orl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
