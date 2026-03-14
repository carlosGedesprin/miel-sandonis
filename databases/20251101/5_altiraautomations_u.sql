-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-09-2024 a las 13:17:19
-- Versión del servidor: 10.4.27-MariaDB
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS=0;
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

DROP TABLE IF EXISTS `account_pay_details`;
DROP TABLE IF EXISTS `account_notes`;
DROP TABLE IF EXISTS `account_funds`;
DROP TABLE IF EXISTS `account_funds_settings`;
DROP TABLE IF EXISTS `automation`;

DROP TABLE IF EXISTS `credential_type_data`;
DROP TABLE IF EXISTS `credential_data`;
DROP TABLE IF EXISTS `credential`;
DROP TABLE IF EXISTS `credential_type`;
DROP TABLE IF EXISTS `rag`;
DROP TABLE IF EXISTS `rag_document`;
DROP TABLE IF EXISTS `server`;

DROP TABLE IF EXISTS `n8n_lead_email`;
DROP TABLE IF EXISTS `n8n_lead`;
-- DROP TABLE IF EXISTS `n8n_warm_ip_email`;
-- DROP TABLE IF EXISTS `n8n_warm_ip_account`;
DROP TABLE IF EXISTS `lead_market`;
DROP TABLE IF EXISTS `lead_origin`;
DROP TABLE IF EXISTS `lead_fair`;
DROP TABLE IF EXISTS `lead`;
DROP TABLE IF EXISTS `lead_funding`;
DROP TABLE IF EXISTS `account_payment_method`;
DROP TABLE IF EXISTS `invoice_line`;
DROP TABLE IF EXISTS `quote_extra`;
DROP TABLE IF EXISTS `quote_line`;
DROP TABLE IF EXISTS `payment_transaction`;
DROP TABLE IF EXISTS `payment`;
DROP TABLE IF EXISTS `user_notes`;
DROP TABLE IF EXISTS `user_profile`;
DROP TABLE IF EXISTS `user_role`;
DROP TABLE IF EXISTS `quote`;
DROP TABLE IF EXISTS `commission`;
DROP TABLE IF EXISTS `settlement`;
DROP TABLE IF EXISTS `invoice`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `coupon`;
DROP TABLE IF EXISTS `account`;

DROP TABLE IF EXISTS `supplier`;
DROP TABLE IF EXISTS `sub_sector`;
DROP TABLE IF EXISTS `workflow`;
DROP TABLE IF EXISTS `solution`;
DROP TABLE IF EXISTS `sector`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `account`
--

