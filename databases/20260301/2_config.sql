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

-- use `altiraautomations`;

DROP TABLE IF EXISTS `config`;
--
-- Estructura de tabla para la tabla `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `config_name` varchar(50) NOT NULL,
  `config_value` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `config`
--

INSERT INTO `config` (`id`, `config_name`, `config_value`) VALUES
( 1, 'web_enabled', '1'),
( 2, 'web_domain', 'mielsandonis.com'),
( 3, 'web_name', 'Miel Sandonis'),
( 4, 'web_name_long', 'Miel artesanal de los Pinares de Portillo (Valldolid)'),
( 5, 'web_description', 'Miel artesanal de los Pinares de Portillo (Valldolid)'),
( 6, 'web_info_email', 'info@mielsandonis.com'),
( 7, 'web_info_phone', '+34 555 55 55 55'),
( 8, 'web_copy_right', '2025 Miel Sandonis'),
( 9, 'web_header_image', '_header_transparent_big.png'),
(10, 'web_keywords', 'miel, artesanal'),
(11, 'web_meta_author', 'mielsandonis.com'),
(12, 'web_meta_country', 'ES, Spain'),
(13, 'web_meta_geo_region', 'ES-MAD'),
(14, 'web_meta_geo_country', 'ES'),
(15, 'web_metatgn_nation', ''),
(16, 'web_locale', 'es'),
(17, 'web_currency', 'EUR'),
(18, 'company_name', 'Miel Sandonis'),
(19, 'company_vat', 'B55555555'),
(20, 'company_address_1', 'Calle de las colmensa, 1'),
(21, 'company_address_2', ''),
(22, 'company_address_3', ''),
(23, 'company_address_4', 'Valladolid'),
(24, 'company_address_5', 'Valladolid'),
(25, 'company_postcode', '47001'),
(26, 'company_country', 'ES'),
(27, 'company_region', 'Valladolid'),
(28, 'company_city', 'Valladolid'),
(29, 'company_phone', '+34 555 55 55 55'),
(30, 'company_email', 'info@mielsandonis.com'),
(31, 'company_facebook', 'https://facebook.com'),
(32, 'company_linkedin', 'https://linkedin.com'),
(33, 'company_youtube', 'https://youtube.com'),
(34, 'company_twitter', 'https://twitter.com'),
(35, 'company_pinterest', 'https://pinterest.com'),
(36, 'company_skype', 'https://www.skype.com'),
(37, 'company_instagram', 'https://instagram.com/'),
(38, 'website_skin', 'default'),
(39, 'app_skin', 'default'),
(40, 'ticketing_run', '1'),
(41, 'ticketing_skin', 'default'),
(42, 'cron_enabled', '1'),
(43, 'cron_lock', ''),
(44, 'cron_last_run', '20180620'),
(45, 'mail_method', 'PHPMailer'),
(46, 'mail_host', 'mail.mielsandonis.com'),
(47, 'mail_port', '25'),
(48, 'mail_username', 'system@mielsandonis.com'),
(49, 'mail_password', 'pG4uY9dU9iC6tT8nU1tM0qD5eH6kU9pQ'),
(50, 'email_system_address', 'system@mielsandonis.com'),
(51, 'email_system_name', 'Miel Sandonis'),
(52, 'email_header_image', 'mail_header.png'),
(53, 'invoice_last_run', '1572102046'),
(62, 'cookies_prefix', 'MIELSANDONIS'),
(54, 'verify_account', '1'),
(55, 'records_per_page', '20'),
(56, 'record_visits', '0'),
(57, 'max_size_file_upload', '8000000'),
(58, 'private_ip', 'a:2:{i:0;s:9:\"127.0.0.1\";i:1;s:13:\"83.217.171.64\";}'),
(59, 'temp_images_folder', '/web/temp/'),
(60, 'whatsapp_phone', '34555555555'),
(61, 'time_zone', 'Europe/Madrid'),
(62, 'key_secret', ''),
(63, 'customer_service_email_address', 'info@mielsandonis.com'),
(64, 'locations_api', 'https://openges.com/api'),
(65, 'locations_api_key', '000');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