CREATE TABLE `account` (
  `id` int(11) NOT NULL,
  `account_key` varchar(32) DEFAULT NULL,
  `group` varchar(255) NOT NULL COMMENT '3-Staff 4-Customer 5-Agent',
  `main_user` varchar(5) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `notifications_email` varchar(100) DEFAULT NULL,
  `locale` varchar(2) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `post_code` varchar(10) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `region` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `alt_city` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `vat` varchar(25) DEFAULT NULL,
  `vat_type` int(11) DEFAULT NULL,
  `agent` varchar(11) DEFAULT NULL,
  `show_to_staff` varchar(1) NOT NULL DEFAULT '1' COMMENT 'With 0 only admin can see it',
  `allow_staff_use_card` varchar(1) NOT NULL DEFAULT '1',
  `preferred_payment_type` int(11) DEFAULT NULL,
  `commission_percent` varchar(4) NOT NULL DEFAULT '0',
  `coupon` int(11) DEFAULT NULL,
  `stripe_id` varchar(100) DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `account`
--

INSERT INTO `account` (`id`, `account_key`, `group`, `main_user`, `name`,            `company`,           `notifications_email`, `locale`, `address`, `post_code`, `country`, `region`, `city`, `alt_city`, `phone`, `vat`, `vat_type`, `show_to_staff`, `allow_staff_use_card`, `preferred_payment_type`, `commission_percent`,  `coupon`, `stripe_id`, `active`) VALUES
( 1, 'a9ce8c1201020e2b3e77',                '1',     '1',         'superadmin',      NULL,                'superadmin@popo.org', 'es',     NULL,     NULL,         NULL,      NULL,     NULL,   NULL,       NULL,    NULL,  '3',  '0',             '1',                    3,                         2000,                 NULL,     NULL,        '1'),
( 2, '5c2o6564f0db0aec4156',                '2',     '2',         'admin',           NULL,                'admin@popo.org',      'es',     NULL,     NULL,         NULL,      NULL,     NULL,   NULL,       NULL,    NULL,  '3',  '0',             '1',                    3,                         2000,                 NULL,     NULL,        '1'),
( 3, 'fde97e0eda19a6d80c94',                '3',     '3',         'staff',           NULL,                'staff@popo.com',      'es',     NULL,     NULL,         NULL,      NULL,     NULL,   NULL,       NULL,    NULL,  '3',  '0',             '1',                    3,                         2000,                 NULL,     NULL,        '1'),
( 4, 'd0fc4acbd986c8f3dafe',                '4',     '4',         'Dylan Peacock',   'Boosted Cars',      'dylan@popo.com',      'es',     NULL,     NULL,         NULL,      NULL,     NULL,   NULL,       NULL,    NULL,  '3',  '1',             '1',                    3,                         2000,                 NULL,     NULL,        '1'),
( 5, '7c976gd4640d1883b49b',                '5',     '5',         'Maddison Welch',  'Madd\'s Agency',    'maddison@popo.com',   'es',     NULL,     NULL,         NULL,      NULL,     NULL,   NULL,       NULL,    NULL,  '3',  '1',             '1',                    3,                         2000,                 NULL,     NULL,        '1'),
( 6, 'e1096d7aac2614d6fb71',                '5',     '6',         'Abby Kent',       'Abby Consultancy',  'abby@popo.com',       'es',     NULL,     NULL,         NULL,      NULL,     NULL,   NULL,       NULL,    NULL,  '3',  '1',             '1',                    4,                         2000,                 NULL,     NULL,        '1'),
( 7, 'bb8510k31386cf26ad1f',                '4',     '7',         'John Westgate',   'Westgate Goods',    'john@popo.com',       'es',     NULL,     NULL,         NULL,      NULL,     NULL,   NULL,       NULL,    NULL,  '3',  '1',             '1',                    3,                         2000,                 NULL,     NULL,        '1'),
( 8, 'a5a209te0bb2de229c94',                '4',     '8',         'Morgan Goodwin',  'Morgan Pub',        'morgan@popo.com',     'es',     NULL,     NULL,         NULL,      NULL,     NULL,   NULL,       NULL,    NULL,  '3',  '1',             '1',                    3,                         2000,                 NULL,     NULL,        '1'),
( 9, '3df648l6a34f77fd6551',                '4',     '9',         'Hubert T. Lee',   'West Inn',          'hubert@popo.com',     'es',     NULL,     NULL,         NULL,      NULL,     NULL,   NULL,       NULL,    NULL,  '3',  '1',             '1',                    4,                         2000,                 NULL,     NULL,        '1'),
(10, '6e53f1f1f4b4023d7415',                '4',    '10',         'Ellie Stewart',   'Ell\'s Chemicals',  'ellie@popo.com',      'es',     NULL,     NULL,         NULL,      NULL,     NULL,   NULL,       NULL,    NULL,  '3',  '1',             '1',                    4,                         2000,                 NULL,     NULL,        '1'),
(11, 'd02d08a8b77c3e4ef34d',                '4',    '11',         'Jacob Walker',    'Walk Boots',        'jacob@popo.com',      'es',     NULL,     NULL,         NULL,      NULL,     NULL,   NULL,       NULL,    NULL,  '3',  '1',             '1',                    3,                         2000,                 NULL,     NULL,        '1'),
(12, 'ea243e2e3398b4f0b0be',                '4',    '12',         'Raymond Wallace', 'Ray Electric Inc.', 'ray@popo.com',        'es',     NULL,     NULL,         NULL,      NULL,     NULL,   NULL,       NULL,    NULL,  '3',  '1',             '1',                    3,                         2000,                 NULL,     NULL,        '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `account_notes`
--

CREATE TABLE `account_notes` (
  `id` int(11) NOT NULL,
  `account` int(11) DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL COMMENT '3-Staff 4-Customer 5-Agent 6-Integrator 7-PublicSector 8-Verificator',
  `notes` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `account_funds`
--

CREATE TABLE `account_funds` (
  `id` int(11) NOT NULL,
  `account` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `payment_type` int(11) DEFAULT NULL,
  `account_payment_method` int(11) DEFAULT NULL,
  `funding_key` varchar(32) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `payment_reference` varchar(50) DEFAULT NULL,
  `credit` varchar(10) DEFAULT NULL,
  `debit` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `account_funds_settings`
--

CREATE TABLE `account_funds_settings` (
  `id` int(11) NOT NULL,
  `account` int(11) DEFAULT NULL,
  `account_payment_method` int(11) DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0',
  `min` int(11) DEFAULT NULL,
  `auto_fill` varchar(1) NOT NULL DEFAULT '0',
  `fill_amount` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `account_payment_method`
--

CREATE TABLE `account_payment_method` (
  `id` int(11) NOT NULL,
  `account` int(11) DEFAULT NULL,
  `payment_type` int(11) DEFAULT NULL,
  `key` varchar(32) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `IBAN` varchar(24) DEFAULT NULL,
  `object_id` varchar(50) DEFAULT NULL COMMENT 'Stripe object `pm_` id',
  `object` varchar(20) DEFAULT NULL COMMENT 'Stripe object `payment_method`',
  `brand` varchar(20) DEFAULT NULL COMMENT 'Visa, MasterCard...',
  `country` varchar(20) DEFAULT NULL,
  `name_on_card` varchar(100) DEFAULT NULL,
  `last_4` varchar(4) DEFAULT NULL,
  `exp_month` varchar(2) DEFAULT NULL,
  `exp_year` varchar(4) DEFAULT NULL,
  `cvc_check` varchar(20) DEFAULT NULL,
  `funding` varchar(20) DEFAULT NULL,
  `preferred` varchar(1) NOT NULL DEFAULT '0',
  `active` varchar(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `account_pay_details`
--

CREATE TABLE `account_pay_details` (
  `id` int(11) NOT NULL,
  `account` int(11) DEFAULT NULL,
  `IBAN` varchar(24) DEFAULT NULL,
  `last_4` varchar(4) DEFAULT NULL,
  `exp_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `automation`
--

CREATE TABLE `automation` (
  `id` int(11) NOT NULL,
  `automation_key` varchar(32) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `account` int(11) DEFAULT NULL,
  `billing_account` int(11) DEFAULT NULL,
  `product_setup` int(11) DEFAULT NULL,
  `product_renewal` int(11) DEFAULT NULL,
  `coupon` int(11) DEFAULT NULL,
  `date_reg` datetime DEFAULT NULL,
  `price_setup` varchar(10) NOT NULL DEFAULT '0',
  `price_renewal` varchar(10) NOT NULL DEFAULT '0',
  `auto_renew` varchar(1) NOT NULL DEFAULT '1',
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `rag` int(11) DEFAULT NULL,
  `agent` varchar(11) DEFAULT NULL,
  `active` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credential`
--

CREATE TABLE `credential` (
  `id` int(11) NOT NULL,
  `credential_key` varchar(32) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `account` int(11) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0',
  `credential_type` int(11) DEFAULT NULL,
  `n8n_id` varchar(50) DEFAULT NULL,
  `n8n_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credential_data`
--

CREATE TABLE `credential_data` (
  `id` int(11) NOT NULL,
  `credential` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `field_name` varchar(100) DEFAULT NULL,
  `field_value` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credential_type`
--

CREATE TABLE `credential_type` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `n8n_name` varchar(50) DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `credential_type`
--

INSERT INTO `credential_type` (`id`, `name`, `n8n_name`, `active`) VALUES
(1, 'Generic', 'generic', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credential_type_data`
--

CREATE TABLE `credential_type_data` (
  `id` int(11) NOT NULL,
  `credential_type` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `field` varchar(100) DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `credential_type_data`
--

INSERT INTO `credential_type_data` (`id`, `credential_type`, `name`, `field`, `active`) VALUES
(1, 1, 'Init', 'api_log', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `server`
--

CREATE TABLE `server` (
  `id` int(11) NOT NULL,
  `server_key` varchar(32) DEFAULT NULL,
  `account` int(11) DEFAULT NULL,
  `billing_account` int(11) DEFAULT NULL,
  `product_setup` int(11) DEFAULT NULL,
  `product_renewal` int(11) DEFAULT NULL,
  `price_setup` varchar(10) NOT NULL DEFAULT '0',
  `price_renewal` varchar(10) NOT NULL DEFAULT '0',
  `coupon` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `date_reg` datetime DEFAULT NULL,
  `auto_renew` varchar(1) NOT NULL DEFAULT '1',
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `agent` varchar(11) DEFAULT NULL,
  `server_name` varchar(100) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `server_id` varchar(15) DEFAULT NULL,
  `root_password` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `customer_username` varchar(50) DEFAULT NULL,
  `customer_password` varchar(50) DEFAULT NULL,
  `services` varchar(50) DEFAULT NULL,
  `bulk_info` longtext DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `server`
--

INSERT INTO `server` (`id`, `server_key`, `account`, `billing_account`, `product_setup`, `product_renewal`, `price_setup`, `price_renewal`, `coupon`, `name`, `date_reg`,            `auto_renew`, `date_start`, `date_end`,   `agent`, `server_name`, `ip`, `server_id`, `root_password`, `username`, `password`, `customer_username`, `customer_password`, `services`, `bulk_info`, `active`) VALUES
(1, '2c86fea1c86d141cbe33271df95bf389',   6,         6,                 12,              13,                '5000',        '5000',          NULL,     'Popo', '2025-12-04 18:00:45', '1',          '2025-12-04', '2026-01-04', '',      '6-popo',      '',   '',          '',              '',         '',         '',                  '',                  '1,2,3',     '',       '1'),
(2, '98a36feec61f5648cefb24d957958219',   6,         6,                 12,              13,                '5000',        '5000',          NULL,     'Pipo', '2025-12-04 18:02:17', '1',          '2025-12-04', '2026-01-04', '',      '6-pipo',      '',   '',          '',              '',         '',         '',                  '',                  '1,2,3',     '',       '1'),
(3, '7ddac96beafc74aeae1f7a921c0288d3',   6,         6,                 12,              13,                '5000',        '5000',          NULL,     'Popa', '2025-12-04 18:25:04', '1',          '2025-12-04', '2026-01-04', '',      '6-popa',      '',   '',          '',              '',         '',         '',                  '',                  '2,1',       '',      '1');


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rag`
--

CREATE TABLE `rag` (
  `id` int(11) NOT NULL,
  `rag_key` varchar(32) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `account` int(11) DEFAULT NULL,
  `billing_account` int(11) DEFAULT NULL,
  `product_setup` int(11) DEFAULT NULL,
  `product_renewal` int(11) DEFAULT NULL,
  `coupon` int(11) DEFAULT NULL,
  `date_reg` datetime DEFAULT NULL,
  `price_setup` varchar(10) NOT NULL DEFAULT '0',
  `price_renewal` varchar(10) NOT NULL DEFAULT '0',
  `auto_renew` varchar(1) NOT NULL DEFAULT '1',
  `date_start` date DEFAULT NULL,
  `date_end` date DEFAULT NULL,
  `agent` varchar(11) DEFAULT NULL,
  `server` int(11) DEFAULT NULL,
  `address` varchar(225) DEFAULT NULL,
  `folder` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `active` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `rag`
--

INSERT INTO `rag` (`id`, `rag_key`, `name`, `account`, `billing_account`, `product_setup`, `product_renewal`, `coupon`, `date_reg`, `price_setup`, `price_renewal`, `auto_renew`, `date_start`, `date_end`, `agent`, `server`, `address`, `folder`, `username`, `password`, `active`) VALUES
(1, '3a7bdb08f402277abb8c217d98f1b597', 'AccedeMe', 6, 6, 10, 11, NULL, '2025-10-14 19:25:08', '5000', '5000', '1', NULL, NULL, '', 1, 'ftp.accedeme.com', 'altira', 'accedeme789456@accedeme.com', 'gZ9tF2sE6uJ2xY3uF5mP3mZ6xV7eQ2bO', '1');

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `rag_document`
--

CREATE TABLE `rag_document` (
  `id` int(11) NOT NULL,
  `rag` int(11) DEFAULT NULL,
  `rag_document_key` varchar(32) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `file_name` longtext DEFAULT NULL,
  `extension` varchar(25) DEFAULT NULL,
  `date_reg` datetime DEFAULT NULL,
  `status` varchar(1) NOT NULL DEFAULT '0' COMMENT '0-Pending 1-Ready'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sector`
--

CREATE TABLE `sector` (
  `id` int(11) NOT NULL,
  `key` varchar(32) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `solutions` varchar(100) DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `sector`
--

INSERT INTO `sector` (`id`, `key`, `name`,                                 `picture`, `description`, `slug`, `solutions`, `active`) VALUES
( 1, '01a6541987',                 'Clínicas dentales',                    NULL,      NULL,          'clínicas-dentales',                   NULL, '1'),
( 2, '02a4881987',                 'Centros de estética',                  NULL,      NULL,          'centros-de-estetica',                 NULL, '1'),
( 3, '03a4881987',                 'Gestorías y Asesorías Fiscales',       NULL,      NULL,          'gestorias-y-asesorias_fiscales',      NULL, '1'),
( 4, '04a4881987',                 'Academias y Formación Online Premium', NULL,      NULL,          'academias-y-formacion-premium',       NULL, '1'),
( 5, '05a6541987',                 'Agencias de Marketing y Consultores',  NULL,      NULL,          'agencias-de-marketing-y-consultores', NULL, '1'),
( 6, '06a6541987',                 'Veterinarios',                         NULL,      NULL,          'veterinarios',                        NULL, '1'),
( 7, '07a6541987',                 'Industria',                            NULL,      NULL,          'industria',                           NULL, '1'),
( 8, '08a6541987',                 'Logística',                            NULL,      NULL,          'logistica',                           NULL, '1'),
( 9, '09a6541987',                 'Legal',                                NULL,      NULL,          'legal',                               NULL, '1'),
(10, '10a6541987',                 'Turismo',                              NULL,      NULL,          'turismo',                             NULL, '1'),
(11, '11a6541987',                 'Agroalimentario',                      NULL,      NULL,          'agroalimentario',                     NULL, '1'),
(12, '12a6541987',                 'Marketing',                            NULL,      NULL,          'marketing',                           NULL, '1'),
(13, '13a6541987',                 'Sanidad',                              NULL,      NULL,          'sanidad',                             NULL, '1'),
(14, '14a6541987',                 'Construcción',                         NULL,      NULL,          'construccion',                        NULL, '1'),
(15, '15a6541987',                 'Ecommerce',                            NULL,      NULL,          'ecommerce',                           NULL, '1'),
(16, '16a6541987',                 'Restauración',                         NULL,      NULL,          'restauracion',                        NULL, '1'),
(17, '17a6541987',                 'Finanzas',                             NULL,      NULL,          'finanzas',                            NULL, '1'),
(18, '18a6541987',                 'Inmobiliaria',                         NULL,      NULL,          'inmobiliaria',                        NULL, '1'),
(19, '19a6541987',                 'Automoción',                           NULL,      NULL,          'automocion',                          NULL, '1'),
(20, '20a6541987',                 'Educación',                            NULL,      NULL,          'educacion',                           NULL, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sub_sector`
--

CREATE TABLE `sub_sector` (
  `id` int(11) NOT NULL,
  `sector` int(11) DEFAULT NULL,
  `key` varchar(32) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `sub_sector`
--

INSERT INTO `sub_sector` (`id`, `sector`, `key`, `name`, `picture`, `description`, `slug`, `active`) VALUES
( 1, 2, '1019541987', 'Clínica Dental', NULL, 'Description Clínica Dental', 'clinica-dental', '1'),
( 2, 2, '2059881987', 'Comercio local', NULL, 'Description Comercio local', 'comercio-local', '1'),
( 3, 1, '3059881987', 'Inmobiliaria', NULL, 'Description Inmobiliaria', 'inmobiliaria', '1'),
( 4, 1, '4059881987', 'Agencia de seguros', NULL, 'Description Agencia de seguros', 'agencia-de-seguros', '1'),
( 5, 4, '5059881987', 'Taller de reparación de coches', NULL, 'Description Taller de reparación de coches', 'reparacion-de-coches', '1'),
( 6, 2, '6059881987', 'Peluquería', NULL, 'Description Peluquería', 'peluqueria', '1'),
( 7, 2, '7059881987', 'Clínica veterinaría', NULL, 'Description Clínica veterinaría', 'clinica-veterinaría', '1'),
( 8, 2, '8059881987', 'Centro de estética', NULL, 'Description Centro de estética', 'centro-de-estetica', '1'),
( 9, 1, '9059881987', 'Hostelería Bar', NULL, 'Description Hostelería Bar', 'hosteleria-bar', '1'),
(10, 3, '1059881987', 'Fabricación piezas', NULL, 'Description Fabricación piezas', 'fabricacion-piezas', '1'),
(11, 1, '1159881987', 'Hotel', NULL, 'Description Hotel', 'hotel', '1'),
(12, 3, '1259881987', 'Mecanizados', NULL, 'Description Mecanizados', 'mecanizados', '1'),
(13, 1, '1359881987', 'Supermercado', NULL, 'Description Supermercado', 'supermercado', '1'),
(14, 5, '1419541987', 'Explotación ganadera', NULL, 'Description Explotación ganadera', 'explotacion-ganadera', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solution`
--

CREATE TABLE `solution` (
  `id` int(11) NOT NULL,
  `key` varchar(32) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `subtitle` varchar(250) DEFAULT NULL,
  `problem` longtext DEFAULT NULL,
  `solution` longtext DEFAULT NULL,
  `image` varchar(100) DEFAULT NULL,
  `workflows` varchar(100) DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `commission`
--

CREATE TABLE `commission` (
  `id` int(11) NOT NULL,
  `account` int(11) DEFAULT NULL,
  `settlement` int(11) DEFAULT NULL,
  `invoice` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `invoice_net` varchar(10) DEFAULT '0',
  `commission_percent` varchar(4) NOT NULL DEFAULT '0',
  `description` varchar(80) DEFAULT NULL,
  `total` varchar(10) NOT NULL DEFAULT '0',
  `payed` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coupon`
--

CREATE TABLE `coupon` (
  `id` int(11) NOT NULL,
  `agent` int(11) DEFAULT NULL,
  `integrator` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `discount` varchar(5) NOT NULL DEFAULT '0',
  `discount_type` varchar(10) DEFAULT NULL COMMENT '% - Percentage, amount - Amount',
  `period` varchar(1) DEFAULT NULL COMMENT 'Y - Year, M - Month',
  `num_period` int(11) DEFAULT NULL,
  `validity_date_start` date DEFAULT NULL,
  `validity_date_end` date DEFAULT NULL,
  `commission_percent` varchar(4) NOT NULL DEFAULT '0',
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `account` int(11) DEFAULT NULL,
  `payment` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `net` varchar(10) NOT NULL DEFAULT '0',
  `vat_amount` varchar(10) NOT NULL DEFAULT '0',
  `total_to_pay` varchar(10) NOT NULL DEFAULT '0',
  `payed` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoice_line`
--

CREATE TABLE `invoice_line` (
  `id` int(11) NOT NULL,
  `invoice` int(11) DEFAULT NULL,
  `product` int(11) DEFAULT NULL,
  `item` varchar(100) DEFAULT NULL,
  `units` varchar(10) NOT NULL DEFAULT '1',
  `description` varchar(255) DEFAULT NULL,
  `price` varchar(10) NOT NULL DEFAULT '0',
  `amount` varchar(10) NOT NULL DEFAULT '0',
  `discount` varchar(10) NOT NULL DEFAULT '0',
  `total` varchar(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lead`
--

CREATE TABLE `lead` (
  `id` int(11) NOT NULL,
  `date_reg` datetime DEFAULT NULL,
  `lead_key` varchar(32) DEFAULT NULL,
  `account` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `group` varchar(255) NOT NULL COMMENT '3-Staff 4-Customer 5-Agent 6-Integrator',
  `username` varchar(25) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `locale` varchar(2) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
--  `position` varchar(200) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `post_code` varchar(10) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `region` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `alt_city` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
--  `phone_mobile` varchar(15) DEFAULT NULL,
--  `domain_name` varchar(50) DEFAULT NULL,
--  `linkedin` varchar(200) DEFAULT NULL,
--  `instagram` varchar(200) DEFAULT NULL,
--  `twitter` varchar(200) DEFAULT NULL,
  `send_emails` varchar(1) NOT NULL DEFAULT '1',
--  `market` int(11) DEFAULT NULL,
--  `origin` int(11) DEFAULT NULL,
--  `conscience` varchar(100) DEFAULT NULL,
--  `bulk_info` longtext DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lead_funding`
--

CREATE TABLE `lead_funding` (
  `id` int(11) NOT NULL,
  `account` int(11) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `payment_type` int(11) DEFAULT NULL,
  `account_payment_method` int(11) DEFAULT NULL,
  `date_reg` datetime DEFAULT NULL,
  `funding_key` varchar(32) DEFAULT NULL,
  `amount` varchar(50) DEFAULT NULL,
  `amount_received` varchar(50) DEFAULT NULL,
  `payment_reference` varchar(50) DEFAULT NULL,
  `applied` varchar(1) NOT NULL DEFAULT '0',
  `next_action` varchar(250) DEFAULT NULL,
  `token` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lead_fair`
--

CREATE TABLE `lead_fair` (
  `id` int(11) NOT NULL,
  `lead_key` varchar(32) DEFAULT NULL,
  `date_reg` datetime DEFAULT NULL,
  `group` varchar(255) NOT NULL COMMENT '3-Staff 4-Customer 5-Agent 6-Integrator',
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `phone_mobile` varchar(15) DEFAULT NULL,
  `locale` varchar(2) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `position` varchar(200) DEFAULT NULL,
  `linkedin` varchar(200) DEFAULT NULL,
  `instagram` varchar(200) DEFAULT NULL,
  `twitter` varchar(200) DEFAULT NULL,
  `market` varchar(100) DEFAULT NULL,
  `origin` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `post_code` varchar(10) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `region` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `alt_city` varchar(100) DEFAULT NULL,
  `send_emails` varchar(1) NOT NULL DEFAULT '1',
  `notes` longtext DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `n8n_warm_ip_account`
--

-- CREATE TABLE `n8n_warm_ip_account` (
--   `id` int(11) NOT NULL,
--   `name` varchar(100) DEFAULT NULL,
--   `email` varchar(191) DEFAULT NULL,
--   `active` varchar(1) NOT NULL DEFAULT '0'
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `n8n_warm_ip_account`
--

-- INSERT INTO `n8n_warm_ip_account` (`id`, `name`, `email`, `active`) VALUES
-- ( 1, 'Pepito Documents', 'documents.altiraautomations@gmail.com', '1'),
-- ( 2, 'Pepito Documents 2',         'accedeme.software@gmail.com', '1'),
-- ( 3, 'Silvia Altiraautomations',   'silvia.altiraautomations@gmail.com', '0'),
-- ( 4, 'Silvia 696',                 'silviaperez696@gmail.com', '0'),
-- ( 5, 'Silvia accedeMe',            'silvia.accedeme@gmail.com', '0'),
-- ( 6, 'Carlos accedeMe',            'carlos.accedeme@gmail.com', '0'),
-- ( 7, 'Accedeme.software',          'accedeme.software@gmail.com', '0'),
-- ( 8, 'silvia accessMe',            'silvia.accessme@gmail.com', '0'),
-- ( 9, 'alerts.accedeme',            'alerts.accedeme@gmail.com', '0'),
-- (10, 'ads.accedeme',               'ads.accedeme@gmail.com', '0'),
-- (11, 'Rotuleros',                  'rotuleros@gmail.com', '0'),
-- (12, 'Altira Automations',         'altiraautomations@gmail.com', '0'),
-- (13, 'Carlos Altira',              'carlos.altira.automations@gmail.com', '0'),
-- (14, 'Carlos Altira 2',            'carlos.altiraautomations@gmail.com', '0'),
-- (15, 'Mancuniana',                 'themancuniana@gmail.com', '0'),
-- (16, 'Circulo Español Altrincham', 'circuloespanolaltrincham@gmail.com', '0'),
-- (17, 'Geseim 2',                   'geseim2@gmail.com', '0'),
-- (18, 'Caminante no hay camino verdeal',                   'caminantenohaycaminoverdeal@outlook.es', '0'),
-- (19, 'Geseim 35',                  'geseim35@gmail.com', '0'),
-- (20, 'Carlos C. accedeMe',         'carlos.cusi.accedeme@gmail.com', '0'),
-- (21, 'System accedeMe',            'system.accedeme@gmail.com', '1'),
-- (22, 'Customers accedeMe',         'customers.accedeme@gmail.com', '0'),
-- (23, 'Geseim 3',                   'geseim3@gmail.com', '0'),
-- (24, 'Carlos Carum uk',            'carlos.carum.uk@gmail.com', '0'),
-- (25, 'openges.com',                'openges.com@gmail.com', '0'),
-- (26, 'J Cusi 9053',                'jcusi9053@gmail.com', '0'),
-- (27, 'Camarguesa',                 'camarguesa2011@hotmail.com', '0'),
-- (28, 'Quality Developers',         'quality.developers.academy@gmail.com', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `n8n_warm_ip_email`
--

-- CREATE TABLE `n8n_warm_ip_email` (
--   `id` int(11) NOT NULL,
--   `account` int(11) DEFAULT NULL,
--   `date_sent` datetime DEFAULT NULL,
--   `warm_email` varchar(250) DEFAULT NULL,
--   `subject` varchar(250) DEFAULT NULL,
--   `body` longtext DEFAULT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `n8n_lead`
--

CREATE TABLE `n8n_lead` (
  `id` int(11) NOT NULL,
  `lead_key` varchar(32) DEFAULT NULL,
  `date_reg` datetime DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `company` varchar(200) DEFAULT NULL,
  `position` varchar(200) DEFAULT NULL,
  `locale` varchar(2) DEFAULT NULL,
  `linkedin` varchar(200) DEFAULT NULL,
  `instagram` varchar(200) DEFAULT NULL,
  `twitter` varchar(200) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `domain_name` varchar(50) DEFAULT NULL,
  `phone` varchar(32) DEFAULT NULL,
  `phone_mobile` varchar(15) DEFAULT NULL,
  `market` int(11) DEFAULT NULL,
  `origin` int(11) DEFAULT NULL,
  `conscience` varchar(100) DEFAULT NULL,
  `bulk_info` longtext DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `n8n_lead_email`
--

CREATE TABLE `n8n_lead_email` (
  `id` int(11) NOT NULL,
  `lead` int(11) DEFAULT NULL,
  `date_sent` datetime DEFAULT NULL,
  `subject` varchar(250) DEFAULT NULL,
  `body` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lead_market`
--
CREATE TABLE lead_market (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `lang_key` varchar(50) DEFAULT NULL,
  `active` varchar(1) DEFAULT '1' NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `lead_market`
--

INSERT INTO `lead_market` (`id`, `name`,                  `lang_key`, `active`) VALUES
(1,                              'General',               'LEAD_MARKET_GENERAL', '1'),
(2,                              'Public administration', 'LEAD_MARKET_3RD_SECTOR', '1'),
(3,                              'Turism',                'LEAD_MARKET_TURISM', '1'),
(4,                              'Law',                   'LEAD_MARKET_LAW', '1'),
(5,                              'MK Agency',             'LEAD_MARKET_MK_AGENCY', '1'),
(6,                              'Sustainability',        'LEAD_MARKET_SUSTAINABILITY', '1'),
(7,                              'Consulting',            'LEAD_MARKET_CONSULTING', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lead_origin`
--
CREATE TABLE lead_origin (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `lang_key` varchar(50) DEFAULT NULL,
  `active` varchar(1) DEFAULT '1' NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `lead_origin`
--

INSERT INTO `lead_origin` (`id`, `name`, `lang_key`, `active`) VALUES
(1,                              'Manual entry',     'LEAD_ORIGIN_MANUAL', '1'),
(2,                              'Web contact form', 'LEAD_ORIGIN_WEB_CONTACT', '1'),
(3,                              'LinkedIn contact', 'LEAD_ORIGIN_LINKEDIN', '1'),
(4,                              'Web test',         'LEAD_ORIGIN_WEB_TEST', '1'),
(5,                              'Free audit',       'LEAD_ORIGIN_FREE_AUDIT', '1'),
(6,                              'Partner register', 'LEAD_ORIGIN_PARTNER_REGISTER', '1'),
(7,                              'Fair',             'LEAD_ORIGIN_FAIR', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `account` int(11) DEFAULT NULL,
  `quote` int(11) DEFAULT NULL,
  `funding` int(11) DEFAULT NULL,
  `payment_type` int(11) DEFAULT NULL,
  `instalment` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `amount` varchar(10) NOT NULL DEFAULT '0',
  `result` int(11) DEFAULT NULL,
  `typeTrans` varchar(50) DEFAULT NULL,
  `idTrans` varchar(50) DEFAULT NULL,
  `codAproval` varchar(50) DEFAULT NULL,
  `codError` varchar(50) DEFAULT NULL,
  `desError` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payment_transaction`
--

CREATE TABLE `payment_transaction` (
  `id` int(11) NOT NULL,
  `account` int(11) DEFAULT NULL,
  `account_payment_method` int(11) DEFAULT NULL,
  `quote` int(11) DEFAULT NULL,
  `funding` int(11) DEFAULT NULL,
  `payment_type` int(11) DEFAULT NULL,
  `date_reg` datetime DEFAULT NULL,
  `origin` varchar(20) DEFAULT NULL COMMENT 'quote, funding',
  `result` varchar(100) DEFAULT NULL,
  `event_id` varchar(100) DEFAULT NULL,
  `origin_id` varchar(100) DEFAULT NULL,
  `transaction_id` varchar(100) DEFAULT NULL,
  `transaction` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quote`
--

CREATE TABLE `quote` (
  `id` int(11) NOT NULL,
  `account` int(11) DEFAULT NULL,
  `quote_type` int(11) DEFAULT NULL,
  `invoice` int(11) DEFAULT NULL,
  `payment_type` int(11) DEFAULT NULL,
  `payment_method` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `quote_key` varchar(32) DEFAULT NULL,
  `net` varchar(10) NOT NULL DEFAULT '0',
  `vat_amount` varchar(10) NOT NULL DEFAULT '0',
  `total_to_pay` varchar(10) NOT NULL DEFAULT '0',
  `payment_origin` varchar(10) DEFAULT NULL COMMENT 'online, cron',
  `payment_reference` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quote_extra`
--

CREATE TABLE `quote_extra` (
  `id` int(11) NOT NULL,
  `quote` int(11) DEFAULT NULL,
  `next_action` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quote_line`
--

CREATE TABLE `quote_line` (
  `id` int(11) NOT NULL,
  `quote` int(11) DEFAULT NULL,
  `coupon` int(11) DEFAULT NULL,
  `item` varchar(100) DEFAULT NULL,
  `units` varchar(10) NOT NULL DEFAULT '1',
  `product` varchar(40) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` varchar(10) NOT NULL DEFAULT '0',
  `amount` varchar(10) NOT NULL DEFAULT '0',
  `discount` varchar(10) NOT NULL DEFAULT '0',
  `total` varchar(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `settlement`
--

CREATE TABLE `settlement` (
  `id` int(11) NOT NULL,
  `account` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `net` varchar(10) NOT NULL DEFAULT '0',
  `vat_amount` varchar(10) NOT NULL DEFAULT '0',
  `total_to_pay` varchar(10) NOT NULL DEFAULT '0',
  `payed` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `supplier_key` varchar(32) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `supplier`
--

INSERT INTO `supplier` (`id`, `supplier_key`, `name`, `picture`, `description`, `slug`, `active`) VALUES
(1, '4444444444', 'Dylan Peacock', NULL, 'Dylan Peacock services', 'dylan-peacock-services', '1'),
(2, '5555555555', 'Maddison Welch', NULL, 'Maddison Welch services', 'maddison-welch-services', '1'),
(3, '6666666666', 'Abby Kent', NULL, 'Abby Kent services', 'abby-kent-services', '1'),
(4, '7777777777', 'John Westgate', NULL, 'John Westgate services', 'john-westgate-services', '1'),
(5, '8888888888', 'Morgan Goodwin', NULL, 'Morgan Goodwin services', 'morgan-goodwin-services', '1'),
(6, '9999999999', 'Hubert T. Lee', NULL, 'Hubert T. Lee services', 'hubert-t-lee-services', '1'),
(7, 'aaaaaaaaaa', 'Ellie Stewart', NULL, 'Ellie Stewart services', 'ellie-stewart-services', '1'),
(8, 'bbbbbbbbbb', 'Jacob Walker', NULL, 'Jacob Walker services', 'jacob-walker-services', '1'),
(9, 'cccccccccc', 'Raymond Wallace', NULL, 'Raymond Wallace services', 'raymond-wallace-services', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `account` int(11) DEFAULT NULL,
  `role` int(11) DEFAULT NULL,
  `user_key` varchar(32) DEFAULT NULL,
  `username` varchar(25) DEFAULT NULL,
  `password` varchar(64) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `attempt` varchar(15) DEFAULT NULL,
  `locale` varchar(2) DEFAULT NULL,
  `activation_key` varchar(10) DEFAULT NULL,
  `change_password_key` varchar(10) DEFAULT NULL,
  `show_to_staff` varchar(1) NOT NULL DEFAULT '1' COMMENT 'With 0 only admin can see it',
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `account`, `role`, `user_key`, `username`, `password`, `email`, `name`, `lastlogin`, `attempt`, `locale`, `activation_key`, `change_password_key`, `show_to_staff`, `active`) VALUES
( 1,  1, NULL, 'a9ca8c1201020e2b3e772a67a6a1e753', 'superadmin', '$2y$12$z1Usb7xiFIZji9R5eWiZTu4MI9rYb3HfOdBaH0U2sFXxT0F4r/fZm', 'superadmin@popo.org', 'Super Admin',       '2020-04-10 18:34:31', NULL, 'es', NULL, NULL, '0', '1'),
( 2,  2, NULL, '5c2c6564f0db0aec415698ad3fbc49ac', 'admin',      '$2y$12$z1Usb7xiFIZji9R5eWiZTu4MI9rYb3HfOdBaH0U2sFXxT0F4r/fZm', 'admin@popo.org',      'Administrator',     '2021-05-19 07:53:42', NULL, 'es', NULL, NULL, '0', '1'),
( 3,  3, NULL, 'fde94e0eda19a6d80c944a294aeb0351', 'staff',      '$2y$12$z1Usb7xiFIZji9R5eWiZTu4MI9rYb3HfOdBaH0U2sFXxT0F4r/fZm', 'staff@popo.com',      'Staff',             '2020-08-18 20:48:28', NULL, 'es', NULL, NULL, '0', '1'),
( 4,  4, NULL, 'd0fcbacbd986c8f3dafe0c459633e763', 'dylan',      '$2y$12$z1Usb7xiFIZji9R5eWiZTu4MI9rYb3HfOdBaH0U2sFXxT0F4r/fZm', 'dylan@popo.com',      'Dylan Peacock',     '2020-08-09 12:31:30', NULL, 'es', NULL, NULL, '0', '1'),
( 5,  5, NULL, '7c9764d4640d1883b49b1c3abc45880f', 'maddison',   '$2y$12$z1Usb7xiFIZji9R5eWiZTu4MI9rYb3HfOdBaH0U2sFXxT0F4r/fZm', 'maddison@popo.com',   'Maddison Welch',    '2021-01-01 00:00:01', NULL, 'es', NULL, NULL, '0', '1'),
( 6,  6, NULL, 'e1096dbaac2614d6fb711bfb8ebc5e0f', 'abby',       '$2y$12$z1Usb7xiFIZji9R5eWiZTu4MI9rYb3HfOdBaH0U2sFXxT0F4r/fZm', 'abby@popo.com',       'Abby Kent',         '2021-01-01 00:00:01', NULL, 'es', NULL, NULL, '0', '1'),
( 7,  7, NULL, 'bb8510131386cf26ad1f4b5b5785d600', 'john',       '$2y$12$z1Usb7xiFIZji9R5eWiZTu4MI9rYb3HfOdBaH0U2sFXxT0F4r/fZm', 'john@popo.com',       'John',              '2021-01-01 00:00:01', NULL, 'es', NULL, NULL, '1', '1'),
( 8,  8, NULL, 'a5a2093e0bb2de229c9465ca627e36ae', 'morgan',     '$2y$12$z1Usb7xiFIZji9R5eWiZTu4MI9rYb3HfOdBaH0U2sFXxT0F4r/fZm', 'morgan@popo.com',     'Morgan Goodwin',    '2021-01-01 00:00:01', NULL, 'es', NULL, NULL, '1', '1'),
( 9,  9, NULL, '3df64896a34f77fd65513c679e8f93f7', 'hubert',     '$2y$12$z1Usb7xiFIZji9R5eWiZTu4MI9rYb3HfOdBaH0U2sFXxT0F4r/fZm', 'hubert@popo.com',     'Hubert T. Lee',     '2021-01-01 00:00:01', NULL, 'es', NULL, NULL, '1', '1'),
(10, 10, NULL, '6e5341f1f4b4023d741505f0cabb8bae', 'ellie',      '$2y$12$z1Usb7xiFIZji9R5eWiZTu4MI9rYb3HfOdBaH0U2sFXxT0F4r/fZm', 'ellie@popo.com',      'Ellie Stewart',     '2021-01-01 00:00:01', NULL, 'en', NULL, NULL, '1', '1'),
(11, 11, NULL, 'd02d0fa8b77c3e4ef34d8676f929866b', 'jacob',      '$2y$12$z1Usb7xiFIZji9R5eWiZTu4MI9rYb3HfOdBaH0U2sFXxT0F4r/fZm', 'jacob@popo.com',      'Juan',              '2021-01-01 00:00:01', NULL, 'en', NULL, NULL, '1', '1'),
(12, 12, NULL, 'd02d05a8bn7c3e4ef34d8676f929866b', 'jacobson',   '$2y$12$z1Usb7xiFIZji9R5eWiZTu4MI9rYb3HfOdBaH0U2sFXxT0F4r/fZm', 'jacobson@popo.com',   'Jacob Walker',      '2021-01-01 00:00:01', NULL, 'en', NULL, NULL, '1', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_notes`
--

CREATE TABLE `user_notes` (
  `id` int(11) NOT NULL,
  `user` int(11) DEFAULT NULL,
  `group` varchar(255) NOT NULL COMMENT '3-Staff 4-Customer 5-Agent 6-Supervisor 7-Verificator L1 8-Verificator L2',
  `notes` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_profile`
--

CREATE TABLE `user_profile` (
  `id` int(11) NOT NULL,
  `user` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `post_code` varchar(10) DEFAULT NULL,
  `country` varchar(2) DEFAULT NULL,
  `region` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `alt_city` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `photo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `user_profile`
--

INSERT INTO `user_profile` (`id`, `user`, `name`, `address`, `post_code`, `country`, `region`, `city`, `alt_city`, `phone`, `photo`) VALUES
( 1,  1, 'Superadmin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
( 2,  2, 'Administrator', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
( 3,  3, 'Staff', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
( 4,  4, 'Dylan Peacock', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
( 5,  5, 'Maddison Welch', '', '', '', '', '', '', '', NULL),
( 6,  6, 'Abby Kent', '', '', '', '', '', '', '', NULL),
( 7,  7, 'John', '', '', '', '', '', '', '', ''),
( 8,  8, 'Morgan Goodwin', '', '', '', '', '', '', '', NULL),
( 9,  9, 'Hubert T. Lee', '19 Shannon Way', 'CV10 0BT', '', '', '', '', '', NULL),
(10, 10, 'Ellie Stewart', '', '', '', '', '', '', '', ''),
(11, 11, 'Jacob Walker', '', '', '', '', '', '', '', ''),
(12, 12, 'Jacobson Walker', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `name_lang_key` varchar(30) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `user_role`
--

INSERT INTO `user_role` (`id`, `name_lang_key`, `role`) VALUES
(1, 'USER_ROLE_MANAGER', 'Manager'),
(2, 'USER_ROLE_STAFF', 'Staff'),
(3, 'USER_ROLE_CUSTOMER', 'Customer'),
(4, 'USER_ROLE_AGENT', 'Agent');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `workflow`
--

CREATE TABLE `workflow` (
  `id` int(11) NOT NULL,
  `key` varchar(32) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `json` longtext DEFAULT NULL,
  `credentials` varchar(100) DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `workflow`
--

INSERT INTO `workflow` (`id`, `key`, `name`, `json`, `credentials`, `active`) VALUES
(1, '01a39541987', 'Instagram grow', NULL, NULL, '1'),
(2, '02a39541987', 'Invoices', NULL, NULL, '1'),
(3, '03a39541987', 'Linkedin and instagram scrapping', NULL, NULL, '1'),
(4, '04a39541987', 'Personal assistant', NULL, NULL, '1'),
(5, '05a39541987', 'Phone agent bookings', NULL, NULL, '1'),
(6, '06a39541987', 'Phone agent no show', NULL, NULL, '1'),
(7, '07a39541987', 'Web scrapping', NULL, NULL, '1');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_7D3656A4CA6BF2F7` (`account_key`),
  ADD KEY `IDX_7D3656A48A1DFE65` (`preferred_payment_type`),
  ADD KEY `IDX_7D3656A4A5B69FD7` (`vat_type`),
  ADD KEY `IDX_7D3656A464BF3F02` (`coupon`);

--
-- Indices de la tabla `account_notes`
--
ALTER TABLE `account_notes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_37947FFD7D3656A4` (`account`);

--
-- Indices de la tabla `account_funds`
--
ALTER TABLE `account_funds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_30EA94207D3656A4` (`account`),
  ADD KEY `IDX_30EA94208D93D649` (`user`),
  ADD KEY `IDX_30EA9420AD5DC05D` (`payment_type`),
  ADD KEY `IDX_30EA9420299665FC` (`account_payment_method`);

--
-- Indices de la tabla `account_funds_settings`
--
ALTER TABLE `account_funds_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_411423F97D3656A4` (`account`),
  ADD KEY `IDX_411423F9299665FC` (`account_payment_method`);

--
-- Indices de la tabla `account_payment_method`
--
ALTER TABLE `account_payment_method`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_299665FC8A90ABA9` (`key`),
  ADD KEY `IDX_299665FC7D3656A4` (`account`),
  ADD KEY `IDX_299665FCAD5DC05D` (`payment_type`);

--
-- Indices de la tabla `account_pay_details`
--
ALTER TABLE `account_pay_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_458B30427D3656A4` (`account`);

--
-- Indices de la tabla `automation`
--
ALTER TABLE `automation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C9739CEE7D3656A4` (`account`),
  ADD KEY `IDX_C9739CEE1618DD73` (`billing_account`),
  ADD KEY `IDX_C9739CEE84412D6C` (`product_setup`),
  ADD KEY `IDX_C9739CEE2CDD5C78` (`product_renewal`),
  ADD KEY `IDX_C9739CEE64BF3F02` (`coupon`),
  ADD KEY `IDX_C9739CEE704CB31` (`rag`);

--
-- Indices de la tabla `credential`
--
ALTER TABLE `credential`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_57F1D4B47DDCCFA` (`credential_key`),
  ADD KEY `IDX_57F1D4B7D3656A4` (`account`),
  ADD KEY `IDX_57F1D4B7E711A00` (`credential_type`);

--
-- Indices de la tabla `credential_data`
--
ALTER TABLE `credential_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5F5CBE4A57F1D4B` (`credential`);

--
-- Indices de la tabla `credential_type`
--
ALTER TABLE `credential_type`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `credential_type_data`
--
ALTER TABLE `credential_type_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2BFD629C7E711A00` (`credential_type`);

--
-- Indices de la tabla `server`
--
ALTER TABLE `server`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_5A6DD5F6B459A1F3` (`server_key`),
  ADD KEY `IDX_5A6DD5F67D3656A4` (`account`),
  ADD KEY `IDX_5A6DD5F61618DD73` (`billing_account`),
  ADD KEY `IDX_5A6DD5F684412D6C` (`product_setup`),
  ADD KEY `IDX_5A6DD5F62CDD5C78` (`product_renewal`),
  ADD KEY `IDX_5A6DD5F664BF3F02` (`coupon`);

--
-- Indices de la tabla `rag`
--
ALTER TABLE `rag`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_704CB317D3656A4` (`account`),
  ADD KEY `IDX_704CB311618DD73` (`billing_account`),
  ADD KEY `IDX_704CB3184412D6C` (`product_setup`),
  ADD KEY `IDX_704CB312CDD5C78` (`product_renewal`),
  ADD KEY `IDX_704CB315A6DD5F6` (`server`),
  ADD KEY `IDX_704CB3164BF3F02` (`coupon`);
  
--
-- Indices de la tabla `rag_document`
--
ALTER TABLE `rag_document`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_165544D704CB31` (`rag`);
  
--
-- Indices de la tabla `sector`
--
ALTER TABLE `sector`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_4BA3D9E88A90ABA9` (`key`);

--
-- Indices de la tabla `sub_sector`
--
ALTER TABLE `sub_sector`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_F9F296A48A90ABA9` (`key`),
  ADD KEY `IDX_F9F296A44BA3D9E8` (`sector`);

--
-- Indices de la tabla `solution`
--
ALTER TABLE `solution`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_9F3329DB8A90ABA9` (`key`);

--
-- Indices de la tabla `commission`
--
ALTER TABLE `commission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1C6501587D3656A4` (`account`),
  ADD KEY `IDX_1C650158DD9F1B51` (`settlement`),
  ADD KEY `IDX_1C65015890651744` (`invoice`);

--
-- Indices de la tabla `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_64BF3F0277153098` (`code`),
  ADD KEY `IDX_64BF3F02268B9C9D` (`agent`),
  ADD KEY `IDX_64BF3F02B1A25A0D` (`integrator`);

--
-- Indices de la tabla `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_906517446D28840D` (`payment`),
  ADD KEY `IDX_906517447D3656A4` (`account`);

--
-- Indices de la tabla `invoice_line`
--
ALTER TABLE `invoice_line`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D3D1D69390651744` (`invoice`),
  ADD KEY `IDX_D3D1D693D34A04AD` (`product`);

--
-- Indices de la tabla `lead`
--
ALTER TABLE `lead`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_289161CBE7927C74` (`email`);

--
-- Indices de la tabla `lead_funding`
--
ALTER TABLE `lead_funding`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8AC38DB87D3656A4` (`account`),
  ADD KEY `IDX_8AC38DB88D93D649` (`user`),
  ADD KEY `IDX_8AC38DB8AD5DC05D` (`payment_type`),
  ADD KEY `IDX_8AC38DB8299665FC` (`account_payment_method`);

--
-- Indices de la tabla `lead_fair`
--
ALTER TABLE `lead_fair`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_9DDE53F0E7927C74` (`email`);

--
-- Indices de la tabla `n8n_warm_ip_account`
--
-- ALTER TABLE `n8n_warm_ip_account`
--   ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `n8n_warm_ip_email`
--
-- ALTER TABLE `n8n_warm_ip_email`
--   ADD PRIMARY KEY (`id`),
--   ADD KEY `IDX_39F4C6A7D3656A4` (`account`);

--
-- Indices de la tabla `n8n_lead`
--
ALTER TABLE `n8n_lead`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BAA70E326BAC85CB` (`market`),
  ADD KEY `IDX_BAA70E32DEF1561E` (`origin`);

--
-- Indices de la tabla `n8n_lead_email`
--
ALTER TABLE `n8n_lead_email`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_835DD255289161CB` (`lead`);

--
-- Indices de la tabla `lead_market`
--
ALTER TABLE `lead_market`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lead_origin`
--
ALTER TABLE `lead_origin`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6D28840D7D3656A4` (`account`),
  ADD KEY `IDX_6D28840D6B71CBF4` (`quote`),
  ADD KEY `IDX_6D28840DD30DD1D6` (`funding`),
  ADD KEY `IDX_6D28840DAD5DC05D` (`payment_type`);

--
-- Indices de la tabla `payment_transaction`
--
ALTER TABLE `payment_transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_84BBD50B7D3656A4` (`account`),
  ADD KEY `IDX_84BBD50B299665FC` (`account_payment_method`),
  ADD KEY `IDX_84BBD50B6B71CBF4` (`quote`),
  ADD KEY `IDX_84BBD50BD30DD1D6` (`funding`),
  ADD KEY `IDX_84BBD50BAD5DC05D` (`payment_type`);

--
-- Indices de la tabla `quote`
--
ALTER TABLE `quote`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6B71CBF47D3656A4` (`account`),
  ADD KEY `IDX_6B71CBF41E3908A3` (`quote_type`),
  ADD KEY `IDX_6B71CBF490651744` (`invoice`),
  ADD KEY `IDX_6B71CBF4AD5DC05D` (`payment_type`),
  ADD KEY `IDX_6B71CBF47B61A1F6` (`payment_method`);

--
-- Indices de la tabla `quote_extra`
--
ALTER TABLE `quote_extra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_40C080046B71CBF4` (`quote`);

--
-- Indices de la tabla `quote_line`
--
ALTER TABLE `quote_line`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_43F3EB7C6B71CBF4` (`quote`),
  ADD KEY `IDX_43F3EB7C64BF3F02` (`coupon`);

--
-- Indices de la tabla `settlement`
--
ALTER TABLE `settlement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DD9F1B517D3656A4` (`account`);

--
-- Indices de la tabla `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_9B2A6C7E560D15C` (`supplier_key`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D6496186CA22` (`user_key`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD KEY `IDX_8D93D64957698A6A` (`role`);

--
-- Indices de la tabla `user_notes`
--
ALTER TABLE `user_notes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_E3D95DD48D93D649` (`user`);

--
-- Indices de la tabla `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_D95AB4058D93D649` (`user`);

--
-- Indices de la tabla `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `workflow`
--
ALTER TABLE `workflow`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_65C598168A90ABA9` (`key`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `account`
--
ALTER TABLE `account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `account_notes`
--
ALTER TABLE `account_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `account_funds`
--
ALTER TABLE `account_funds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `account_funds_settings`
--
ALTER TABLE `account_funds_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `account_payment_method`
--
ALTER TABLE `account_payment_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `account_pay_details`
--
ALTER TABLE `account_pay_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `automation`
--
ALTER TABLE `automation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `server`
--
ALTER TABLE `server`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT de la tabla `rag`
--
ALTER TABLE `rag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `rag_document`
--
ALTER TABLE `rag_document`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sector`
--
ALTER TABLE `sector`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sub_sector`
--
ALTER TABLE `sub_sector`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solution`
--
ALTER TABLE `solution`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `commission`
--
ALTER TABLE `commission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `invoice_line`
--
ALTER TABLE `invoice_line`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lead`
--
ALTER TABLE `lead`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT de la tabla `lead_funding`
--
ALTER TABLE `lead_funding`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT de la tabla `lead_fair`
--
ALTER TABLE `lead_fair`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `n8n_warm_ip_account`
--
-- ALTER TABLE `n8n_warm_ip_account`
--   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `n8n_warm_ip_email`
--
-- ALTER TABLE `n8n_warm_ip_email`
--   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `n8n_lead`
--
ALTER TABLE `n8n_lead`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `n8n_lead_email`
--
ALTER TABLE `n8n_lead_email`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lead_market`
--
ALTER TABLE `lead_market`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lead_origin`
--
ALTER TABLE `lead_origin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `payment_transaction`
--
ALTER TABLE `payment_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quote`
--
ALTER TABLE `quote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quote_extra`
--
ALTER TABLE `quote_extra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quote_line`
--
ALTER TABLE `quote_line`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `settlement`
--
ALTER TABLE `settlement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user_notes`
--
ALTER TABLE `user_notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT de la tabla `workflow`
--
ALTER TABLE `workflow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `credential`
--
ALTER TABLE `credential`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `credential_data`
--
ALTER TABLE `credential_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT de la tabla `credential_type`
--
ALTER TABLE `credential_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `credential_type_data`
--
ALTER TABLE `credential_type_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `FK_7D3656A464BF3F02` FOREIGN KEY (`coupon`) REFERENCES `coupon` (`id`),
  ADD CONSTRAINT `FK_7D3656A4A5B69FD7` FOREIGN KEY (`vat_type`) REFERENCES `vat_type` (`id`),
  ADD CONSTRAINT `FK_7D3656A48A1DFE65` FOREIGN KEY (`preferred_payment_type`) REFERENCES `payment_type` (`id`);

--
-- Filtros para la tabla `account_notes`
--
ALTER TABLE `account_notes`
  ADD CONSTRAINT `FK_37947FFD7D3656A4` FOREIGN KEY (`account`) REFERENCES `account` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `account_funds`
--
ALTER TABLE `account_funds`
  ADD CONSTRAINT `FK_30EA9420299665FC` FOREIGN KEY (`account_payment_method`) REFERENCES `account_payment_method` (`id`),
  ADD CONSTRAINT `FK_30EA94207D3656A4` FOREIGN KEY (`account`) REFERENCES `account` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_30EA94208D93D649` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_30EA9420AD5DC05D` FOREIGN KEY (`payment_type`) REFERENCES `payment_type` (`id`);

--
-- Filtros para la tabla `account_funds_settings`
--
ALTER TABLE `account_funds_settings`
  ADD CONSTRAINT `FK_411423F9299665FC` FOREIGN KEY (`account_payment_method`) REFERENCES `account_payment_method` (`id`),
  ADD CONSTRAINT `FK_411423F97D3656A4` FOREIGN KEY (`account`) REFERENCES `account` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `account_payment_method`
--
ALTER TABLE `account_payment_method`
  ADD CONSTRAINT `FK_299665FC7D3656A4` FOREIGN KEY (`account`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `FK_299665FCAD5DC05D` FOREIGN KEY (`payment_type`) REFERENCES `payment_type` (`id`);

--
-- Filtros para la tabla `account_pay_details`
--
ALTER TABLE `account_pay_details`
  ADD CONSTRAINT `FK_458B30427D3656A4` FOREIGN KEY (`account`) REFERENCES `account` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `automation`
--
ALTER TABLE `automation`
  ADD CONSTRAINT `FK_C9739CEE7D3656A4` FOREIGN KEY (`account`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `FK_C9739CEE1618DD73` FOREIGN KEY (`billing_account`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `FK_C9739CEE2CDD5C78` FOREIGN KEY (`product_renewal`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_C9739CEED34A04AJ` FOREIGN KEY (`product_setup`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_C9739CEE64BF3F02` FOREIGN KEY (`coupon`) REFERENCES `coupon` (`id`),
  ADD CONSTRAINT `FK_C9739CEE704CB31` FOREIGN KEY (`rag`) REFERENCES `rag` (`id`);

--
-- Filtros para la tabla `credential`
--
ALTER TABLE `credential`
  ADD CONSTRAINT `FK_57F1D4B7D3656A4` FOREIGN KEY (`account`) REFERENCES `account` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_57F1D4B7E711A00` FOREIGN KEY (`credential_type`) REFERENCES `credential_type` (`id`);

--
-- Filtros para la tabla `credential_data`
--
ALTER TABLE `credential_data`
  ADD CONSTRAINT `FK_5F5CBE4A57F1D4B` FOREIGN KEY (`credential`) REFERENCES `credential` (`id`);

--
-- Filtros para la tabla `credential_type_data`
--
ALTER TABLE `credential_type_data`
  ADD CONSTRAINT `FK_2BFD629C7E711A00` FOREIGN KEY (`credential_type`) REFERENCES `credential_type` (`id`);
  
--
-- Filtros para la tabla `server`
--
ALTER TABLE `server`
  ADD CONSTRAINT `FK_5A6DD5F61618DD73` FOREIGN KEY (`billing_account`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `FK_5A6DD5F62CDD5C78` FOREIGN KEY (`product_renewal`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_5A6DD5F664BF3F02` FOREIGN KEY (`coupon`) REFERENCES `coupon` (`id`),
  ADD CONSTRAINT `FK_5A6DD5F67D3656A4` FOREIGN KEY (`account`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `FK_5A6DD5F684412D6C` FOREIGN KEY (`product_setup`) REFERENCES `product` (`id`);

--
-- Filtros para la tabla `rag`
--
ALTER TABLE `rag`
  ADD CONSTRAINT `FK_704CB317D3656A4` FOREIGN KEY (`account`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `FK_704CB311618DD73` FOREIGN KEY (`billing_account`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `FK_704CB3184412D6C` FOREIGN KEY (`product_setup`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_704CB312CDD5C78` FOREIGN KEY (`product_renewal`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_704CB3164BF3F02` FOREIGN KEY (`coupon`) REFERENCES `coupon` (`id`),
  ADD CONSTRAINT `FK_704CB315A6DD5F6` FOREIGN KEY (`server`) REFERENCES `server` (`id`);

--
-- Filtros para la tabla `rag_document`
--
ALTER TABLE `rag_document`
  ADD CONSTRAINT `FK_13629772704CB31` FOREIGN KEY (`rag`) REFERENCES `rag` (`id`);
  
--
-- Filtros para la tabla `commission`
--
ALTER TABLE `commission`
  ADD CONSTRAINT `FK_1C6501587D3656A4` FOREIGN KEY (`account`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `FK_1C65015890651744` FOREIGN KEY (`invoice`) REFERENCES `invoice` (`id`),
  ADD CONSTRAINT `FK_1C650158DD9F1B51` FOREIGN KEY (`settlement`) REFERENCES `settlement` (`id`);

--
-- Filtros para la tabla `coupon`
--
ALTER TABLE `coupon`
  ADD CONSTRAINT `FK_64BF3F02268B9C9D` FOREIGN KEY (`agent`) REFERENCES `account` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_64BF3F02B1A25A0D` FOREIGN KEY (`integrator`) REFERENCES `account` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `FK_906517446D28840D` FOREIGN KEY (`payment`) REFERENCES `payment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_906517447D3656A4` FOREIGN KEY (`account`) REFERENCES `account` (`id`);

--
-- Filtros para la tabla `invoice_line`
--
ALTER TABLE `invoice_line`
  ADD CONSTRAINT `FK_D3D1D69390651744` FOREIGN KEY (`invoice`) REFERENCES `invoice` (`id`),
  ADD CONSTRAINT `FK_D3D1D693D34A04AD` FOREIGN KEY (`product`) REFERENCES `product` (`id`);

--
-- Filtros para la tabla `lead_funding`
--
ALTER TABLE `lead_funding`
  ADD CONSTRAINT `FK_8AC38DB8299665FC` FOREIGN KEY (`account_payment_method`) REFERENCES `account_payment_method` (`id`),
  ADD CONSTRAINT `FK_8AC38DB87D3656A4` FOREIGN KEY (`account`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `FK_8AC38DB88D93D649` FOREIGN KEY (`user`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_8AC38DB8AD5DC05D` FOREIGN KEY (`payment_type`) REFERENCES `payment_type` (`id`);

--
-- Filtros para la tabla `n8n_warm_ip_email`
--
-- ALTER TABLE `n8n_warm_ip_email`
--   ADD CONSTRAINT `FK_BAC2029B289161CB` FOREIGN KEY (`account`) REFERENCES `n8n_warm_ip_account` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `n8n_lead`
--
ALTER TABLE `n8n_lead`
  ADD CONSTRAINT `FK_BAA70E326BAC85CB` FOREIGN KEY (`market`) REFERENCES `lead_market` (`id`),
  ADD CONSTRAINT `FK_BAA70E32DEF1561E` FOREIGN KEY (`origin`) REFERENCES `lead_origin` (`id`);

--
-- Filtros para la tabla `n8n_lead_email`
--
ALTER TABLE `n8n_lead_email`
  ADD CONSTRAINT `FK_835DD255289161CB` FOREIGN KEY (`lead`) REFERENCES `n8n_lead` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `FK_6D28840D6B71CBF4` FOREIGN KEY (`quote`) REFERENCES `quote` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_6D28840D7D3656A4` FOREIGN KEY (`account`) REFERENCES `account` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_6D28840DAD5DC05D` FOREIGN KEY (`payment_type`) REFERENCES `payment_type` (`id`),
  ADD CONSTRAINT `FK_6D28840DD30DD1D6` FOREIGN KEY (`funding`) REFERENCES `lead_funding` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `payment_transaction`
--
ALTER TABLE `payment_transaction`
  ADD CONSTRAINT `FK_84BBD50B299665FC` FOREIGN KEY (`account_payment_method`) REFERENCES `account_payment_method` (`id`),
  ADD CONSTRAINT `FK_84BBD50B6B71CBF4` FOREIGN KEY (`quote`) REFERENCES `quote` (`id`),
  ADD CONSTRAINT `FK_84BBD50B7D3656A4` FOREIGN KEY (`account`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `FK_84BBD50BAD5DC05D` FOREIGN KEY (`payment_type`) REFERENCES `payment_type` (`id`),
  ADD CONSTRAINT `FK_84BBD50BD30DD1D6` FOREIGN KEY (`funding`) REFERENCES `lead_funding` (`id`);

--
-- Filtros para la tabla `quote`
--
ALTER TABLE `quote`
  ADD CONSTRAINT `FK_6B71CBF41E3908A3` FOREIGN KEY (`quote_type`) REFERENCES `quote_type` (`id`),
  ADD CONSTRAINT `FK_6B71CBF47B61A1F6` FOREIGN KEY (`payment_method`) REFERENCES `account_payment_method` (`id`),
  ADD CONSTRAINT `FK_6B71CBF47D3656A4` FOREIGN KEY (`account`) REFERENCES `account` (`id`),
  ADD CONSTRAINT `FK_6B71CBF490651744` FOREIGN KEY (`invoice`) REFERENCES `invoice` (`id`),
  ADD CONSTRAINT `FK_6B71CBF4AD5DC05D` FOREIGN KEY (`payment_type`) REFERENCES `payment_type` (`id`);

--
-- Filtros para la tabla `quote_extra`
--
ALTER TABLE `quote_extra`
  ADD CONSTRAINT `FK_40C080046B71CBF4` FOREIGN KEY (`quote`) REFERENCES `quote` (`id`);

--
-- Filtros para la tabla `quote_line`
--
ALTER TABLE `quote_line`
  ADD CONSTRAINT `FK_43F3EB7C64BF3F02` FOREIGN KEY (`coupon`) REFERENCES `coupon` (`id`),
  ADD CONSTRAINT `FK_43F3EB7C6B71CBF4` FOREIGN KEY (`quote`) REFERENCES `quote` (`id`);

--
-- Filtros para la tabla `settlement`
--
ALTER TABLE `settlement`
  ADD CONSTRAINT `FK_DD9F1B517D3656A4` FOREIGN KEY (`account`) REFERENCES `account` (`id`);

--
-- Filtros para la tabla `sub_sector`
--
ALTER TABLE `sub_sector`
  ADD CONSTRAINT `FK_F9F296A44BA3D9E8` FOREIGN KEY (`sector`) REFERENCES `sector` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D64957698A6A` FOREIGN KEY (`role`) REFERENCES `user_role` (`id`);

--
-- Filtros para la tabla `user_notes`
--
ALTER TABLE `user_notes`
  ADD CONSTRAINT `FK_E3D95DD48D93D649` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `FK_D95AB4058D93D649` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE;

COMMIT;

SET FOREIGN_KEY_CHECKS=1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
