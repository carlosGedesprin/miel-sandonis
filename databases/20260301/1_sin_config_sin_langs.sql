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
-- Base de datos: `mielsandonis`
-- use mielsandonis;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bank_account`
--

CREATE TABLE `bank_account` (
  `id` int(11) NOT NULL,
  `iban` varchar(10) DEFAULT NULL,
  `number` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `default` varchar(1) NOT NULL DEFAULT '0',
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bot`
--

CREATE TABLE `bot` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `user_agent` longtext DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0',
  `ip` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `bot`
--

INSERT INTO `bot` (`id`, `name`, `user_agent`, `active`, `ip`) VALUES
(1, 'AdsBot [Google]', 'AdsBot-Google', '1', ''),
(2, 'Alexa [Bot]', 'ia_archiver', '1', ''),
(3, 'Alta Vista [Bot]', 'Scooter/', '1', ''),
(4, 'Ask Jeeves [Bot]', 'Ask Jeeves', '1', ''),
(5, 'Baidu [Spider]', 'Baiduspider+(', '1', ''),
(6, 'Bing [Bot]', 'bingbot/', '1', ''),
(7, 'Exabot [Bot]', 'Exabot/', '1', ''),
(8, 'FAST Enterprise [Crawler]', 'FAST Enterprise Crawler', '1', ''),
(9, 'FAST WebCrawler [Crawler]', 'FAST-WebCrawler/', '1', ''),
(10, 'Francis [Bot]', 'http://www.neomo.de/', '1', ''),
(11, 'Gigabot [Bot]', 'Gigabot/', '1', ''),
(12, 'Google Adsense [Bot]', 'Mediapartners-Google', '1', ''),
(13, 'Google Desktop', 'Google Desktop', '1', ''),
(14, 'Google Feedfetcher [Bot]', 'Feedfetcher-Google', '1', ''),
(15, 'Google [Bot]', 'Googlebot', '1', ''),
(16, 'Heise IT-Markt [Crawler]', 'heise-IT-Markt-Crawler', '1', ''),
(17, 'Heritrix [Crawler]', 'heritrix/1.', '1', ''),
(18, 'IBM Research [Bot]', 'ibm.com/cs/crawler', '1', ''),
(19, 'ICCrawler - ICjobs', 'ICCrawler - ICjobs', '1', ''),
(20, 'ichiro [Crawler]', 'ichiro/', '1', ''),
(21, 'Majestic-12 [Bot]', 'MJ12bot/', '1', ''),
(22, 'Metager [Bot]', 'MetagerBot/', '1', ''),
(23, 'MSN NewsBlogs', 'msnbot-NewsBlogs/', '1', ''),
(24, 'MSN [Bot]', 'msnbot/', '1', ''),
(25, 'MSNbot Media [Bot]', 'msnbot-media/', '1', ''),
(26, 'NG-Search [Bot]', 'NG-Search/', '1', ''),
(27, 'Nutch [Bot]', 'http://lucene.apache.org/nutch/', '1', ''),
(28, 'Nutch/CVS [Bot]', 'NutchCVS/', '1', ''),
(29, 'OmniExplorer [Bot]', 'OmniExplorer_Bot/', '1', ''),
(30, 'Online link [Validator]', 'online link validator', '1', ''),
(31, 'psbot [Picsearch]', 'psbot/0', '1', ''),
(32, 'Seekport [Bot]', 'Seekbot/', '1', ''),
(33, 'Sensis [Crawler]', 'Sensis Web Crawler', '1', ''),
(34, 'SEO Crawler', 'SEO search Crawler/', '1', ''),
(35, 'Seoma [Crawler]', 'Seoma [SEO Crawler]', '1', ''),
(36, 'SEOSearch [Crawler]', 'SEOsearch/', '1', ''),
(37, 'Snappy [Bot]', 'Snappy/1.1 ( http://www.urltrends.com/ )', '1', ''),
(38, 'Steeler [Crawler]', 'http://www.tkl.iis.u-tokyo.ac.jp/~crawler/', '1', ''),
(39, 'Synoo [Bot]', 'SynooBot/', '1', ''),
(40, 'Telekom [Bot]', 'crawleradmin.t-info@telekom.de', '1', ''),
(41, 'TurnitinBot [Bot]', 'TurnitinBot/', '1', ''),
(42, 'Voyager [Bot]', 'voyager/1.0', '1', ''),
(43, 'W3 [Sitesearch]', 'W3 SiteSearch Crawler', '1', ''),
(44, 'W3C [Linkcheck]', 'W3C-checklink/', '1', ''),
(45, 'W3C [Validator]', 'W3C_*Validator', '1', ''),
(46, 'WiseNut [Bot]', 'http://www.WISEnutbot.com', '1', ''),
(47, 'YaCy [Bot]', 'yacybot', '1', ''),
(48, 'Yahoo MMCrawler [Bot]', 'Yahoo-MMCrawler/', '1', ''),
(49, 'Yahoo Slurp [Bot]', 'Yahoo! DE Slurp', '1', ''),
(50, 'Yahoo [Bot]', 'Yahoo! Slurp', '1', ''),
(51, 'YahooSeeker [Bot]', 'YahooSeeker/', '1', ''),
(52, 'Java [Bad bot]', 'Java/', '1', ''),
(53, 'Thumbshots [Capture]', 'thumbshots-de-Bot', '1', ''),
(54, 'Susie [Sync]', '!Susie', '1', ''),
(55, 'Google Ads', 'AdsBot-Google', '1', ''),
(56, 'Google Python URL fetcher [Bot]', 'Python-urllib/', '1', ''),
(57, 'Google Search Appliance [Bot]', 'gsa', '1', ''),
(58, 'Yahoo! SpiderMan [spider]', 'SpiderMan', '1', ''),
(59, 'Yahoo! Mindset', 'Yahoo! Mindset', '1', ''),
(60, 'Yahoo! Blogs', 'Yahoo-Blogs', '1', ''),
(61, 'Yahoo! Feed Seeker', 'YahooFeedSeeker', '1', ''),
(62, 'Yahoo! Multimedia', 'Yahoo-MM', '1', ''),
(63, 'Yahoo! Test', 'Yahoo-Test', '1', ''),
(64, 'Yahoo! VerticalCrawler', 'Yahoo-VerticalCrawler', '1', ''),
(65, 'Fast PartnerSite', 'Fast PartnerSite Crawler', '1', ''),
(66, 'Fast Crawler Gold', 'Fast Crawler Gold Edition', '1', ''),
(67, 'FAST FirstPage retriever', 'FAST FirstPage retriever', '1', ''),
(68, 'FAST MetaWeb', 'FAST MetaWeb Crawler', '1', ''),
(69, 'Yahoo! Search Marketing', 'crawlx', '1', ''),
(70, 'Walhello', 'appie', '1', ''),
(71, 'GeoBot', 'GeoBot/version', '1', ''),
(72, 'Suchpad [Bot]', 'http://www.suchpad.de/bot/', '1', ''),
(73, 'Insuranco', 'InsurancoBot', '1', ''),
(74, 'Xaldon [bot]', 'Xaldon WebSpider', '1', ''),
(75, 'Cosmix', 'cfetch/', '1', ''),
(76, 'Esperanza', 'EsperanzaBot', '1', ''),
(77, 'EliteSys', 'EliteSys SuperBot/', '1', ''),
(78, 'MP3-Bot', 'MP3-Bot', '1', ''),
(79, 'genie', 'genieBot (', '1', ''),
(80, 'g2', 'g2Crawler', '1', ''),
(81, 'GBSpider', 'GBSpider v', '1', ''),
(82, 'Picsearch', 'psbot/', '1', ''),
(83, 'PlantyNet', 'PlantyNet_WebRobot_V', '1', ''),
(84, 'Twiceler', 'Twiceler www.cuill.com/robots.html', '1', ''),
(85, 'IPG', 'internet-provider-guenstig.de-Bot', '1', ''),
(86, 'WissenOnline', 'WissenOnline-Bot', '1', ''),
(87, '24spider', '24spider-Robot', '1', ''),
(88, 'Zerx', 'zerxbot/', '1', ''),
(89, 'LinkWalker [bot]', 'LinkWalker', '1', ''),
(90, 'Exabot [Bot]', 'Exabot-', '1', ''),
(91, 'Jyxobot', 'Jyxobot/', '1', ''),
(92, 'Tbot [Bot]', 'Tbot/', '1', ''),
(93, 'Findexa Crawler', 'Findexa Crawler (', '1', ''),
(94, 'ISC Systems iRc Search', 'ISC Systems iRc Search', '1', ''),
(95, 'IRLbot', 'http://irl.cs.tamu.edu/crawler', '1', ''),
(96, 'Mirago', 'HeinrichderMiragoRobot (', '1', ''),
(97, 'Sygol', 'SygolBot', '1', ''),
(98, 'WWWeasel', 'WWWeasel Robot v', '1', ''),
(99, 'Naver', 'nhnbot@naver.com', '1', ''),
(100, 'MMSBot', 'http://www.mmsweb.at/bot.html', '1', ''),
(101, 'Hogsearch', 'oegp v. ', '1', ''),
(102, 'Kraehe', '-DIE-KRAEHE- META-SEARCH-ENGINE/', '1', ''),
(103, 'Vagabondo', 'http://webagent.wise-guys.nl/', '1', ''),
(104, 'Nimble', 'NimbleCrawler', '1', ''),
(105, 'Bunnybot', 'powered by www.buncat.de', '1', ''),
(106, 'Boitho', 'boitho.com-dc/', '1', ''),
(107, 'Scumbot', 'Scumbot/', '1', ''),
(108, 'GeigerzaehlerBot', 'http://www.geigerzaehler.org/bot.html', '1', ''),
(109, 'Orbiter', 'http://www.dailyorbit.com/bot.htm', '1', ''),
(110, 'ASPseek', 'ASPseek/', '1', ''),
(111, 'Crawler Search', '.Crawler-Search.de', '1', ''),
(112, 'Singingfish Asterias', 'Asterias', '1', ''),
(113, 'NetResearchServer', 'NetResearchServer/', '1', ''),
(114, 'OrangeSpider', 'OrangeSpider', '1', ''),
(115, 'McSeek', 'powered by www.McSeek.de', '1', ''),
(116, 'Accoona', 'Accoona-AI-Agent/', '1', ''),
(117, 'Webmeasurement', 'webmeasurement-bot,', '1', ''),
(118, '123spider', '123spider-Bot', '1', ''),
(119, 'Cometrics', 'cometrics-bot,', '1', ''),
(120, 'Houxou', 'HouxouCrawler/', '1', ''),
(121, 'Ocelli', 'Ocelli/', '1', ''),
(122, 'EchO!', 'EchO!/', '1', ''),
(123, 'Gigablast', 'gigablast.com/', '1', ''),
(124, 'SurveyBot [Bot]', 'SurveyBot/', '1', ''),
(125, 'Marvin Medhunt', 'Marvin', '1', ''),
(126, 'InfoSeek SideWinder', 'Infoseek SideWinder/', '1', ''),
(127, 'InternetSeer', 'InternetSeer', '1', ''),
(128, 'Rambler', 'StackRambler/', '1', ''),
(129, 'Vestris Alkaline [Bot]', 'AlkalineBOT/', '1', ''),
(130, 'Robozilla', 'Robozilla/', '1', ''),
(131, 'Openfind', 'openfind.com', '1', ''),
(132, 'Diggit!', 'Digger/', '1', ''),
(133, 'Become', 'become.com/', '1', ''),
(134, 'NetSprint', 'NetSprint', '1', ''),
(135, 'Szukacz', 'szukacz', '1', ''),
(136, 'Gooro', 'Gooru-WebSpider', '1', ''),
(137, 'Onet', 'OnetSzukaj', '1', ''),
(138, 'Inktomi', 'Inktomi', '1', ''),
(139, 'Kraehe [Metasuche]', '-DIE-KRAEHE- META-SEARCH-ENGINE/', '1', ''),
(140, 'SnapPreview [bot]', 'SnapPreviewBot', '1', ''),
(141, 'XML Sitemap Generator [bot]', 'XML Sitemaps Generator', '1', ''),
(142, 'Google Sitemap [bot]', 'GSMA/', '1', ''),
(143, 'Larbin [bot]', 'larbin_2.6.3', '1', ''),
(144, 'Seznam [Bot]', 'SeznamBot', '1', ''),
(145, 'Indy Library [Bot]', 'Indy Library', '1', ''),
(146, 'Crawler0.1 [Crawler]', 'Crawler0.1', '1', ''),
(147, 'VoilaBot [Bot]', 'VoilaBot', '1', ''),
(148, 'Sogou [Bot]', 'Sogou web spider', '1', ''),
(149, 'MWI [bot]', 'MWI-UCE-Checker', '1', ''),
(150, 'Lycos [spider]', 'Lycos_Spider_', '1', ''),
(151, 'Speedy [spider]', 'Speedy Spider', '1', ''),
(152, 'Pagebull', 'Pagebull', '1', ''),
(153, 'panscient [spider]', 'panscient.com', '1', ''),
(154, 'libwww-perl [bot]', 'libwww-perl', '1', ''),
(155, 'SBIder [bot]', 'SBIder/', '1', ''),
(156, 'PHP version tracker', 'PHP version tracker', '1', ''),
(157, 'hbtronix [spider]', 'hbtronix.spider', '1', ''),
(158, 'over-zealus [bot]', 'Opera/5.0 (Windows NT 4.0;US)', '1', ''),
(159, 'HP Web PrintSmart [Bot]', 'HP Web PrintSmart', '1', ''),
(160, 'Ahrefs [Bot]', 'AhrefsBot/', '1', ''),
(161, 'Proximic [Bot]', 'proximic', '1', ''),
(162, 'ChangeDetection [Bot]', 'changedetection.com', '1', ''),
(163, 'Yandex [Bot]', 'YandexBot', '1', ''),
(164, 'ShopWiki [Bot]', 'ShopWiki', '1', ''),
(165, 'Genieo Web Filter [Bot]', 'Genieo/', '1', ''),
(166, 'DotBot [Bot]', 'DotBot/', '1', ''),
(167, 'CCBot [Bot]', 'CCBot/', '1', ''),
(168, 'meanpathbot [Bot]', 'meanpathbot/', '1', ''),
(169, 'spbot [Bot]', 'spbot/', '1', ''),
(170, 'magpie-crawler [Bot]', 'magpie-crawler', '1', ''),
(171, 'Baiduspider [Bot]', 'Baiduspider/', '1', ''),
(172, 'Woko [bot]', 'Woko', '1', ''),
(173, 'Spinn3r [Bot]', 'Spinn3r', '1', ''),
(174, 'SEO ENG World [Bot]', 'SEOENGWorldBot/', '1', ''),
(175, 'Easou Spider [Bot]', 'EasouSpider', '1', ''),
(176, 'NaverBot [Bot]', 'NaverBot/', '1', ''),
(177, 'Yeti Naver [Bot]', 'Yeti', '1', ''),
(178, 'Coccoc [Bot]', 'coccoc/', '1', ''),
(179, 'Daumoa [Bot]', 'Daumoa/', '1', ''),
(180, 'GrapeshotCrawler [Bot]', 'GrapeshotCrawler/', '1', ''),
(181, 'omgilibot [Bot]', 'omgilibot/', '1', ''),
(182, 'Mail.RU_Bot [Bot]', 'Mail.RU_Bot/', '1', ''),
(183, 'URLAppend [Bot]', 'URLAppendBot/', '1', ''),
(184, 'Sistrix [Bot]', 'SISTRIX', '1', ''),
(185, 'A6-Indexer [Bot]', 'A6-Indexer/', '1', ''),
(186, 'Semrush [Bot]', 'semrush', '1', ''),
(197, 'MegaIndex [bot]', 'megaindex.com', '1', ''),
(198, 'Admantx [bot]', 'admantx', '1', ''),
(199, 'Getintent [bot]', 'getintent', '1', ''),
(200, 'Deusu [bot]', 'deusu', '1', ''),
(201, 'Istellabot', 'istellabot', '1', ''),
(202, 'Socialrank', 'socialrank', '1', ''),
(203, 'ltx71 [bot]', 'ltx71', '1', ''),
(204, 'CommonCrawler [Bot]', 'CommonCrawler', '1', ''),
(205, 'Uptime [Bot]', 'uptime', '1', ''),
(206, 'Linkdex [bot]', 'linkdexbot', '1', ''),
(207, 'Twitter [bot]', 'Twitterbot', '1', ''),
(208, 'Tweetmeme [Bot]', 'TweetmemeBot', '1', ''),
(209, 'Xovi [Bot]', 'XoviBot', '1', ''),
(210, 'WeSEE Ads [Bot]', 'WeSEE:Ads/PageBot', '1', ''),
(211, 'Adv [Bot]', 'AdvBot', '1', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blog_article`
--

CREATE TABLE `blog_article` (
  `id` int(11) NOT NULL,
  `category` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `author` int(11) DEFAULT NULL,
  `picture_thumb` varchar(100) DEFAULT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `ordinal` int(11) DEFAULT NULL,
  `visits` int(11) DEFAULT NULL,
  `featured` varchar(1) NOT NULL DEFAULT '0',
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `blog_article`
--

INSERT INTO `blog_article` (`id`, `category`, `date`, `author`, `picture_thumb`, `picture`, `ordinal`, `visits`, `featured`, `active`) VALUES
(1, 1, '2025-08-21', 1, 'blog_article_1_20251212181454.jpg', 'blog_article_1_20251212181455.jpg', 45, 0, '1', '1'),
(2, 1, '2025-08-22', 1, 'blog_article_2_20251213103657.jpg', 'blog_article_2_20251213103658.jpg', 20, 0, '1', '1'),
(3, 2, '2025-11-23', 1, 'blog_article_3_20251213170507.jpg', 'blog_article_3_20251213170508.jpg', 30, 0, '1', '1'),
(4, 1, '2025-12-03', 1, 'blog_article_4_20251214103722.jpg', 'blog_article_4_20251214103723.jpg', 40, 0, '1', '1');

-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `blog_article_lang`
--

CREATE TABLE `blog_article_lang` (
  `id` int(11) NOT NULL,
  `article` int(11) DEFAULT NULL,
  `lang_code_2a` varchar(2) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `metadescription` longtext DEFAULT NULL,
  `picture_alt_text` varchar(255) DEFAULT NULL,
  `text` longtext DEFAULT NULL,
  `faq_title` varchar(160) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `blog_article_lang`
--

INSERT INTO `blog_article_lang` (`id`, `article`, `lang_code_2a`, `slug`, `title`, `metadescription`, `picture_alt_text`, `text`, `faq_title`) VALUES
(3, 2, 'es', '5-automatizaciones-que-toda-pyme-deberia-tener', '5 automatizaciones que toda PYME española debería tener', 'Descubre las 5 automatizaciones que toda PYME española debe tener antes de 2026 para ahorrar tiempo, reducir costes y aumentar ventas.', 'Persona trabajando en un ordenador con iconos de automatización, como facturas, chatbot, gráficos, etc. que representa la digitalización de las PYMES en España.', '&lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    Las PYMES que no automatizan sus procesos clave están poniendo en riesgo su rentabilidad, su crecimiento y su capacidad para competir.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    No necesitas un equipo de IT ni un presupuesto de 50.000€.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    Estas son las 5 automatizaciones más efectivas — y que nadie te está contando en español.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    1. Automatización de la facturación y seguimiento de cobros\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    ¿Cuántas horas al mes pierdes gestionando facturas, recordatorios y cobros? En España, el 68% de las PYMES tienen retrasos en cobros por falta de seguimiento automático.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    La solución: Conecta a tu sistema de facturación ERP (Facturae, Sage, o incluso Excel) con nuestra solución que:\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;Sube facturas o tickets desde una foto en Whatsapp a tu ERP&lt;/li&gt;\r\n                    &lt;li&gt;Gestione los vencimientos de esas facturas.&lt;/li&gt;\r\n                    &lt;li&gt;Envíe un recordatorio automático 3 días antes del vencimiento.&lt;/li&gt;\r\n                    &lt;li&gt;Notifique por WhatsApp al cliente el estado de los pagos.&lt;/li&gt;\r\n                    &lt;li&gt;...&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                            Una empresa de consultoría en Sevilla redujo los cobros pendientes de 15 días a 4 días, y aumentó su flujo de caja en un 32%.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    2. Gestión automática de pedidos y stock\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    ¿Has perdido una venta porque un cliente llamó y te dijeron “no tenemos stock”? En España, el 41% de las PYMES de comercio minorista pierden ventas por falta de sincronización entre tienda online y almacén.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    La solución: Con nuestra solución conecta tu tienda online (Shopify, WooCommerce) con tu gestor de inventario (Odoo, Zoho Inventory). Cuando se venda un producto:\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;Se descuenta automáticamente del stock.&lt;/li&gt;\r\n                    &lt;li&gt;Se envía un email al cliente con el tracking.&lt;/li&gt;\r\n                    &lt;li&gt;Se genera una alerta si el stock baja de 3 unidades (lo decides tú).&lt;/li&gt;\r\n                    &lt;li&gt;...&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                           Una tienda de artículos de jardín en Tarragona redujo sus errores de stock en un 90% y aumentó sus ventas en un 21%.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    3. Atención al cliente con agentes IA sin programación\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    ¿Cuántas veces has tenido que responder a la misma pregunta: “¿Cuánto cuesta?”, “¿Tienen envío a Canarias?”, “¿Cuánto tarda?”\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    La solución: Usa un agente IA que lo haga por tí. Las voces ya no son sintéticas, son fabulosas. Entrena al bot con tus respuestas frecuentes y conecta con WhatsApp y tu web.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;Responde preguntas 24/7.&lt;/li&gt;\r\n                    &lt;li&gt;Recoge datos del cliente (nombre, teléfono, necesidad).&lt;/li&gt;\r\n                    &lt;li&gt;Deriva a un humano si la pregunta es compleja.&lt;/li&gt;\r\n                    &lt;li&gt;...&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                           Un taller de reparación en Zaragoza redujo su carga de atención al cliente en un 65% y aumentó sus citas programadas en un 40%.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    4. Automatización de la gestión de leads desde redes sociales\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    ¿Recibes mensajes en Instagram o Facebook y los pierdes entre el caos del día? El 73% de las PYMES no responden a mensajes en redes en menos de 24 horas.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    Usa nuetra solución para conectar tus redes sociales con Google Sheets o HubSpot. Cada vez que alguien te escriba:\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;Se crea un registro automático con su nombre, mensaje y red.&lt;/li&gt;\r\n                    &lt;li&gt;Se envía un email de bienvenida con tu catálogo.&lt;/li&gt;\r\n                    &lt;li&gt;Se agrega a una lista de seguimiento.&lt;/li&gt;\r\n                    &lt;li&gt;...&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                           Un diseñador gráfico en Ourense captó 18 nuevos clientes en 30 días sin levantar la cabeza de su escritorio.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    5. Informes semanales automáticos de KPIs\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    ¿Te pasas el lunes por la mañana haciendo tablas de Excel para ver cómo fue la semana? Eso no es trabajo. Eso es pérdida de tiempo.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    a solución: Conecta tus herramientas (Google Analytics, CRM, facturación) con Power BI o Data Studio. Configura un informe semanal que te llegue por email los lunes a las 9:00.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;Ventas de la semana.&lt;/li&gt;\r\n                    &lt;li&gt;Clientes nuevos.&lt;/li&gt;\r\n                    &lt;li&gt;Conversiones por canal.&lt;/li&gt;\r\n                    &lt;li&gt;Coste por adquisición.&lt;/li&gt;\r\n                    &lt;li&gt;...&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                           Un restaurante en Bilbao tomó decisiones de compra basadas en datos, no en intuiciones — y aumentó su margen en un 18%.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    ¿Qué sigue?\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    Estas 5 soluciones de automatizaciones no requieren saber de programación. Solo requieren de uno de nuestros servicios. Y si lo haces ahora, en 2026 estarás 3 años por delante de tu competencia.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    ¿Quieres que te ayude a implementar una de ellas en tu empresa?\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    Agenda una evaluación &lt;b&gt;gratuita de 30 minutos&lt;/b&gt; y te mostramos cómo automatizar tu proceso más pesado.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    Haz clic aquí para agendar tu sesión: &lt;a href=&quot;/altiraautomations.com/&quot;&gt;evaluacion gratuita&lt;/a&gt;\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;', 'Preguntas frecuentes (FAQ) sobre procesos de automatización para PYMES'),
(16, 1, 'en', 'what-are-ai-agents', 'What are AI Agents? The key to sustainable business growth', 'Discover what AI Agents are and how they differ from RPA. Learn why this technology is the single most powerful tool for UK business growth and efficiency.', 'Illustration of AI agents optimizing business workflows', '&lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    For years, the world of business efficiency has been dominated by &lt;b&gt;Robotic Process Automation (RPA).&lt;/b&gt; While RPA was revolutionary in handling repetitive tasks, modern challenges demand more than simple repetition—they require &lt;b&gt;intelligence&lt;/b&gt;.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    This is the era of the &lt;b&gt;AI Agent.&lt;/b&gt; If you are looking to scale your company, break free from operational bottlenecks, and achieve true, measurable growth, understanding the power of AI Agents is the essential first step.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    This article clarifies what an &lt;b&gt;AI Agent&lt;/b&gt; is, why it is radically different from traditional automation, and how it becomes the most powerful tool for generating &lt;b&gt;sustainable business growth.&lt;/b&gt;\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    RPA vs. AI Agents: The difference between following and thinking\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    The main distinction lies in their core capability:\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;&lt;b&gt;RPA (The Follower):&lt;/b&gt; Traditional RPA is task-specific. It follows rigid, pre-programmed rules (if X happens, then do Y). It is excellent for clear, predictable, high-volume tasks. However, it cannot adapt, learn, or make decisions in ambiguous situations. When a process encounters an exception, the RPA system stops.&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;AI Agent (The Thinker):&lt;/b&gt; An AI Agent, often based on Machine Learning and Large Language Models (LLMs), not only follows rules but can &lt;b&gt;reason, plan, and execute multi-step tasks.&lt;/b&gt; It learns from data, adapts to changes in the process, and can handle ambiguity (e.g., interpreting a text-free email request). The AI Agent is not just software; it is a &lt;b&gt;digital team member&lt;/b&gt; capable of autonomously solving problems within defined limits.&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                             RPA only follows fixed rules; AI Agents can reason, plan, and adapt. This capability is key to overcoming bottlenecks.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    Why AI Agents are key to business growth\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    For businesses seeking exponential growth, AI Agents provide solutions where traditional methods fail:\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;&lt;b&gt;Handling complexity:&lt;/b&gt; They can manage end-to-end processes that involve data analysis, communication (emails/chat), and decision-making—tasks too complex for RPA alone.&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;True scalability:&lt;/b&gt; Since Agents can handle ambiguity and exceptions, they can process massive volumes of work without requiring human intervention for every minor issue. This allows the business to scale operations without proportionally increasing personnel.&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;Focus on strategic value:&lt;/b&gt; By taking over high-friction tasks (data entry, drafting initial reports, customer classification), Agents free up highly skilled human teams to focus on strategy, creativity, and direct customer relationships—where the human touch is irreplaceable.&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    The result is a direct impact on the &lt;b&gt;Return on Investment (ROI)&lt;/b&gt;, not only through cost reduction but through significant revenue growth.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    The pillars of success with AI Agents\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    Implementing AI Agents requires a strategic approach to guarantee a positive outcome:\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ol&gt;\r\n                    &lt;li&gt;&lt;b&gt;Strategic process selection:&lt;/b&gt; Success begins by identifying the processes with the highest cost of time, highest volume, and highest risk of human error. These are the &quot;quick wins&quot; that ensure a fast ROI.&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;Solutions tailored to you:&lt;/b&gt; A generic solution will always fail. Altira Automations focuses on designing Agents that are &lt;b&gt;tailored to the specific culture and systems&lt;/b&gt; of your company (ERP, CRM, etc.), ensuring seamless and effective integration.&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;Human trust and control:&lt;/b&gt; We integrate the human factor not as an obstacle, but as a crucial layer of validation. The Agents are designed to function as &quot;copilots,&quot; allowing human teams to review and refine critical actions before execution, guaranteeing &lt;b&gt;100%&lt;/b&gt; reliability and minimizing operational risk.&lt;/li&gt;\r\n                &lt;/ol&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                          AI Agents enable true scalability by managing complexity. Success requires strategic selection and bespoke solutions tailored to your unique systems.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    AI Agents are more than a technological trend; they are the necessary evolution of automation. They offer the ability to scale your operations, reduce critical errors, and direct your human talent toward true business growth.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    If your company is ready to take the step toward &lt;b&gt;sustainable and intelligent growth&lt;/b&gt; in the UK market, the time to understand and implement AI Agents is now.\r\n                &lt;/p&gt;\r\n                &lt;p style=&quot;font-weight: bold&quot;&gt;\r\n                    Ready to scale? Contact us today to map your first AI Agent process.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                   &lt;a href=&quot;/contact&quot;&gt;contact us&lt;/a&gt;.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;', 'Frequently asked questions about  AI Agent Automation'),
(17, 1, 'es', 'que-es-una-automatizacion-ia', '¿Qué es una Automatización IA y cómo impulsa tu negocio?', 'Descubre la automatización de procesos empresariales con agentes de IA. Aumenta la productividad, reduce costes y escala tu negocio de forma segura.', 'Ilustración de agentes IA optimizando flujos de trabajo de negocio', '&lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n    &lt;p&gt;\r\n        Hola y bienvenido/a al blog de Altira Automations. En el mundo empresarial actual, una pregunta resuena en cada oficina y sala de juntas: &lt;b&gt;&quot;¿Cómo podemos hacer más con menos?&quot;&lt;/b&gt;\r\n    &lt;/p&gt;\r\n    &lt;p&gt;\r\n        La respuesta está en el poder de la automatización. Pero no hablamos solo de simples macros o herramientas preprogramadas; nos referimos a la &lt;b&gt;Automatización Inteligente&lt;/b&gt; impulsada por &lt;b&gt;Agentes de Inteligencia Artificial.&lt;/b&gt;\r\n    &lt;/p&gt;\r\n    &lt;p&gt;\r\n        Este concepto ha dejado de ser una tecnología futurista para convertirse en una herramienta indispensable para dueños de pequeños negocios, directores de grandes empresas y profesionales que buscan la máxima eficiencia.\r\n    &lt;/p&gt;\r\n    &lt;p&gt;\r\n        En este artículo, desgranaremos qué es exactamente la automatización de procesos empresariales (BPA), cómo la IA la lleva al siguiente nivel y por qué es el momento de que su empresa empiece a escalar de verdad.\r\n    &lt;/p&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;blog_article_h2&quot;&gt;\r\n    &lt;h2&gt;\r\n        Desmitificando el concepto: ¿Qué es la Automatización Empresarial?\r\n    &lt;/h2&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n    &lt;p&gt;\r\n        En su esencia más pura, la automatización empresarial consiste en utilizar la tecnología para ejecutar tareas y procesos que, de otro modo, requerirían intervención humana manual. El objetivo principal es la &lt;b&gt;liberación de recursos&lt;/b&gt; valiosos.\r\n    &lt;/p&gt;\r\n    &lt;p&gt;\r\n        Piense en su empresa. Hay tareas que se repiten una y otra vez: la entrada de datos en una base de clientes, el envío de correos electrónicos de seguimiento, la generación de informes contables, la gestión de inventario, etc. Estas tareas suelen ser tediosas, consumen tiempo y, al ser manuales, son propensas a errores humanos.\r\n    &lt;/p&gt;\r\n    &lt;p&gt;\r\n        La automatización de procesos, a través de software o bots, toma el relevo en estas actividades rutinarias. Al delegar estas funciones, su equipo puede centrarse en lo que realmente aporta valor: &lt;b&gt;la estrategia, la creatividad, la interacción humana&lt;/b&gt; compleja y la toma de decisiones. Es un cambio de paradigma: pasar de &quot;trabajar duro&quot; a &lt;b&gt;&quot;trabajar de forma inteligente y estratégica&quot;.&lt;/b&gt;\r\n    &lt;/p&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;blog_article_h2&quot;&gt;\r\n    &lt;h2&gt;\r\n        El Salto Cuántico: Agentes IA como el Corazón de Altir\r\n    &lt;/h2&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n    &lt;p&gt;\r\n        La automatización tradicional es excelente, pero la &lt;b&gt;Automatización Inteligente (AI)&lt;/b&gt;, el foco de Altira Automations, es donde la magia ocurre. Los Agentes de IA añaden una capa de &lt;b&gt;inteligencia, adaptabilidad y aprendizaje&lt;/b&gt; a la automatización.\r\n    &lt;/p&gt;\r\n    &lt;p&gt;\r\n        Un &lt;i&gt;bot&lt;/i&gt; de automatización normal sigue una regla: &quot;Si X ocurre, haz Y&quot;. Un &lt;b&gt;Agente de IA&lt;/b&gt; hace esto y mucho más. Puede:\r\n    &lt;/p&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;blog_article_list&quot;&gt;\r\n    &lt;ul&gt;\r\n        &lt;li&gt;&lt;b&gt;Aprender y mejorar:&lt;/b&gt; Detecta patrones en los datos y ajusta su comportamiento para ser más eficiente con el tiempo.&lt;/li&gt;\r\n        &lt;li&gt;&lt;b&gt;Tomar decisiones:&lt;/b&gt; Analiza información no estructurada (como un correo electrónico o un documento escaneado) y decide la mejor acción a seguir.&lt;/li&gt;\r\n        &lt;li&gt;&lt;b&gt;Manejar excepciones:&lt;/b&gt; Si un proceso se sale de la norma, el agente de IA puede intentar resolverlo o escalarlo de forma inteligente.&lt;/li&gt;\r\n    &lt;/ul&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n    &lt;p&gt;\r\n        En Altira Automations, implementamos estas soluciones a medida para que se adapten a la complejidad única de sus procesos. De esta manera, el sistema no solo ejecuta la tarea, sino que optimiza el proceso completo.\r\n    &lt;/p&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n    &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n        &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n            &lt;span&gt;\r\n                La automatización impulsa la eficiencia al liberar a su equipo de tareas repetitivas. La IA añade la capacidad de aprender, tomar decisiones y manejar procesos complejos.\r\n            &lt;/span&gt;\r\n        &lt;/div&gt;\r\n    &lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;blog_article_h2&quot;&gt;\r\n    &lt;h2&gt;\r\n        Beneficios Irrefutables: ¿Por Qué Automatizar Ahora\r\n    &lt;/h2&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n    &lt;p&gt;\r\n        Tanto si dirige una Pyme que necesita maximizar cada minuto, como si es un director de una gran empresa que busca optimizar departamentos enteros, los beneficios de la automatización inteligente son universales:\r\n    &lt;/p&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;blog_article_list&quot;&gt;\r\n    &lt;ol&gt;\r\n        &lt;li&gt;&lt;b&gt;Aumento Dramático de la Productividad:&lt;/b&gt; Las máquinas trabajan 24/7 sin descanso. Un proceso que a un humano le lleva una hora, un Agente IA puede completarlo en minutos.&lt;/li&gt;\r\n        &lt;li&gt;&lt;b&gt;Reducción Drástica de Errores:&lt;/b&gt; Al eliminar el error humano en tareas repetitivas de entrada o gestión de datos, se asegura una calidad y precisión del 100%.&lt;/li&gt;\r\n        &lt;li&gt;&lt;b&gt;Ahorro de Costes Operacionales:&lt;/b&gt; La eficiencia se traduce directamente en menores costes laborales y operativos a largo plazo.&lt;/li&gt;\r\n        &lt;li&gt;&lt;b&gt;Escalabilidad sin Límites:&lt;/b&gt; ¿Necesita manejar un aumento repentino en la demanda? Un sistema automatizado escala instantáneamente sin necesidad de contratar y capacitar a nuevo personal.&lt;/li&gt;\r\n        &lt;li&gt;&lt;b&gt;Seguridad y Confidencialidad (El pilar de Altira):&lt;/b&gt; Los procesos automatizados son auditables y consistentes. En Altira, priorizamos la seguridad y la confidencialidad en el diseño de nuestras soluciones a medida, asegurando que sus datos sensibles estén siempre protegidos.&lt;/li&gt;\r\n    &lt;/ol&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;blog_article_h2&quot;&gt;\r\n    &lt;h2&gt;\r\n        Ejemplos Prácticos de Automatización IA\r\n    &lt;/h2&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n    &lt;p&gt;\r\n        La automatización no es solo para gigantes tecnológicos. Aquí hay tres ejemplos claros de cómo las soluciones de Altira Automations están ayudando a empresas reales:\r\n    &lt;/p&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;blog_article_list&quot;&gt;\r\n    &lt;ul&gt;\r\n        &lt;li&gt;&lt;b&gt;En Finanzas y Contabilidad:&lt;/b&gt; Automatización de la gestión de facturas. El Agente IA lee, clasifica, verifica los datos con el sistema ERP y procesa el pago, marcando excepciones solo si detecta fraude o inconsistencias.&lt;/li&gt;\r\n        &lt;li&gt;&lt;b&gt;En Atención al Cliente:&lt;/b&gt; Implementación de &lt;i&gt;chatbots&lt;/i&gt; inteligentes que no solo responden preguntas frecuentes, sino que pueden acceder a la base de datos del cliente, resolver incidencias de primer nivel y crear tickets de soporte automáticamente.&lt;/li&gt;\r\n        &lt;li&gt;&lt;b&gt;En Recursos Humanos:&lt;/b&gt; Automatización del proceso de onboarding de nuevos empleados: el Agente IA recopila documentos, genera contratos, inscribe al empleado en los sistemas internos y envía correos de bienvenida programados.&lt;/li&gt;\r\n    &lt;/ul&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n    &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n        &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n            &lt;span&gt;\r\n               Los principales beneficios son el trabajo 24/7, la precisión sin errores y la escalabilidad. Altira ofrece soluciones a medida priorizando la seguridad.\r\n            &lt;/span&gt;\r\n        &lt;/div&gt;\r\n    &lt;/div&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;blog_article_h2&quot;&gt;\r\n    &lt;h2&gt;\r\n        tu socio en la Transformación Digital\r\n    &lt;/h2&gt;\r\n&lt;/div&gt;\r\n&lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n    &lt;p&gt;\r\n        Esperamos que esta introducción haya iluminado el potencial de la Automatización IA. Es la llave para desbloquear el verdadero potencial de su negocio, permitiéndole escalar, ahorrar y competir en el mercado del futuro.\r\n    &lt;/p&gt;\r\n    &lt;p&gt;\r\n        En &lt;a href=&quot;/&quot;&gt;Altira Automations&lt;/a&gt;, nuestra &lt;a&gt;profesionalidad, confidencialidad y enfoque en soluciones a medida&lt;/a&gt; garantizan que su salto a la digitalización sea seguro, fluido y exitoso. No se trata solo de implementar tecnología, sino de construir un futuro más eficiente para su empresa.\r\n    &lt;/p&gt;\r\n&lt;/div&gt;', 'Preguntas frecuentes'),
(19, 2, 'en', '5-essential-automations', '5 Essential automations every UK SME should implement now', 'Boost efficiency and cash flow. Discover the 5 most effective AI automations UK SMEs need to implement to compete, scale, and eliminate manual errors.', 'Persona trabajando en un ordenador con iconos de automatización, como facturas, chatbot, gráficos, etc. que representa la digitalización de las PYMES en España.', '&lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    SMEs (Small and Medium-sized Enterprises) that fail to automate their core processes are jeopardizing their profitability, growth, and ability to compete.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    You don\'t need a massive IT team or a £50,000 budget.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    These are the &lt;b&gt;5 most effective automations&lt;/b&gt;—the solutions that truly move the needle—that no one in the UK market is talking about yet.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    1. Automated Invoicing and Collections Tracking\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    How many hours per month do you waste managing invoices, chasing payments, and sending reminders? In the UK, late payments are a significant factor impacting the cash flow of small businesses.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;The solution:&lt;/b&gt; Connect your invoicing system (Xero, QuickBooks, or even a local spreadsheet) with our &lt;b&gt;AI Agent Solution&lt;/b&gt; that can:\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;Upload invoices or receipts from a photo or email directly to your ERP.&lt;/li&gt;\r\n                    &lt;li&gt;Manage the due dates and payment terms automatically.&lt;/li&gt;\r\n                    &lt;li&gt;Send an automated payment reminder email 3 days before the due date.&lt;/li&gt;\r\n                    &lt;li&gt;Notify the client via email or SMS about the payment status.&lt;/li&gt;\r\n                    &lt;li&gt;...&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;Real-World result:&lt;/b&gt; A consulting firm in &lt;b&gt;Manchester&lt;/b&gt; reduced its outstanding collection time from 15 days to just 4 days, boosting their &lt;b&gt;cash flow by 32%.&lt;/b&gt;\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    2. Automatic Order and Inventory Management\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    Have you ever lost a sale because a customer called and you had to say, “Sorry, we’re out of stock”? Lack of synchronization between the online shop and the warehouse is a common source of lost revenue.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;The solution:&lt;/b&gt; Our AI &lt;b&gt;Agent Solution&lt;/b&gt; seamlessly connects your online store (Shopify, WooCommerce) with your inventory management system (Odoo, Zoho Inventory, or bespoke systems). When a product is sold:\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;It is automatically deducted from stock across all platforms.&lt;/li&gt;\r\n                    &lt;li&gt;A confirmation email with the tracking link is sent to the customer instantly.&lt;/li&gt;\r\n                    &lt;li&gt;It generates a low-stock alert if inventory drops below a set threshold (you decide the number).&lt;/li&gt;\r\n                    &lt;li&gt;...&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;Real-World result:&lt;/b&gt; A garden supplies retailer in &lt;b&gt;Bristol&lt;/b&gt; reduced its stock errors by 90% and increased sales by 21% by eliminating overselling and underselling.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                             Automate your cash flow and stock. AI Agents track payments, send reminders, and sync inventory across all online platforms.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    3. AI Agent Customer Service Without Programming\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    How often do you or your team have to answer the same repetitive questions: “How much does it cost?”, “Do you ship to Scotland?”, “What is the delivery time?”\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;The solution:&lt;/b&gt; Deploy an &lt;b&gt;AI Agent&lt;/b&gt; trained on your specific business knowledge base to handle these queries. The voices are no longer synthetic; they are fabulous and natural. Train the bot with your FAQs and connect it to your website chat and social media messaging.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;Answers common questions 24/7, even outside UK business hours.&lt;/li&gt;\r\n                    &lt;li&gt;Collects crucial client data (name, phone, specific need).&lt;/li&gt;\r\n                    &lt;li&gt;Derives and escalates complex queries seamlessly to a human team member.&lt;/li&gt;\r\n                    &lt;li&gt;...&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;Real-World result:&lt;/b&gt; A vehicle repair workshop in &lt;b&gt;Birmingham&lt;/b&gt; cut its customer service workload by 65% and increased scheduled appointments by 40% because the Agent pre-qualified every lead.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    4. Automated Lead Management from Social Media\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    Do you receive inquiries on Instagram or Facebook only to lose them in the daily chaos? Slow response times on social media directly translate to lost opportunities.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;The solution:&lt;/b&gt; Use our &lt;b&gt;AI Agent Solution&lt;/b&gt; to connect your social media channels (Instagram, Facebook) directly with your CRM or Google Sheets. Every time someone sends you a message:\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;An automatic record is created with their name, message content, and platform source.&lt;/li&gt;\r\n                    &lt;li&gt;A personalized welcome email with your catalogue or service guide is sent immediately.&lt;/li&gt;\r\n                    &lt;li&gt;They are added to a specific follow-up list for your sales team.&lt;/li&gt;\r\n                    &lt;li&gt;...&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;Real-World result:&lt;/b&gt; A graphic design agency in &lt;b&gt;Leeds&lt;/b&gt; captured 18 new qualified clients in 30 days without distracting their designers from their core work.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                          Capture leads 24/7. Use AI Agents to answer FAQs and instantly log social media messages into your CRM for follow-up.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    5. Automatic Weekly KPI Reporting\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    Do you spend Monday morning pulling data into spreadsheets just to see how the previous week performed? That is not working; that is wasting precious time.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;The solution:&lt;/b&gt; Connect your existing tools (Google Analytics, CRM, Invoicing) with a dashboard solution like Power BI or Looker Studio. Configure a weekly report to arrive in your inbox every Monday at 9:00 AM.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;Weekly Sales performance.&lt;/li&gt;\r\n                    &lt;li&gt;New customer acquisition rate.&lt;/li&gt;\r\n                    &lt;li&gt;Conversions by channel.&lt;/li&gt;\r\n                    &lt;li&gt;Customer Acquisition Cost (CAC).&lt;/li&gt;\r\n                    &lt;li&gt;...&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;Real-World result:&lt;/b&gt; A restaurant chain in &lt;b&gt;London&lt;/b&gt; made purchasing decisions based on hard data, not intuition—and increased their profit margin by 18%.\r\n                    What’s Next?\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    These &lt;b&gt;5 AI Agent automations&lt;/b&gt; don\'t require you to know a single line of code. They only require one of our bespoke services. By implementing these solutions now, you will be three years ahead of your UK competition by 2026.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    Would you like me to help you implement one of these game-changing automations in your business?\r\n                &lt;/p&gt;\r\n                &lt;p style=&quot;font-weight: bold&quot;&gt;\r\n                    Book a free 30-minute evaluation, and we will show you how to automate your biggest bottleneck.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                   &lt;a href=&quot;/contact&quot;&gt;contact us&lt;/a&gt;.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;', 'Frequently Asked Questions (FAQ) for UK SMEs');
INSERT INTO `blog_article_lang` (`id`, `article`, `lang_code_2a`, `slug`, `title`, `metadescription`, `picture_alt_text`, `text`, `faq_title`) VALUES
(24, 3, 'en', 'ai-automation-myths-security', 'AI Automation: Myths, Security &amp;amp; Fast ROI for UK Business', 'Debunk AI implementation myths. Discover how Altira ensures data security and rapid ROI with bespoke automation solutions for your critical processes.', 'Ilustración de un escudo digital revelando procesos IA seguros, disipando mitos y miedos empresariales.', '&lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    Having explored what &lt;b&gt;AI Automation&lt;/b&gt; is and which core processes drive SME growth, many business leaders face a mental barrier: fear of implementation. This is natural. The idea of introducing &lt;b&gt;AI Agents&lt;/b&gt; into critical processes is often surrounded by myths and concerns about cost, complexity, and, most importantly, &lt;b&gt;data security.&lt;/b&gt;\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    At Altira Automations, we know that AI implementation is only successful if it is safe, transparent, and strategic. This article will dismantle the most common misconceptions and show you the clear, professional path we follow to ensure your jump to automation is a success with a &lt;b&gt;fast Return on Investment (ROI).&lt;/b&gt;\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    Three Common Myths That Hinder Automation\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    Misinformation is the main enemy of progress. It is time to break down the myths preventing many businesses from achieving efficiency:\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;Myth 1:&lt;/b&gt; &quot;AI Automation is only for multinationals with giant budgets.&quot;\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;Reality:&lt;/b&gt; While large corporations invest heavily, Robotic Process Automation (RPA) and AI Agents technology have been democratized. At Altira, we specialize in bespoke, modular solutions. By starting with high-impact, low-cost implementation processes, the ROI is quickly justified, making the investment accessible even for UK SMEs.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;Myth 2:&lt;/b&gt; &quot;ROI takes years to materialize; It\'s a Long-Term gamble.&quot;\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;Reality:&lt;/b&gt; If the process selection is strategic (as we detailed in our previous article), the return is visible very quickly, not in years. The immediate reduction in high time-cost tasks and the elimination of human error risk free up capital and working hours from day one.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;Myth 3:&lt;/b&gt; &quot;Implementation is long, complicated, and slows down daily operations.&quot;\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;Reality:&lt;/b&gt; A professional partner like Altira Automations uses the &lt;b&gt;Agile methodology.&lt;/b&gt; AI implementation is carried out in phases, often in parallel with the existing manual process (testing), minimizing any operational disruption and guaranteeing business continuity.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                             Myths about cost and complexity are false; AI automation is accessible and offers fast ROI. Focus on quick wins.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    Altira\'s pillar: Ironclad security and confidentiality\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    The most legitimate concern when automating processes is the management of sensitive data. At Altira Automations, Data Security and Confidentiality are not an extra; they are the heart of our professionalism and commitment to the UK market.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;&lt;b&gt;Secure infrastructure:&lt;/b&gt; Our AI Agents operate in highly controlled environments. We implement dedicated, secure infrastructures, ensuring compliance with the strictest UK security regulations (such as GDPR).&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;Traceability and auditability:&lt;/b&gt; Unlike manual work (where errors can be difficult to track), every action taken by an AI Agent is logged. This provides complete process traceability, facilitating any internal or external audit.&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;Minimum necessary access:&lt;/b&gt; We design AI Agents under the &lt;b&gt;&quot;principle of least privilege.&quot;&lt;/b&gt; They only have access to the data and systems strictly necessary to execute their task, shielding the rest of your business ecosystem.&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                             Data security is guaranteed through compliance (GDPR), dedicated infrastructure, complete traceability, and minimum privilege access.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    Our Altira methodology: The safe path to efficienc\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    The key to overcoming fears is having a clear and structured plan. Our methodology, aimed at achieving &lt;b&gt;Rapid ROI,&lt;/b&gt; is summarized in four transparent phases:\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;&lt;b&gt;Consulting and discovery (the human factor):&lt;/b&gt; This initial phase, an essential part of our service, involves deep immersion with your team. We identify the highest-value processes (those with high risk/high time-cost) and gather nuances to ensure the solution is 100% tailored to your reality (a bespoke solution).&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;Bespoke solution design:&lt;/b&gt; Based on the discovery phase, we design the AI Agent architecture. We define which technology will be used (RPA, Machine Learning, etc.) and how it will integrate seamlessly with your current systems (ERP, CRM, etc.).&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;Implementation, testing, and go-live:&lt;/b&gt; The AI Agent is built, and then subjected to rigorous testing in a controlled environment. Only when precision is guaranteed and your team is trained, does the Agent transition to the productive environment (Go-Live).&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;Support and continuous optimization:&lt;/b&gt; Automation is not static. We offer continuous support, monitor the AI Agent\'s performance, and make adjustments to further optimize the process, ensuring the benefit is sustained long-term.&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                          Our method includes Discovery, Bespoke Design, rigorous Testing before Go-Live, and Continuous Support to maximise success.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    Your trusted partner in transformation\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    The fear of the unknown is natural, but it should not halt your business potential. Process AI Automation with AI Agents is the most powerful tool for scaling, provided it is approached with professionalism and security.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    At Altira Automations, we don\'t just implement technology; we offer the guarantee that your digital transformation will be secure, confidential, and have a clear path to a fast ROI.\r\n                &lt;/p&gt;\r\n                &lt;p style=&quot;font-weight: bold&quot;&gt;\r\n                    If you are ready to take the step, but want to do so with the peace of mind of having an expert partner by your side, contact us. Let\'s discuss your most challenging process, and how we can automate it without risk.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    &lt;a href=&quot;/contact&quot;&gt;contact us&lt;/a&gt; today to ensure your AI implementation is secure, compliant, and delivers fast ROI.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;', 'Frequently Asked Questions about AI Implementation and Security'),
(25, 3, 'es', 'automatizacion-ia-mitos-realidades-y-seguridad', 'Automatización IA: Mitos, Realidades y Seguridad', 'Derriba los mitos de la implementación IA. Descubre cómo Altira garantiza la seguridad de datos y un ROI rápido con soluciones de automatización a medida.', 'Ilustración de un escudo digital revelando procesos IA seguros, disipando mitos y miedos empresariales.', '&lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    Tras explorar qué es la Automatización con Agentes IA y cuáles son los procesos clave que impulsan el crecimiento de las PYMES, muchos líderes empresariales se encuentran con una barrera mental: &lt;b&gt;el miedo a la implementación.&lt;/b&gt;\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    Es natural. La idea de introducir Agentes IA en procesos críticos a menudo está rodeada de &lt;b&gt;mitos y preocupaciones&lt;/b&gt; sobre costes, complejidad y, lo más importante, la &lt;b&gt;seguridad de los datos.&lt;/b&gt;\r\n                    En Altira Automations, sabemos que la implementación IA solo es exitosa si es segura, transparente y estratégica.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    En Altira Automations, sabemos que la &lt;b&gt;implementación IA&lt;/b&gt; solo es exitosa si es &lt;b&gt;segura, transparente y estratégica.&lt;/b&gt;\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    Este artículo desmantelaremos las creencias erróneas más comunes y te mostraremos el camino claro y profesional que seguimos para garantizar que su salto a la automatización sea un éxito con un &lt;b&gt;Retorno de la inversión (ROI) rápido.&lt;/b&gt;\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    Tres mitos comunes que frenan la automatización\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;h3&gt;\r\n                    Mito 1: &quot;La automatización IA es solo para multinacionales con presupuestos gigantes.&quot;\r\n                &lt;/h3&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;Realidad:&lt;/b&gt; Si bien las grandes corporaciones invierten mucho, la tecnología Automatización Robótica de Procesos (RPA) y los Agentes IA se han democratizado.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    En Altira somos especialistas en &lt;b&gt;soluciones a medida&lt;/b&gt; y modulares.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    Al empezar con procesos de alto impacto y bajo coste de implementación, el ROI se justifica rápidamente, haciendo que la inversión sea accesible incluso para pequeñas y medianas empresas.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;h3&gt;\r\n                    Mito 2: &quot;El ROI tarda años en materializarse, es una apuesta a largo plazo.&quot;\r\n                &lt;/h3&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;Realidad:&lt;/b&gt; Si la selección del proceso es estratégica (como enseñamos en nuestro artículo anterior), el retorno se ve muy rápidamente y no en años.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    La reducción inmediata del &lt;b&gt;alto coste de tiempo&lt;/b&gt; y la eliminación del &lt;b&gt;riesgo de error humano&lt;/b&gt; liberan capital y horas de trabajo desde el primer momento.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;h3&gt;\r\n                    Mito 3: &quot;La implementación es larga, complicada y frena la operación diaria.&quot;\r\n                &lt;/h3&gt;\r\n                &lt;p&gt;\r\n                    &lt;b&gt;Realidad:&lt;/b&gt; Un socio profesional como Altira Automations utilizamos la metodología Agile. La &lt;b&gt;implementación IA&lt;/b&gt; se realiza de forma escalonada, a menudo en paralelo con el proceso manual (pruebas), minimizando cualquier interrupción operativa y garantizando la continuidad del negocio.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    El pilar de Altira: Seguridad y confidencialidad blindadas\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    La preocupación más legítima al &lt;b&gt;automatizar procesos&lt;/b&gt; es la gestión de datos sensibles. En Altira Automations, la &lt;b&gt;Seguridad de Datos&lt;/b&gt; y la &lt;b&gt;Confidencialidad&lt;/b&gt; no son un extra, son el corazón de nuestra profesionalidad.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;&lt;b&gt;Infraestructura Segura:&lt;/b&gt; Nuestros Agentes IA operan en entornos altamente controlados. Implementamos infraestructuras dedicadas, garantizando el cumplimiento de las normativas de seguridad más estrictas (como el RGPD europeo).&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;Trazabilidad y Auditoría:&lt;/b&gt; A diferencia del trabajo manual (donde los errores pueden ser difíciles de rastrear), cada acción de un Agente IA queda registrada. Esto ofrece una &lt;b&gt;trazabilidad completa&lt;/b&gt; del proceso, facilitando cualquier auditoría interna o externa.&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;Acceso Mínimo Necesario:&lt;/b&gt; Diseñamos los Agentes IA bajo el principio de &quot;mínimo privilegio&quot;. Solo tienen acceso a los datos y sistemas estrictamente necesarios para ejecutar su tarea, blindando el resto de su ecosistema empresarial.&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                            Los mitos sobre coste y complejidad son falsos; la automatización IA es accesible y ofrece un ROI rápido. La seguridad de datos está garantizada mediante entornos controlados y auditorías constantes.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    Nuestra metodología Altira: El camino seguro hacia la eficiencia\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    La clave para superar los miedos es tener un plan claro y estructurado. Nuestra metodología, orientada a obtener ROI Rápido, se resume en cuatro fases transparentes:\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;&lt;b&gt;Consultoría y descubrimiento (El factor humano):&lt;/b&gt; Esta fase inicial, parte esencial de nuestro servicio, implica una inmersión profunda con tu equipo. Identificamos los procesos de mayor valor (los de alto riesgo/alto coste de tiempo) y recogemos los matices para asegurar que la solución se adapte 100% a tu realidad (solución a medida).&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;Diseño de solución a medida:&lt;/b&gt; Basados en el descubrimiento, diseñamos la arquitectura del Agente IA. Se define qué tecnología se usará (RPA, Machine Learning, etc.) y cómo se integrará sin fricción con sus sistemas actuales (ERP, CRM, etc.).&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;Implementación, ruebas y Go-Live:&lt;/b&gt; El Agente IA es construido, y luego sometido a rigurosas pruebas en un entorno controlado. Solo cuando la precisión está garantizada y su equipo está capacitado, el Agente pasa al entorno productivo (Go-Live).&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;Soporte y optimización continua:&lt;/b&gt; La automatización no es estática. Ofrecemos soporte continuo, monitoreamos el rendimiento del Agente IA y realizamos ajustes para optimizar aún más el proceso, asegurando que el beneficio se mantenga a largo plazo./li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                          La metodología de Altira pasa por el Descubrimiento con el equipo, el Diseño a Medida, la Implementación con rigurosas pruebas y el Soporte continuo para maximizar el éxito.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    Tu socio de confianza en la transformación\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    El miedo a lo desconocido es natural, pero no debe frenar el potencial de tu negocio. La &lt;b&gt;automatización de procesos&lt;/b&gt; con Agentes IA es la herramienta más poderosa para escalar, siempre y cuando se aborde con &lt;b&gt;profesionalidad y seguridad.&lt;/b&gt;\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    En Altira Automations, no solo implementamos tecnología; ofrecemos la garantía de que su transformación digital será &lt;b&gt;segura, confidencial&lt;/b&gt; y con un camino claro hacia un &lt;b&gt;ROI rápido&lt;/b&gt;.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    Si estás listo para dar el paso, pero quiere hacerlo con la tranquilidad de tener un socio experto a su lado, &lt;a href=&quot;/contacto&quot;&gt;contacta&lt;/a&gt; con nosotros. Hablemos de tu proceso más temido, y cómo podemos automatizarlo sin riesgo.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;', 'Preguntas frecuentes'),
(28, 4, 'en', 'ai-gents-reliability-and-the-critical-human-factor', 'AI Agents: Reliability and the critical human factor', 'Is your AI reliable for critical processes? Discover why certainty demands human oversight and the responsible Copilot Model (Human-in-the-Loop).', 'Altira\'s Copilot Model: Human-in-the-Loop intervention in AI automation to ensure 100% reliability in critical business processes.', '&lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    Professional networks are filled with demos showcasing &lt;b&gt;AI Agents&lt;/b&gt; with astonishing autonomy. The narrative of systems that reason and act entirely on their own is powerful, but the operational reality of a business demands a more relevant question: &lt;b&gt;Can we fully trust AI to manage critical business processes?&lt;/b&gt;\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    Sector experience demonstrates that an Agent\'s value is not measured by its autonomy in a lab environment, but by its &lt;b&gt;measurable reliability&lt;/b&gt; in the real business environment. To successfully implement AI, we must shift focus: move beyond the hype of total autonomy and concentrate on &lt;b&gt;safe and responsible implementation strategies.&lt;/b&gt;\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    The hidden risk of &quot;almost perfect&quot; automation\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    The most advanced AI models often achieve success rates of 80% to 85% on complex, controlled tasks. While an 8 out of 10 is considered a pass in academia, in a company\'s critical operations, a &lt;b&gt;15% to 20% error rate is an intolerable risk.&lt;/b&gt;\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    When automating billing management or inventory, a failure in one out of every five operations can generate a repair cost (rework, losses, or accounting errors) that voids any efficiency savings. The fundamental problem is not that Agents fail, but that in full autonomy, they are &lt;b&gt;not 100% deterministic.&lt;/b&gt; Critical operations demand certainty, not just high probability.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    The danger of the false negative: A case study\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    To fully grasp the risk, consider an example: automated triage of sales emails.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;&lt;b&gt;False positive:&lt;/b&gt; The AI mistakes spam for an opportunity and escalates it to a human. The cost is low (a few seconds lost).&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;False negative (catastrophic):&lt;/b&gt; The AI mistakes a high-value lead for a generic query and applies a standard automated response. The cost is a &lt;b&gt;lost business opportunity and reputational damage.&lt;/b&gt;&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    The most mature and responsible solution in the industry is to recognize that autonomous decision-making in ambiguous contexts remains risky. It is imperative to &lt;b&gt;design for failure&lt;/b&gt; and incorporate a supervision system.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                            Reliability demands more than 80% success. Catastrophic risk is in False Negatives, mitigated by human oversight.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    The responsible strategy: The copilot model and human intervention\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    Abandoning AI is not an option, but adjusting the level of autonomy to the level of process risk is. The safest model with the highest ROI today is the &lt;b&gt;Intelligent Copilot Model&lt;/b&gt;, where AI amplifies human capacity.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    This model of responsible implementation is based on the following best practices:\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;&lt;b&gt;AI as a draft generator:&lt;/b&gt; The AI does not execute the final action (e.g., sending the email or validating payment), but rather &lt;b&gt;generates the proposed action&lt;/b&gt; (the response draft, the classified data, the pre-reconciled report).&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;The human as a strategic validator:&lt;/b&gt; The professional only needs to review and validate the AI\'s proposal. The cost of verification is negligible compared to the cost of doing the work from scratch, thus maintaining efficiency gains.&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;Mandatory calibration and testing:&lt;/b&gt; In any professional implementation process, there must be a testing phase where &lt;b&gt;human intervention (Human-in-the-Loop)&lt;/b&gt; is mandatory. This fine-tuning period is crucial to ensure 100% precision before launch, minimizing operational risk.&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    The question that defines success is: Is the cost of verifying the Agent\'s work significantly less than the cost of doing the work myself? If the answer is yes, the solution is viable and offers immediate ROI.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                          The safest AI model is the Intelligent Copilot with mandatory human-in-the-loop intervention and control.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    Successful &lt;b&gt;AI Automation&lt;/b&gt; implementation requires a pragmatic vision. The real value today is not in total replacement, but in talent amplification through intelligent orchestration.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    If your organization seeks to implement &lt;b&gt;AI Agents&lt;/b&gt; in critical operations and requires the guarantee of a professional partner who prioritizes &lt;b&gt;reliability, security,&lt;/b&gt; and a clear path to &lt;b&gt;ROI&lt;/b&gt;, we can help you.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    &lt;a href=&quot;/contact&quot;&gt;Let\'s talk reliability.&lt;/a&gt; Let\'s design the perfect AI Copilot for your business together.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;', 'Frequently Asked Questions about AI Agent Reliability'),
(29, 4, 'es', 'agentes-ia-fiabilidad-y-factor-humano', 'Agentes IA: Fiabilidad y factor humano', '¿Es tu IA fiable para procesos críticos? Desmontamos el hype. Descubre por qué la fiabilidad se mide en la calidad de la intervención humana.', 'Modelo Copiloto de Altira: Intervención humana en la automatización IA para garantizar el 100% de fiabilidad en procesos críticos.', '&lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    Las redes profesionales están llenas de &lt;i&gt;demos&lt;/i&gt; que muestran Agentes IA con una autonomía sorprendente. La narrativa de sistemas que razonan y actúan por sí mismos es potente, pero la realidad operativa de una empresa exige una pregunta más relevante, &lt;b&gt;¿Podemos confiar plenamente en la IA para gestionar procesos de negocio críticos?&lt;/b&gt;\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    La experiencia en el sector demuestra que el valor de un Agente no se mide por su autonomía en un entorno de laboratorio, sino por su &lt;b&gt;fiabilidad medible&lt;/b&gt; en el entorno de la empresa. Para implementar la IA con éxito, debemos cambiar el enfoque, dejar el &lt;i&gt;hype&lt;/i&gt; de la autonomía total y centrarnos en &lt;b&gt;estrategias de implementación seguras y responsables.&lt;/b&gt;\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    El riesgo oculto del &quot;Notable&quot; en la automatización\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    Los modelos de IA más avanzados suelen alcanzar tasas de éxito del 80% al 85% en tareas complejas y controladas. Si bien un 8 sobre 10 es un notable en lo académico, en la operación crítica de una empresa, &lt;b&gt;un 15% a 20% de tasa de error es un riesgo intolerable.&lt;/b&gt;\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    Cuando se automatiza la gestión de facturación o de inventario, un fallo en una de cada cinco operaciones puede generar un coste de reparación (retrabajo, pérdidas o errores contables) que anula cualquier ahorro de eficiencia. El problema fundamental no es que los Agentes fallen, sino que en autonomía total no son &lt;b&gt;100% deterministas.&lt;/b&gt;\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    Las operaciones críticas exigen certeza, no altas probabilidades.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    El Peligro del Falso Negativo: Un Caso de Estudio\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    Para comprender el riesgo, veamos un ejemplo: triaje automático de correos comerciales.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;&lt;b&gt;Falso Positivo:&lt;/b&gt; La IA confunde &lt;i&gt;spam&lt;/i&gt; con una oportunidad y lo escala a un humano. El coste es bajo (unos segundos perdidos).&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;Falso Negativo (Catastrófico):&lt;/b&gt; La IA confunde un &lt;i&gt;lead&lt;/i&gt; de alto valor con una consulta genérica y le aplica una respuesta automatizada estándar. El coste es una &lt;b&gt;oportunidad de negocio perdida y un daño reputacional.&lt;/b&gt;&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    La solución más madura y responsable en el sector es reconocer que la toma de decisiones autónoma en contextos ambiguos sigue siendo arriesgada. Es imperativo &lt;b&gt;diseñar para el fallo&lt;/b&gt; e incorporar un sistema de supervisión.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                             La fiabilidad en procesos críticos exige más que un 80% de éxito.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_h2&quot;&gt;\r\n                &lt;h2&gt;\r\n                    La estrategia responsable: El modelo copiloto y la intervención humana\r\n                &lt;/h2&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    Abandonar la IA no es una opción, pero sí lo es &lt;b&gt;ajustar el nivel de autonomía al nivel de riesgo del proceso.&lt;/b&gt; El modelo más seguro y de mayor ROI hoy es el del &lt;b&gt;Copiloto Inteligente&lt;/b&gt;, donde la IA amplifica la capacidad humana.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    Este modelo de implementación responsable se basa en las siguientes buenas prácticas:\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_list&quot;&gt;\r\n                &lt;ul&gt;\r\n                    &lt;li&gt;&lt;b&gt;IA como generador de borradores: &lt;/b&gt; La IA no ejecuta la acción final (ej. enviar el correo o validar el pago), sino que &lt;b&gt;genera la propuesta de acción&lt;/b&gt; (el borrador de respuesta, el dato clasificado, el informe pre-conciliado).&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;El humano como validador estratégico:&lt;/b&gt; l profesional solo debe revisar y validar la propuesta de la IA. El coste de la verificación es ínfimo comparado con el coste de hacer el trabajo desde cero, manteniendo la ganancia de eficiencia.&lt;/li&gt;\r\n                    &lt;li&gt;&lt;b&gt;Calibración y prueba obligatoria:&lt;/b&gt; En todo proceso de implementación profesional, debe existir una &lt;b&gt;fase de prueba donde la intervención humana es obligatoria (Human in the loop)&lt;/b&gt;. Este periodo de calibración fina es crucial para asegurar la precisión del 100% antes de la puesta en marcha, minimizando el riesgo operativo.&lt;/li&gt;\r\n                &lt;/ul&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    La pregunta que define el éxito es: &lt;b&gt;¿Es el coste de verificar el trabajo del agente significativamente menor que el coste de hacer el trabajo yo mismo?&lt;/b&gt; Si la respuesta es afirmativa, la solución es viable y de ROI inmediato.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_highlight&quot;&gt;\r\n                &lt;div class=&quot;blog_article_highlight_container&quot;&gt;\r\n                    &lt;div class=&quot;blog_article_highlight_content&quot;&gt;\r\n                        &lt;span&gt;\r\n                          El modelo de IA más seguro es el Copiloto Inteligente con intervención humana obligatoria.\r\n                        &lt;/span&gt;\r\n                    &lt;/div&gt;\r\n                &lt;/div&gt;\r\n            &lt;/div&gt;\r\n            &lt;div class=&quot;blog_article_paragraph&quot;&gt;\r\n                &lt;p&gt;\r\n                    La implementación exitosa de la &lt;b&gt;Automatización IA&lt;/b&gt; requiere una visión pragmática. El valor real hoy no está en el reemplazo total, sino en la amplificación del talento mediante la orquestación inteligente.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                    Si tu organización busca implementar &lt;b&gt;Agentes IA&lt;/b&gt; en operaciones críticas y necesita la garantía de un socio profesional que priorice la &lt;b&gt;fiabilidad, la seguridad&lt;/b&gt; y un camino claro hacia el &lt;b&gt;ROI&lt;/b&gt;, podemos ayudarte.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n\r\n                &lt;p&gt;\r\n                    Hablemos de fiabilidad. Diseñemos juntos el Copiloto IA perfecto para tu negocio.\r\n                &lt;/p&gt;\r\n                &lt;p&gt;\r\n                   &lt;a href=&quot;/contacto&quot;&gt;contacta&lt;/a&gt; con nosotros.\r\n                &lt;/p&gt;\r\n            &lt;/div&gt;', 'Preguntas Frecuentes sobre la Fiabilidad de los Agentes IA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blog_category`
--

CREATE TABLE `blog_category` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `metadescription` longtext DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `picture_alt_text` varchar(255) DEFAULT NULL,
  `ordinal` int(11) DEFAULT NULL,
  `featured` varchar(1) NOT NULL DEFAULT '0',
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `blog_category`
--

INSERT INTO `blog_category` (`id`, `title`, `metadescription`, `slug`, `picture`, `picture_alt_text`, `ordinal`, `featured`, `active`) VALUES
(1, 'Automatizaciones', 'Descubre que es son las Automatizaciones con IA y como aplicarlas a tu negocio', 'automatizaciones-ia', 'blog_category_1_20241220111804.webp', 'Automatizaciones', 10, '0', '0'),
(2, 'Casos de éxito', 'Historias ilustrativas y ejemplarizantes de la automatización de procesos', 'casos-de-exito', 'blog_category_2_20241220111911.webp', 'Casos de éxito', 20, '0', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blog_author`
--

CREATE TABLE `blog_author` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `metadescription` longtext DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `picture` varchar(100) DEFAULT NULL,
  `picture_alt_text` varchar(255) DEFAULT NULL,
  `linkedin` varchar(200) DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `blog_author`
--

INSERT INTO `blog_author` (`id`, `name`, `metadescription`, `slug`, `picture`, `picture_alt_text`, `linkedin`, `active`) VALUES
(1, 'Miel Sandonis', 'Fondo editorial del Miel Sandonis', '/author/mielsamdonis', 'blog_author_1_20251105163922.jpg', 'Logo de Miel Sandonis', 'https://www.linkedin.com/company/miel-sandonis', '1'),
(2, 'Minerva Sandonis', 'Nuestra CEO', '/author/minerva-sandonis', 'blog_author_1_20251105163922.jpg', 'Trabajando con mis colmensas.', 'https://www.linkedin.com/in/minerva-sandonis/', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `blog_article_faq`
--

CREATE TABLE `blog_article_faq` (
  `id` int(11) NOT NULL,
  `article` int(11) DEFAULT NULL,
  `lang_code_2a` varchar(2) DEFAULT NULL,
  `question` varchar(250) DEFAULT NULL,
  `reply` longtext DEFAULT NULL,
  `ordinal` int(11) DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `blog_article_faq`
--

INSERT INTO `blog_article_faq` (`id`, `article`, `lang_code_2a`, `question`, `reply`, `ordinal`, `active`) VALUES
(76, 1, 'en', 'How quickly can I see an ROI from implementing an AI Agent?', 'The ROI for AI automation is typically rapid. In many cases, operating costs decrease as soon as the AI ​​Agents are operational, and productivity improvements are noticeable within the first few months. While it depends on the complexity of the project, our focus is on delivering tangible value quickly.', 10, '1'),
(77, 1, 'en', 'Are AI Agents safe for handling sensitive company data?', 'Absolutely. Security is paramount. Professional implementation must ensure compliance with UK data regulations, end-to-end encryption, and the principle of &quot;minimum privilege&quot; (the Agent only accesses necessary data).', 30, '1'),
(78, 1, 'en', 'Does implementing an AI Agent mean displacing human employees?', 'No. AI Agents replace tasks, not people. Their purpose is to liberate employees from repetitive tasks so they can focus on roles that require creative thinking, high-value client interaction, and strategic decision-making.', 50, '1'),
(79, 1, 'en', 'What is the difference between an AI Agent and a Chatbot?', 'A Chatbot is a communicative tool. An AI Agent is an executing tool. It can read, reason, plan, and then perform actions (like filing a document, processing an order, or drafting a report) across different systems.', 70, '1'),
(80, 1, 'en', 'What kind of processes are ideal for starting with AI Agents?', 'Ideal starting points are processes with a high volume of data input, processes with many variables (e.g., invoice processing), and initial customer service triage.', 90, '1'),
(81, 1, 'es', '¿Es la automatización de tareas solo para grandes empresas?', 'Absolutamente no. La automatización se adapta perfectamente a las Pymes. De hecho, para un negocio pequeño, liberar un par de horas al día al equipo de gerencia puede significar la diferencia entre el estancamiento y el crecimiento. Miel Sandonis ofrece soluciones modulares y asequibles.', 20, '1'),
(82, 1, 'es', '¿Reemplazará la IA a mi equipo humano?', 'La IA y la automatización no buscan reemplazar, sino potenciar. Al eliminar el trabajo repetitivo, sus empleados pueden dedicarse a tareas que requieren creatividad, empatía y pensamiento estratégico. Es una herramienta para el upskilling y la mejora del capital humano.', 40, '1'),
(83, 1, 'es', '¿Qué procesos de mi negocio debo automatizar primero?', 'Recomendamos empezar por los procesos que son: a) repetitivos y de alto volumen, b) propensos a errores, y c) requieren mucho tiempo. Nuestro equipo de Miel Sandonis puede ayudarle con una auditoría para identificar los puntos de mayor impacto.', 60, '1'),
(84, 1, 'es', '¿Qué tan seguro es automatizar procesos críticos?', 'La seguridad es uno de nuestros pilares. Al ser soluciones a medida, garantizamos que la infraestructura de automatización cumpla con los estándares más estrictos de confidencialidad y normativas de datos (como el RGPD), siendo a menudo más seguro y auditable que los procesos manuales.', 80, '1'),
(85, 1, 'es', '¿Cuánto tiempo se tarda en ver el Retorno de Inversión (ROI)?', 'El ROI en la automatización con IA suele ser rápido. En muchos casos, los costes operativos se reducen tan pronto como los Agentes IA están operativos, y la mejora de la productividad se nota en los primeros meses. Depende de la complejidad, pero nuestro enfoque es la entrega de valor tangible y rápido.', 100, '1'),
(86, 2, 'es', 'Do I need technical knowledge to implement these automations?', 'No. We always conduct a thorough audit to understand which process is your biggest bottleneck and provide a packaged, tested solution that best suits you. You do not need to do any programming.', 10, '1'),
(87, 2, 'es', 'How long does it take for an automation to go live?', 'The actual time depends on how many systems need to be connected (invoicing, CRM, social media). Our experience shows that from the setup phase to field testing, your approval, and final production, it takes less than one week.', 20, '1'),
(88, 2, 'es', 'Are these automations secure?', 'Yes. Security and confidentiality are key to us. We always use enterprise-level encryption and ensure compliance with GDPR and relevant UK data standards. Your data is never stored on external, unverified servers.', 30, '1'),
(89, 2, 'es', 'Can I automate processes if I use local (non-cloud) software?', 'Yes. Many tools allow local software to be connected via APIs or CSV file imports. If your system doesn\'t have an API, we can use solutions for automatic data import from Excel or scanned PDFs.', 40, '1'),
(90, 2, 'es', 'How much does it cost to implement these automations?', 'The cost is highly dependent on the specific solution, external connections, and integrated services. A fixed price cannot be given as every automation is unique. However, it is significantly less than hiring one full-time employee.', 50, '1'),
(96, 3, 'en', 'Does AI Automation imply staff layoffs?', 'This is the biggest myth. AI Agents do not replace people; they replace repetitive, low-value tasks. Our focus at Miel Sandonis is to free up your team to dedicate themselves to strategic, creative, and customer-facing tasks, where human value is irreplaceable.', 10, '1'),
(97, 3, 'en', 'What kind of security does Miel Sandonis offer for my company\'s confidential data?', 'At Miel Sandonis, we prioritize strict compliance with GDPR and other UK data security regulations. We implement controlled environment infrastructures, end-to-end encryption, and continuous auditing. Furthermore, the &quot;principle of least privilege&quot; ensures the AI Agent only accesses data essential for its task.', 30, '1'),
(98, 3, 'en', 'How long does it take to see the Return on Investment (ROI) of an automation?', 'If the process has been strategically selected (high time cost and high error risk), the ROI can be tangible in the first few weeks. The key is aiming for high-impact &quot;quick wins&quot; that free up resources from the outset.', 50, '1'),
(99, 3, 'en', 'My current process has many &quot;buts&quot; and exceptions; can it be automated?', 'Yes, this is precisely where the Intelligence of our AI Agents shines. Unlike traditional RPA (which only follows rigid rules), our AI Agents can learn and make decisions based on data and exceptions, making complex processes automatable.', 70, '1'),
(100, 3, 'en', 'Does my team need advanced technical knowledge to use the AI?', 'No. Miel Sandonis\'s solutions integrate into your existing systems with intuitive interfaces. Our job is to build the intelligence in the backend so your team only has to interact with the result simply and efficiently.', 90, '1'),
(101, 3, 'es', '¿La Automatización IA implica el despido de personal?', 'Es el mito más grande. Los Agentes IA no reemplazan personas; reemplazan tareas repetitivas y de bajo valor. Nuestro enfoque en Miel Sandonis es liberar a su equipo para que se dedique a tareas estratégicas, creativas y de atención al cliente, donde el valor humano es irremplazable.', 20, '1'),
(102, 3, 'es', '¿Qué tipo de seguridad ofrece Miel Sandonis para los datos confidenciales de mi empresa?', 'En Miel Sandonis, priorizamos el cumplimiento estricto del RGPD y otras normativas de seguridad de datos. Implementamos infraestructuras de entorno controlado, encriptación de extremo a extremo y auditorías continuas. Además, el principio de &quot;mínimo privilegio&quot; asegura que el Agente IA solo acceda a los datos esenciales para su tarea.', 40, '1'),
(103, 3, 'es', '¿Cuánto tiempo se tarda en ver el Retorno de Inversión (ROI) de una automatización?', 'Si el proceso ha sido seleccionado estratégicamente (alto coste de tiempo y alto riesgo de error), el ROI puede ser tangible en las primeras  semanas. La clave está en apuntar a las &quot;victorias rápidas&quot; y de alto impacto que liberan recursos desde el inicio.', 60, '1'),
(104, 3, 'es', 'Mi proceso actual tiene muchos &amp;amp;amp;quot;peros&amp;amp;amp;quot; y excepciones, ¿se puede automatizar?', 'Sí, precisamente aquí es donde brilla la &lt;b&gt;Inteligencia Artificial&lt;/b&gt; de nuestros Agentes. A diferencia del RPA tradicional (que solo sigue reglas rígidas), nuestros Agentes IA pueden aprender y tomar decisiones basadas en datos y excepciones, haciendo que los procesos complejos sean automatizables.', 80, '1'),
(105, 3, 'es', '¿Es necesario que mi equipo tenga conocimientos técnicos avanzados para usar la IA?', 'No. Las soluciones de Miel Sandonis se integran en sus sistemas actuales con interfaces intuitivas. Nuestro trabajo es construir la inteligencia en la trastienda para que su equipo solo tenga que interactuar con el resultado de forma sencilla y eficiente.', 100, '1'),
(111, 4, 'en', 'If my process involves human intervention, is it still efficient automation?', 'Yes, absolutely. The human shifts from performing tedious work (drafting from scratch) to validating and refining, which is a qualitative leap. The efficiency gain remains high (approximately 80%) while the risk is completely eliminated.', 10, '1'),
(112, 4, 'en', 'What guarantees the security of my data during implementation and testing?', 'A professional service provider must guarantee strict compliance with GDPR, operate with the principle of least privilege (the AI only accesses essential data), and provide the necessary infrastructure and traceability for any audit, ensuring confidentiality.', 30, '1'),
(113, 4, 'en', 'How long does it take to see the Return on Investment (ROI) using the Copilot model?', 'Since the risk is low and the Agent focuses on high cognitive friction tasks (drafting, classifying, extracting), the time savings are immediate, making the ROI tangible within the first few months.', 50, '1'),
(114, 4, 'en', 'My current process has many &quot;buts&quot; and exceptions; can it be automated?', 'Yes. Complex exceptions are handled by the Artificial Intelligence of the Agents, which learn from human validation during the testing period. AI-based systems can handle nuances better than traditional RPA.', 70, '1'),
(115, 4, 'en', 'Can we scale the autonomy of the AI in the future?', 'Yes. Once the solution\'s reliability is demonstrated and your team trusts the system, the process can be re-evaluated to gradually scale the Agent\'s autonomy, provided the inherent process risk allows it.', 90, '1'),
(116, 4, 'es', 'Si mi proceso tiene intervención humana, ¿sigue siendo una automatización eficiente?', 'Sí, absolutamente. El humano pasa de realizar el trabajo tedioso (redactar desde cero) a validar y refinar, lo cual es un salto cualitativo. La ganancia de eficiencia se mantiene alta (aproximadamente el 80%) mientras el riesgo se elimina por completo.', 20, '1'),
(117, 4, 'es', '¿Qué garantiza la seguridad de mis datos durante la implementación y las pruebas?', 'Un proveedor de servicios profesional debe garantizar el cumplimiento estricto del RGPD, operar con principios de mínimo privilegio (la IA solo accede a los datos esenciales) y ofrecer la infraestructura y la trazabilidad necesaria para cualquier auditoría, asegurando la confidencialidad.', 40, '1'),
(118, 4, 'es', '¿Cuánto tiempo se tarda en ver el Retorno de Inversión (ROI) usando el modelo Copiloto?', 'Dado que el riesgo es bajo y el Agente se centra en tareas de alta fricción cognitiva (redactar, clasificar, extraer), el ahorro en tiempo es inmediato, haciendo que el ROI sea tangible en los primeros meses.', 60, '1'),
(119, 4, 'es', 'Mi proceso actual tiene muchos &quot;peros&quot; y excepciones, ¿se puede automatizar?', 'Sí. Las excepciones complejas son manejadas por la Inteligencia Artificial de los Agentes, que aprenden de la validación humana durante el periodo de prueba. Los sistemas basados en IA pueden manejar mejor los matices que el RPA tradicional.', 80, '1'),
(120, 4, 'es', '¿Podemos escalar la autonomía de la IA en el futuro?', 'Sí. Una vez que la fiabilidad de la solución esté demostrada y su equipo confíe en el sistema, se puede reevaluar el proceso para escalar gradualmente la autonomía del Agente, siempre y cuando el riesgo inherente del proceso lo permita.', 100, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cron`
--

CREATE TABLE `cron` (
  `id` int(11) NOT NULL,
  `process` varchar(50) DEFAULT NULL,
  `run` tinyint(1) DEFAULT NULL,
  `periodicity` varchar(15) DEFAULT NULL COMMENT 'minute hour day webcron',
  `size` int(11) DEFAULT NULL,
  `delaytime` int(11) DEFAULT NULL,
  `ordinal` int(11) DEFAULT NULL,
  `last_run` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `cron`
--

INSERT INTO `cron` (`id`, `process`, `run`, `periodicity`, `size`, `delaytime`, `ordinal`, `last_run`) VALUES
( 1, 'mail', 1, 'webcron', 20, 10, 10, '2025-01-01 00:00:01'),
( 2, 'server', 1, 'webcron', 10, 10, 90, '2025-01-01 00:00:01'),
( 3, 'account', 0, 'day', 10, 10, 70, '2022-01-01 00:00:01'),
( 4, 'accountPaymentMethod', 0, 'day', 20, 10, 40, '2022-01-01 00:00:01'),
( 5, 'invoice', 0, 'day', 10, 10, 50, '2022-01-01 00:00:01'),
( 6, 'quote', 0, 'day', 200, 3600, 60, '2022-01-01 00:00:01'),
( 7, 'certification', 0, 'day', 200, 3600, 20, '2022-01-01 00:00:01'),
( 8, 'newsletter', 0, 'day', 20, 3600, 30, '2022-01-01 00:00:01'),
( 9, 'settlement', 0, 'day', 20, 10, 80, '2022-01-01 00:00:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `group`
--

CREATE TABLE `group` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `folder` varchar(20) DEFAULT NULL,
  `show_to_staff` varchar(1) NOT NULL DEFAULT '1' COMMENT 'With 0 only admin can see it'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `group`
--

INSERT INTO `group` (`id`, `name`, `folder`, `show_to_staff`) VALUES
(1, 'Super Admin', 'app', '0'),
(2, 'Admin', 'app', '0'),
(3, 'Staff', 'app', '0'),
(4, 'Customer', 'control_panel', '1'),
(5, 'Agent', 'control_panel', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lang`
--

CREATE TABLE `lang` (
  `id` int(11) NOT NULL,
  `code_2a` varchar(2) DEFAULT NULL,
  `code_3a` varchar(3) DEFAULT NULL,
  `family` varchar(20) DEFAULT NULL,
  `iso_name` varchar(100) DEFAULT NULL,
  `folder` varchar(10) DEFAULT NULL,
  `default` varchar(1) DEFAULT '0',
  `active` varchar(1) NOT NULL DEFAULT '0' COMMENT '0-no active 1-active 2- pre-active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `lang`
--

INSERT INTO `lang` (`id`, `code_2a`, `code_3a`, `family`, `iso_name`, `folder`, `default`, `active`) VALUES
(1, 'ab', 'abk', 'Northwest Caucasian', 'Abkhazian or Abkhaz', 'ab', '0', '0'),
(2, 'aa', 'aar', 'Afro-Asiatic', 'Afar', 'aa', '0', '0'),
(3, 'af', 'afr', 'Indo-European', 'Afrikaans', 'af', '0', '0'),
(4, 'ak', 'aka', 'Niger–Congo', 'Akan', 'ak', '0', '0'),
(5, 'sq', 'sqi', 'Indo-European', 'Albanian', 'sq', '0', '0'),
(6, 'am', 'amh', 'Afro-Asiatic', 'Amharic', 'am', '0', '0'),
(7, 'ar', 'ara', 'Afro-Asiatic', 'Arabic', 'ar', '0', '0'),
(8, 'an', 'arg', 'Indo-European', 'Aragonese', 'an', '0', '0'),
(9, 'hy', 'hye', 'Indo-European', 'Armenian', 'hy', '0', '0'),
(10, 'as', 'asm', 'Indo-European', 'Assamese', 'as', '0', '0'),
(11, 'av', 'ava', 'Northwest Caucasian', 'Avaric', 'av', '0', '0'),
(12, 'ae', 'ave', 'Indo-European', 'Avestan', 'ae', '0', '0'),
(13, 'ay', 'aym', 'Aymaran', 'Aymara', 'ay', '0', '0'),
(14, 'az', 'aze', 'Turkic', 'Azerbaijani', 'az', '0', '0'),
(15, 'bm', 'bam', 'Niger–Congo', 'Bambara', 'bm', '0', '0'),
(16, 'ba', 'bak', 'Turkic', 'Bashkir', 'ba', '0', '0'),
(17, 'eu', 'eus', 'Language isolate', 'Basque', 'eu', '0', '0'),
(18, 'be', 'bel', 'Indo-European', 'Belarusian', 'be', '0', '0'),
(19, 'bn', 'ben', 'Indo-European', 'Bengali', 'bn', '0', '0'),
(20, 'bh', 'bih', 'Indo-European', 'Bihari languages (Bhojpuri, Magahi, and Maithili)', 'bh', '0', '0'),
(21, 'bi', 'bis', 'Creole', 'Bislama', 'bi', '0', '0'),
(22, 'bs', 'bos', 'Indo-European', 'Bosnian', 'bs', '0', '0'),
(23, 'br', 'bre', 'Indo-European', 'Breton', 'br', '0', '0'),
(24, 'bg', 'bul', 'Indo-European', 'Bulgarian', 'bg', '0', '0'),
(25, 'my', 'mya', 'Sino-Tibetan', 'Burmese', 'my', '0', '0'),
(26, 'ca', 'cat', 'Indo-European', 'Catalan, Valencian', 'ca', '0', '0'),
(27, 'ch', 'cha', 'Austronesian', 'Chamorro', 'ch', '0', '0'),
(28, 'ce', 'che', 'Northeast Caucasian', 'Chechen', 'ce', '0', '0'),
(29, 'ny', 'nya', 'Niger–Congo', 'Chichewa, Chewa, Nyanja', 'ny', '0', '0'),
(30, 'zh', 'zho', 'Sino-Tibetan', 'Chinese', 'zh', '0', '0'),
(31, 'cv', 'chv', 'Turkic', 'Chuvash', 'cv', '0', '0'),
(32, 'kw', 'cor', 'Indo-European', 'Cornish', 'kw', '0', '0'),
(33, 'co', 'cos', 'Indo-European', 'Corsican', 'co', '0', '0'),
(34, 'cr', 'cre', 'Algonquian', 'Cree', 'cr', '0', '0'),
(35, 'hr', 'hrv', 'Indo-European', 'Croatian', 'hr', '0', '0'),
(36, 'cs', 'ces', 'Indo-European', 'Czech', 'cs', '0', '0'),
(37, 'da', 'dan', 'Indo-European', 'Danish', 'da', '0', '0'),
(38, 'dv', 'div', 'Indo-European', 'Divehi, Dhivehi, Maldivian', 'dv', '0', '0'),
(39, 'nl', 'nld', 'Indo-European', 'Dutch, Flemish', 'nl', '0', '0'),
(40, 'dz', 'dzo', 'Sino-Tibetan', 'Dzongkha', 'dz', '0', '0'),
(41, 'en', 'eng', 'Indo-European', 'English', 'en', '0', '1'),
(42, 'eo', 'epo', 'Constructed	Esperant', 'Esperanto', 'eo', '0', '0'),
(43, 'et', 'est', 'Uralic', 'Estonian', 'et', '0', '0'),
(44, 'ee', 'ewe', 'Niger–Congo', 'Ewe', 'ee', '0', '0'),
(45, 'fo', 'fao', 'Indo-European', 'Faroese', 'fo', '0', '0'),
(46, 'fj', 'fij', 'Austronesian', 'Fijian', 'fj', '0', '0'),
(47, 'fi', 'fin', 'Uralic', 'Finnish', 'fi', '0', '0'),
(48, 'fr', 'fra', 'Indo-European', 'French', 'fr', '0', '0'),
(49, 'ff', 'ful', 'Niger–Congo', 'Fulah or Fula', 'ff', '0', '0'),
(50, 'gl', 'glg', 'Indo-European', 'Galician', 'gl', '0', '0'),
(51, 'ka', 'kat', 'Kartvelian', 'Georgian', 'ka', '0', '0'),
(52, 'de', 'deu', 'Indo-European', 'German', 'de', '0', '0'),
(53, 'el', 'ell', 'Indo-European', 'Greek, Modern', 'el', '0', '0'),
(54, 'gn', 'grn', 'Tupian', 'Guarani', 'gn', '0', '0'),
(55, 'gu', 'guj', 'Indo-European', 'Gujarati', 'gu', '0', '0'),
(56, 'ht', 'hat', 'Creole', 'Haitian, Haitian Creole', 'ht', '0', '0'),
(57, 'ha', 'hau', 'Afro-Asiatic', 'Hausa', 'ha', '0', '0'),
(58, 'he', 'heb', 'Afro-Asiatic', 'Hebrew', 'he', '0', '0'),
(59, 'hz', 'her', 'Niger–Congo', 'Herero', 'hz', '0', '0'),
(60, 'hi', 'hin', 'Indo-European', 'Hindi', 'hi', '0', '0'),
(61, 'ho', 'hmo', 'Austronesian', 'Hiri Motu', 'ho', '0', '0'),
(62, 'hu', 'hun', 'Uralic', 'Hungarian', 'hu', '0', '0'),
(63, 'ia', 'ina', 'Constructed Interlin', 'Interlingua', 'ia', '0', '0'),
(64, 'id', 'ind', 'Austronesian', 'Indonesianu', 'id', '0', '0'),
(65, 'ie', 'ile', 'Constructed Interlin', 'Interlingue', 'ie', '0', '0'),
(66, 'ga', 'gle', 'Indo-European', 'Irish', 'ga', '0', '0'),
(67, 'ig', 'ibo', 'Niger–Congo', 'Igbo', 'ig', '0', '0'),
(68, 'ik', 'ipk', 'Eskimo–Aleut', 'Inupiaq', 'ik', '0', '0'),
(69, 'io', 'ido', 'Constructed Ido', 'Ido', 'io', '0', '0'),
(70, 'is', 'isl', 'Indo-European', 'Icelandic', 'is', '0', '0'),
(71, 'it', 'ita', 'Indo-European', 'Italian', 'it', '0', '0'),
(72, 'iu', 'iku', 'Eskimo–Aleut', 'Inuktitut', 'iu', '0', '0'),
(73, 'ja', 'jpn', 'Japonic', 'Japanese', 'ja', '0', '0'),
(74, 'jv', 'jav', 'Austronesian', 'Javanese', 'jv', '0', '0'),
(75, 'kl', 'kal', 'Eskimo–Aleut', 'Kalaallisut, Greenlandic', 'kl', '0', '0'),
(76, 'kn', 'kan', 'Dravidian', 'Kannada', 'kn', '0', '0'),
(77, 'kr', 'kau', 'Nilo-Saharan', 'Kanuri', 'kr', '0', '0'),
(78, 'ks', 'kas', 'Indo-European', 'Kashmiri', 'ks', '0', '0'),
(79, 'kk', 'kaz', 'Turkic', 'Kazakh', 'kk', '0', '0'),
(80, 'km', 'khm', 'Austroasiatic', 'Central Khme or Cambodian', 'km', '0', '0'),
(81, 'ki', 'kik', 'Niger–Congo', 'Kikuyu, Gikuyu', 'ki', '0', '0'),
(82, 'rw', 'kin', 'Niger–Congo', 'Kinyarwanda', 'rw', '0', '0'),
(83, 'ky', 'kir', 'Turkic', 'Kirghiz, Kyrgyz', 'ky', '0', '0'),
(84, 'kv', 'kom', 'Uralic', 'Komi', 'kv', '0', '0'),
(85, 'kg', 'kon', 'Niger–Congo', 'Kongo', 'kg', '0', '0'),
(86, 'ko', 'kor', 'Koreanic', 'Korean', 'ko', '0', '0'),
(87, 'ku', 'kur', 'Indo-European', 'Kurdish', 'ku', '0', '0'),
(88, 'kj', 'kua', 'Niger–Congo', 'Kuanyama, Kwanyama', 'kj', '0', '0'),
(89, 'la', 'lat', 'Indo-European', 'Latin', 'la', '0', '0'),
(90, 'lb', 'ltz', 'Indo-European', 'Luxembourgish, Letzeburgesch', 'lb', '0', '0'),
(91, 'lg', 'lug', 'Niger–Congo', 'Ganda', 'lg', '0', '0'),
(92, 'li', 'lim', 'Indo-European', 'Limburgan, Limburger, Limburgish', 'li', '0', '0'),
(93, 'ln', 'lin', 'Niger–Congo', 'Lingala', 'ln', '0', '0'),
(94, 'lo', 'lao', 'Tai–Kadai', 'Lao', 'lo', '0', '0'),
(95, 'lt', 'lit', 'Indo-European', 'Lithuanian', 'lt', '0', '0'),
(96, 'lu', 'lub', 'Niger–Congo', 'Luba-Katanga or  Luba-Shaba', 'lu', '0', '0'),
(97, 'lv', 'lav', 'Indo-European', 'Latvian', 'lv', '0', '0'),
(98, 'gv', 'glv', 'Indo-European', 'Manx', 'gv', '0', '0'),
(99, 'mk', 'mkd', 'Indo-European', 'Macedonian', 'mk', '0', '0'),
(100, 'mg', 'mlg', 'Austronesian', 'Malagasy', 'mg', '0', '0'),
(101, 'ms', 'msa', 'Austronesian', 'Malay', 'ms', '0', '0'),
(102, 'ml', 'mal', 'Dravidian', 'Malayalam', 'ml', '0', '0'),
(103, 'mt', 'mlt', 'Afro-Asiatic', 'Maltese', 'mt', '0', '0'),
(104, 'mi', 'mri', 'Austronesian', 'Maori', 'mi', '0', '0'),
(105, 'mr', 'mar', 'Indo-European', 'Marathi', 'mr', '0', '0'),
(106, 'mh', 'mah', 'Austronesian', 'Marshallese', 'mh', '0', '0'),
(107, 'mn', 'mon', 'Mongolic', 'Mongolian', 'mn', '0', '0'),
(108, 'na', 'nau', 'Austronesian', 'Nauru', 'na', '0', '0'),
(109, 'nv', 'nav', 'Dené–Yeniseian', 'Navajo, Navaho', 'nv', '0', '0'),
(110, 'nd', 'nde', 'Niger–Congo', 'North Ndebele', 'nd', '0', '0'),
(111, 'ne', 'nep', 'Indo-European', 'Nepali', 'ne', '0', '0'),
(112, 'ng', 'ndo', 'Niger–Congo', 'Ndonga', 'ng', '0', '0'),
(113, 'nb', 'nob', 'Indo-European', 'Norwegian Bokmå', 'nb', '0', '0'),
(114, 'nn', 'nno', 'Indo-European', 'Norsk Nynorsk', 'nn', '0', '0'),
(115, 'no', 'nor', 'Indo-European', 'Norwegian', 'no', '0', '0'),
(116, 'ii', 'iii', 'Sino-Tibetan', 'ichuan Yi, Nuosu', 'ii', '0', '0'),
(117, 'nr', 'nbl', 'Niger–Congo', 'South Ndebele', 'nr', '0', '0'),
(118, 'oc', 'oci', 'Indo-European', 'occitan, lenga d\'òc', 'oc', '0', '0'),
(119, 'oj', 'oji', 'Algonquian', 'Ojibwa', 'oj', '0', '0'),
(120, 'cu', 'chu', 'Indo-European', 'Church Slavic, Old Slavonic, Church Slavonic, Old Bulgarian, Old Church Slavonic', 'cu', '0', '0'),
(121, 'om', 'orm', 'Afro-Asiatic', 'Oromo', 'om', '0', '0'),
(122, 'or', 'ori', 'Indo-European', 'Oriya', 'or', '0', '0'),
(123, 'os', 'oss', 'Indo-European', 'Ossetian, Ossetic', 'os', '0', '0'),
(124, 'pa', 'pan', 'Indo-European', 'Punjabi, Panjabi', 'pa', '0', '0'),
(125, 'pi', 'pli', 'Indo-European', 'Pali', 'pi', '0', '0'),
(126, 'fa', 'fas', 'Indo-European', 'Persian', 'fa', '0', '0'),
(127, 'pl', 'pol', 'Indo-European', 'Polish', 'pl', '0', '0'),
(128, 'ps', 'pus', 'Indo-European', 'Pashto, Pushto', 'ps', '0', '0'),
(129, 'pt', 'por', 'Indo-European', 'Portuguese', 'pt', '0', '0'),
(130, 'qu', 'que', 'Quechuan', 'Quechua', 'qu', '0', '0'),
(131, 'rm', 'roh', 'Indo-European', 'Romansh', 'rm', '0', '0'),
(132, 'rn', 'run', 'Niger–Congo', 'Rundi or Kirundi', 'rn', '0', '0'),
(133, 'ro', 'ron', 'Indo-European', 'Romanian, Moldavian, Moldovan', 'ro', '0', '0'),
(134, 'ru', 'rus', 'Indo-European', 'Russian', 'ru', '0', '0'),
(135, 'sa', 'san', 'Indo-European', 'Sanskrit or Saṃskṛta', 'sa', '0', '0'),
(136, 'sc', 'srd', 'Indo-European', 'Sardinian', 'sc', '0', '0'),
(137, 'sd', 'snd', 'Indo-European', 'Sindhi', 'sd', '0', '0'),
(138, 'se', 'sme', 'Uralic', 'Northern Sami', 'se', '0', '0'),
(139, 'sm', 'smo', 'Austronesian', 'Samoan', 'sm', '0', '0'),
(140, 'sg', 'sag', 'Creole', 'Sango', 'sg', '0', '0'),
(141, 'sr', 'srp', 'Indo-European', 'Serbian', 'sr', '0', '0'),
(142, 'gd', 'gla', 'Indo-European', 'Gaelic, Scottish Gaelic', 'gd', '0', '0'),
(143, 'sn', 'sna', 'Niger–Congo', 'Shona', 'sn', '0', '0'),
(144, 'si', 'sin', 'Indo-European', 'Sinhala, Sinhalese', 'si', '0', '0'),
(145, 'sk', 'slk', 'Indo-European', 'Slovak', 'sk', '0', '0'),
(146, 'sl', 'slv', 'Indo-European', 'Slovenian or Slovene', 'sl', '0', '0'),
(147, 'so', 'som', 'Afro-Asiatic', 'Somali', 'so', '0', '0'),
(148, 'st', 'sot', 'Niger–Congo', 'Southern Sotho', 'st', '0', '0'),
(149, 'es', 'spa', 'Indo-European', 'Spanish, Castilian', 'es', '1', '1'),
(150, 'su', 'sun', 'Austronesian', 'Sundanese', 'su', '0', '0'),
(151, 'sw', 'swa', 'Niger–Congo', 'Swahili', 'sw', '0', '0'),
(152, 'ss', 'ssw', 'Niger–Congo', 'Swati or Swazi', 'ss', '0', '0'),
(153, 'sv', 'swe', 'Indo-European', 'Swedish', 'sv', '0', '0'),
(154, 'ta', 'tam', 'Dravidian', 'Tamil', 'ta', '0', '0'),
(155, 'te', 'tel', 'Dravidian', 'Telugu', 'te', '0', '0'),
(156, 'tg', 'tgk', 'Indo-European', 'Tajik', 'tg', '0', '0'),
(157, 'th', 'tha', 'Tai–Kadai', 'Thai', 'th', '0', '0'),
(158, 'ti', 'tir', 'Afro-Asiatic', 'Tigrinya', 'ti', '0', '0'),
(159, 'bo', 'bod', 'Sino-Tibetan', 'Tibetan', 'bo', '0', '0'),
(160, 'tk', 'tuk', 'Turkic', 'Turkmen', 'tk', '0', '0'),
(161, 'tl', 'tgl', 'Austronesian', 'Tagalog', 'tl', '0', '0'),
(162, 'tn', 'tsn', 'Niger–Congo', 'Tswana', 'tn', '0', '0'),
(163, 'to', 'ton', 'Austronesian', 'Tonga (Tonga Islands) or Tongan', 'to', '0', '0'),
(164, 'tr', 'tur', 'Turkic', 'Turkish', 'tr', '0', '0'),
(165, 'ts', 'tso', 'Niger–Congo', 'Tsonga', 'ts', '0', '0'),
(166, 'tt', 'tat', 'Turkic', 'Tatar', 'tt', '0', '0'),
(167, 'tw', 'twi', 'Niger–Congo', 'Twi', 'tw', '0', '0'),
(168, 'ty', 'tah', 'Austronesian', 'Tahitian', 'ty', '0', '0'),
(169, 'ug', 'uig', 'Turkic', 'Uighur, Uyghur', 'ug', '0', '0'),
(170, 'uk', 'ukr', 'Indo-European', 'Ukrainian', 'uk', '0', '0'),
(171, 'ur', 'urd', 'Indo-European', 'Urdu', 'ur', '0', '0'),
(172, 'uz', 'uzb', 'Turkic', 'Uzbek', 'uz', '0', '0'),
(173, 've', 'ven', 'Niger–Congo', 'Venda', 've', '0', '0'),
(174, 'vi', 'vie', 'Austroasiatic', 'Vietnamese', 'vi', '0', '0'),
(175, 'vo', 'vol', 'Constructed Ido', 'Volapük', 'vo', '0', '0'),
(176, 'wa', 'wln', 'Indo-European', 'Walloon', 'wa', '0', '0'),
(177, 'cy', 'cym', 'Indo-European', 'Welsh', 'cy', '0', '0'),
(178, 'wo', 'wol', 'Niger–Congo', 'Wolof', 'wo', '0', '0'),
(179, 'fy', 'fry', 'Indo-European', 'Western Frisian', 'fy', '0', '0'),
(180, 'xh', 'xho', 'Niger–Congo', 'Xhosa', 'xh', '0', '0'),
(181, 'yi', 'yid', 'Indo-European', 'Yiddish', 'yi', '0', '0'),
(182, 'yo', 'yor', 'Niger–Congo', 'Yoruba', 'yo', '0', '0'),
(183, 'za', 'zha', 'Tai–Kadai', 'Zhuang, Chuang', 'za', '0', '0'),
(184, 'zu', 'zul', 'Niger–Congo', 'Zulu', 'zu', '0', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lang_name`
--

CREATE TABLE `lang_name` (
  `id` int(11) NOT NULL,
  `lang_code_2a` varchar(2) DEFAULT NULL,
  `lang_2a` varchar(2) DEFAULT NULL,
  `name` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `lang_name`
--

INSERT INTO `lang_name` (`id`, `lang_code_2a`, `lang_2a`, `name`) VALUES
(1, 'ab', 'en', 'Abkhazian'),
(2, 'ab', 'es', 'Abjasiano'),
(3, 'ab', 'ab', 'аҧсуа бызшәа, аҧсшәа'),
(4, 'ab', 'fr', 'Abkhaze'),
(5, 'ab', 'de', 'Abchasisch'),
(6, 'aa', 'en', 'Afar'),
(7, 'aa', 'es', 'Afar'),
(8, 'aa', 'aa', 'Afaraf'),
(9, 'aa', 'fr', 'Afar'),
(10, 'aa', 'de', 'Danakil-Sprache'),
(11, 'af', 'en', 'Afrikaans'),
(12, 'af', 'es', 'Afrikaans'),
(13, 'af', 'af', 'Afrikaans'),
(14, 'af', 'fr', 'Afrikaans'),
(15, 'af', 'de', 'Afrikaans'),
(16, 'ak', 'en', 'Akan'),
(17, 'ak', 'es', 'Akano'),
(18, 'ak', 'ak', 'Akan'),
(19, 'ak', 'fr', 'Akan'),
(20, 'ak', 'de', 'Akan-Sprache'),
(21, 'sq', 'en', 'Albanian'),
(22, 'sq', 'es', 'Albanés'),
(23, 'sq', 'sq', 'Shqip'),
(24, 'sq', 'fr', 'Albanais'),
(25, 'sq', 'de', 'Albanisch'),
(26, 'am', 'en', 'Amharic'),
(27, 'am', 'es', 'Amárico'),
(28, 'am', 'am', 'አማርኛ'),
(29, 'am', 'fr', 'Amharique'),
(30, 'am', 'de', 'Amharisch'),
(31, 'ar', 'en', 'Arabic'),
(32, 'ar', 'es', 'Árabe'),
(33, 'ar', 'ar', 'العربية'),
(34, 'ar', 'fr', 'Arabe'),
(35, 'ar', 'de', 'Arabisch'),
(36, 'an', 'en', 'Aragonese'),
(37, 'an', 'es', 'Aragonés'),
(38, 'an', 'an', 'Aragonés'),
(39, 'an', 'fr', 'Aragonais'),
(40, 'an', 'de', 'Aragonesisch'),
(41, 'hy', 'en', 'Armenian'),
(42, 'hy', 'es', 'Armenio'),
(43, 'hy', 'hy', 'Հայերեն'),
(44, 'hy', 'fr', 'Arménien'),
(45, 'hy', 'de', 'Armenisch'),
(46, 'as', 'en', 'Assamese'),
(47, 'as', 'es', 'Asamés'),
(48, 'as', 'as', 'অসমীয়া'),
(49, 'as', 'fr', 'Assamais'),
(50, 'as', 'de', 'Assamesisch'),
(51, 'av', 'en', 'Avaric'),
(52, 'av', 'es', 'Avar'),
(53, 'av', 'av', 'авар мацӀ, магӀарул мацӀ'),
(54, 'av', 'fr', 'Avar'),
(55, 'av', 'de', 'Awarisch'),
(56, 'ae', 'en', 'Avestan'),
(57, 'ae', 'es', 'Avéstico'),
(58, 'ae', 'ae', 'Avesta'),
(59, 'ae', 'fr', 'Avestique'),
(60, 'ae', 'de', 'Avestisch'),
(61, 'ay', 'en', 'Aymara'),
(62, 'ay', 'es', 'Aimara'),
(63, 'ay', 'ay', 'Aymar aru'),
(64, 'ay', 'fr', 'Aymara'),
(65, 'ay', 'de', 'Aymará-Sprache'),
(66, 'az', 'en', 'Azerbaijani'),
(67, 'az', 'es', 'Azerí'),
(68, 'az', 'az', 'Azərbaycan dili'),
(69, 'az', 'fr', 'Azéri'),
(70, 'az', 'de', 'Aserbeidschanisch'),
(71, 'bm', 'en', 'Bambara'),
(72, 'bm', 'es', 'Bambara'),
(73, 'bm', 'bm', 'bamanankan'),
(74, 'bm', 'fr', 'Bambara'),
(75, 'bm', 'de', 'Bambara-Sprache'),
(76, 'ba', 'en', 'Bashkir'),
(77, 'ba', 'es', 'Baskir'),
(78, 'ba', 'ba', 'башҡорт теле'),
(79, 'ba', 'fr', 'Bachkir'),
(80, 'ba', 'de', 'Baschkirisch'),
(81, 'eu', 'en', 'Basque'),
(82, 'eu', 'es', 'Euskera'),
(83, 'eu', 'eu', 'euskara, euskera'),
(84, 'eu', 'de', 'Basque'),
(85, 'eu', 'fr', 'Baskisch'),
(86, 'be', 'en', 'Belarusian'),
(87, 'be', 'es', 'Bielorruso'),
(88, 'be', 'be', 'беларуская мова'),
(89, 'be', 'fr', 'Biélorusse'),
(90, 'be', 'de', 'Weißrussisch'),
(91, 'bn', 'en', 'Bengali or Bangla'),
(92, 'bn', 'es', 'Bengalí'),
(93, 'bn', 'bn', 'বাংলা  Bangla'),
(94, 'bn', 'fr', 'Bengali'),
(95, 'bn', 'de', 'Bengali'),
(96, 'bh', 'en', 'Bihari (Bhojpuri, Magahi and Maithili)'),
(97, 'bh', 'es', 'Bihari (Bhojpuri, Magahi y Maithili)'),
(98, 'bh', 'bh', 'भोजपुरी'),
(99, 'bh', 'fr', 'Langues Biharis'),
(100, 'bh', 'de', 'Bihari (Andere)'),
(101, 'bi', 'en', 'Bislama'),
(102, 'bi', 'es', 'Bislama'),
(103, 'bi', 'bi', 'Bislama'),
(104, 'bi', 'fr', 'Bichlamar'),
(105, 'bi', 'de', 'Beach-la-mar'),
(106, 'bs', 'en', 'Bosnian'),
(107, 'bs', 'es', 'Bosnio'),
(108, 'bs', 'bs', 'bosanski jezik'),
(109, 'bs', 'fr', 'Bosniaque'),
(110, 'bs', 'de', 'Bosnisch'),
(111, 'br', 'en', 'Breton'),
(112, 'br', 'es', 'Bretón'),
(113, 'br', 'br', 'brezhoneg'),
(114, 'br', 'fr', 'Breton'),
(115, 'br', 'de', 'Bretonisch'),
(116, 'bg', 'en', 'Bulgarian'),
(117, 'bg', 'es', 'Búlgaro'),
(118, 'bg', 'bg', 'български език'),
(119, 'bg', 'fr', 'Bulgare'),
(120, 'bg', 'de', 'Bulgarisch'),
(121, 'my', 'en', 'Burmese'),
(122, 'my', 'es', 'Birmano'),
(123, 'my', 'my', 'ဗမာစာ'),
(124, 'my', 'fr', 'Birman'),
(125, 'my', 'de', 'Birmanisch'),
(126, 'ca', 'en', 'Catalan'),
(127, 'ca', 'es', 'Catalán'),
(128, 'ca', 'ca', 'català, valencià'),
(129, 'ca', 'fr', 'Catalan Valencien'),
(130, 'ca', 'de', 'Katalanisch'),
(131, 'ch', 'en', 'Chamorro'),
(132, 'ch', 'es', 'Chamorro'),
(133, 'ch', 'ch', 'Chamoru'),
(134, 'ch', 'fr', 'Chamorro'),
(135, 'ch', 'de', 'Chamorro-Sprache'),
(136, 'ce', 'en', 'Chechen'),
(137, 'ce', 'es', 'Checheno'),
(138, 'ce', 'ce', 'нохчийн мотт'),
(139, 'ce', 'fr', 'Tchétchène'),
(140, 'ce', 'de', 'Tschetschenisch'),
(141, 'ny', 'en', 'Chichewa, Chewa, Nyanja'),
(142, 'ny', 'es', 'Chichewa, Chewa, Nyanja'),
(143, 'ny', 'ny', 'chiCheŵa'),
(144, 'ny', 'fr', 'Chichewa Chewa Nyanja'),
(145, 'ny', 'de', 'Nyanja-Sprache'),
(146, 'zh', 'en', 'Chinese'),
(147, 'zh', 'es', 'Chino'),
(148, 'zh', 'zh', '中文 (Zhōngwén), 汉语, 漢語'),
(149, 'zh', 'fr', 'Chinois'),
(150, 'zh', 'de', 'Chinesisch'),
(151, 'cv', 'en', 'Chuvash'),
(152, 'cv', 'es', 'Chuvasio'),
(153, 'cv', 'cv', 'чӑваш чӗлхи'),
(154, 'cv', 'fr', 'Tchouvache'),
(155, 'cv', 'de', 'Tschuwaschisch'),
(156, 'kw', 'en', 'Cornish'),
(157, 'kw', 'es', 'Córnico'),
(158, 'kw', 'kw', 'Kernewek'),
(159, 'kw', 'fr', 'Cornique'),
(160, 'kw', 'de', 'Kornisch'),
(161, 'co', 'en', 'Corsican'),
(162, 'co', 'es', 'Corso'),
(163, 'co', 'co', 'corsu, lingua corsa'),
(164, 'co', 'fr', 'Corse'),
(165, 'co', 'de', 'Korsisch'),
(166, 'cr', 'en', 'Cree'),
(167, 'cr', 'es', 'Cree'),
(168, 'cr', 'cr', 'ᓀᐦᐃᔭᐍᐏᐣ'),
(169, 'cr', 'fr', 'Cree'),
(170, 'cr', 'de', 'Cree-Sprache'),
(171, 'hr', 'en', 'Croatian'),
(172, 'hr', 'es', 'Croata'),
(173, 'hr', 'hr', 'hrvatski jezik'),
(174, 'hr', 'fr', 'Croate'),
(175, 'hr', 'de', 'Kroatisch'),
(176, 'cs', 'en', 'Czech'),
(177, 'cs', 'es', 'Checo'),
(178, 'cs', 'cs', 'čeština, český jazyk'),
(179, 'cs', 'fr', 'Tchèque'),
(180, 'cs', 'de', 'Tschechisch'),
(181, 'da', 'en', 'Danish'),
(182, 'da', 'es', 'Danés'),
(183, 'da', 'da', 'dansk'),
(184, 'da', 'fr', 'Danois'),
(185, 'da', 'de', 'Dänisch'),
(186, 'dv', 'en', 'Maldivian, Divehi, Dhivehi'),
(187, 'dv', 'es', 'Maldivo'),
(188, 'dv', 'dv', 'ދިވެހި'),
(189, 'dv', 'fr', 'Maldivien'),
(190, 'dv', 'de', 'Maledivisch'),
(191, 'nl', 'en', 'Dutch, Flemish'),
(192, 'nl', 'es', 'Neerlandés (Holandés)'),
(193, 'nl', 'nl', 'Nederlands, Vlaams'),
(194, 'nl', 'fr', 'Néerlandais Flamand'),
(195, 'nl', 'de', 'Niederländisch'),
(196, 'dz', 'en', 'Dzongkha'),
(197, 'dz', 'es', 'Dzongkha'),
(198, 'dz', 'dz', 'རྫོང་ཁ'),
(199, 'dz', 'fr', 'Dzongkha'),
(200, 'dz', 'de', 'Dzongkha'),
(201, 'en', 'en', 'English'),
(202, 'en', 'es', 'Inglés'),
(203, 'en', 'fr', 'Anglais'),
(204, 'en', 'de', 'Englisch'),
(205, 'eo', 'en', 'Esperanto'),
(206, 'eo', 'es', 'Esperanto'),
(207, 'eo', 'eo', 'Esperanto'),
(208, 'eo', 'fr', 'Espéranto'),
(209, 'eo', 'de', 'Esperanto'),
(210, 'et', 'en', 'Estonian'),
(211, 'et', 'es', 'Estonio'),
(212, 'et', 'et', 'Eesti, eesti keel'),
(213, 'et', 'fr', 'Estonien'),
(214, 'et', 'de', 'Estnisch'),
(215, 'ee', 'en', 'Ewe'),
(216, 'ee', 'es', 'Ewe'),
(217, 'ee', 'ee', 'Eʋegbe'),
(218, 'ee', 'fr', 'Éwé'),
(219, 'ee', 'de', 'Ewe-Sprache'),
(220, 'fo', 'en', 'Faroese'),
(221, 'fo', 'es', 'Feroés'),
(222, 'fo', 'fo', 'Føroyskt'),
(223, 'fo', 'fr', 'Féroïen'),
(224, 'fo', 'de', 'Färöisch'),
(225, 'fj', 'en', 'Fijian'),
(226, 'fj', 'es', 'Fiyiano'),
(227, 'fj', 'fj', 'Vosa Vakaviti'),
(228, 'fj', 'fr', 'Fidjien'),
(229, 'fj', 'de', 'Fidschi-Sprache'),
(230, 'fi', 'en', 'Finnish'),
(231, 'fi', 'es', 'Finlandés'),
(232, 'fi', 'fi', 'Suomi, Suomen kieli'),
(233, 'fi', 'fr', 'Finnois'),
(234, 'fi', 'de', 'Finnisch'),
(235, 'fr', 'en', 'French'),
(236, 'fr', 'es', 'Francés'),
(237, 'fr', 'fr', 'français, langue française'),
(238, 'fr', 'de', 'Französisch'),
(239, 'ff', 'en', 'Fulah'),
(240, 'ff', 'es', 'Fula'),
(241, 'ff', 'ff', 'Fulfulde, Pulaar, Pular'),
(242, 'ff', 'fr', 'Peul'),
(243, 'ff', 'de', 'Ful'),
(244, 'gl', 'en', 'Galician'),
(245, 'gl', 'es', 'Gallego'),
(246, 'gl', 'gl', 'Galego'),
(247, 'gl', 'fr', 'Galicien'),
(248, 'gl', 'de', 'Galicisch'),
(249, 'ka', 'en', 'Georgian'),
(250, 'ka', 'es', 'Georgiano'),
(251, 'ka', 'ka', 'ქართული'),
(252, 'ka', 'fr', 'Géorgien'),
(253, 'ka', 'de', 'Georgisch'),
(254, 'de', 'en', 'German'),
(255, 'de', 'es', 'Alemán'),
(256, 'de', 'de', 'Deutsch'),
(257, 'de', 'fr', 'Allemand'),
(258, 'el', 'en', 'Greek (modern)'),
(259, 'el', 'es', 'Griego (moderno)'),
(260, 'el', 'el', 'ελληνικά'),
(261, 'el', 'fr', 'Grec moderne'),
(262, 'el', 'de', 'Neugriechisch'),
(263, 'gn', 'en', 'Guaraní'),
(264, 'gn', 'es', 'Guaraní'),
(265, 'gn', 'gn', 'Avañe\'ẽ'),
(266, 'gn', 'fr', 'Guarani'),
(267, 'gn', 'de', 'Guaraní-Sprache'),
(268, 'gu', 'en', 'Gujarati'),
(269, 'gu', 'es', 'Guyaratí (Guyaratí)'),
(270, 'gu', 'gu', 'ગુજરાતી'),
(271, 'gu', 'fr', 'Goudjrati'),
(272, 'gu', 'de', 'Gujarati-Sprache'),
(273, 'ht', 'en', 'Haitian, Haitian Creole'),
(274, 'ht', 'es', 'Haitiano'),
(275, 'ht', 'ht', 'Kreyòl ayisyen'),
(276, 'ht', 'fr', 'Haïtien Créole haïtien'),
(277, 'ht', 'de', 'Haïtien (Haiti-Kreolisch)'),
(278, 'ha', 'en', 'Hausa'),
(279, 'ha', 'es', 'Hausa'),
(280, 'ha', 'ha', '(Hausa) هَوُسَ'),
(281, 'ha', 'fr', 'Haoussa'),
(282, 'ha', 'de', 'Haussa-Sprache'),
(283, 'he', 'en', 'Hebrew'),
(284, 'he', 'es', 'Hebreo'),
(285, 'he', 'he', 'עברית'),
(286, 'he', 'fr', 'Hébreu'),
(287, 'he', 'de', 'Hebräisch'),
(288, 'hz', 'en', 'Herero'),
(289, 'hz', 'es', 'Herero'),
(290, 'hz', 'hz', 'Otjiherero'),
(291, 'hz', 'fr', 'Herero'),
(292, 'hz', 'de', 'Herero-Sprache'),
(293, 'hi', 'en', 'Hindi'),
(294, 'hi', 'es', 'Hindi (Hindú)'),
(295, 'hi', 'hi', 'हिन्दी, हिंदी'),
(296, 'hi', 'fr', 'Hindi'),
(297, 'hi', 'de', 'Hindi'),
(298, 'ho', 'en', 'Hiri Motu'),
(299, 'ho', 'es', 'Hiri motu'),
(300, 'ho', 'ho', 'Hiri Motu'),
(301, 'ho', 'fr', 'Hiri Motu'),
(302, 'ho', 'de', 'Hiri Motu'),
(303, 'hu', 'en', 'Hungarian'),
(304, 'hu', 'es', 'Húngaro'),
(305, 'hu', 'hu', 'magyar'),
(306, 'hu', 'fr', 'Hongrois'),
(307, 'hu', 'de', 'Ungarisch'),
(308, 'ia', 'en', 'Interlingua'),
(309, 'ia', 'es', 'Interlingua'),
(310, 'ia', 'ia', 'Interlingua'),
(311, 'ia', 'fr', 'Interlingua'),
(312, 'ia', 'de', 'Interlingua'),
(313, 'id', 'en', 'Indonesian'),
(314, 'id', 'es', 'Indonesio'),
(315, 'id', 'id', 'Bahasa Indonesia'),
(316, 'id', 'fr', 'Indonésien'),
(317, 'id', 'de', 'Bahasa Indonesia'),
(318, 'ie', 'en', 'Interlingue'),
(319, 'ie', 'es', 'Interlengua'),
(320, 'ie', 'ie', 'Interlingue'),
(321, 'ie', 'fr', 'Interlingue'),
(322, 'ie', 'de', 'Interlingue'),
(323, 'ga', 'en', 'Irish'),
(324, 'ga', 'es', 'Irlandés (Gaélico)'),
(325, 'ga', 'ga', 'Gaeilge'),
(326, 'ga', 'fr', 'Irlandais'),
(327, 'ga', 'de', '	Irisch'),
(328, 'ig', 'en', 'Igbo'),
(329, 'ig', 'es', 'Igbo'),
(330, 'ig', 'ig', 'Asụsụ Igbo'),
(331, 'ig', 'fr', 'Igbo'),
(332, 'ig', 'de', '	Ibo-Sprache'),
(333, 'ik', 'en', 'Inupiaq'),
(334, 'ik', 'es', 'Inupiaq'),
(335, 'ik', 'ik', 'Iñupiaq, Iñupiatun'),
(336, 'ik', 'fr', 'Inupiaq'),
(337, 'ik', 'de', 'Inupik'),
(338, 'io', 'en', 'Ido'),
(339, 'io', 'es', 'Ido'),
(340, 'io', 'io', 'Ido'),
(341, 'io', 'fr', 'Ido'),
(342, 'io', 'de', 'Ido'),
(343, 'is', 'en', 'Icelandic'),
(344, 'is', 'es', 'Islandés'),
(345, 'is', 'is', 'Íslenska'),
(346, 'is', 'fr', 'Islandais'),
(347, 'is', 'de', 'Isländisch'),
(348, 'it', 'en', 'Italian'),
(349, 'it', 'es', 'Italiano'),
(350, 'it', 'it', 'Italiano'),
(351, 'it', 'fr', 'Italien'),
(352, 'it', 'de', 'Italienisch'),
(353, 'iu', 'en', 'Inuktitut'),
(354, 'iu', 'es', 'Inuktitut'),
(355, 'iu', 'iu', 'ᐃᓄᒃᑎᑐᑦ'),
(356, 'iu', 'fr', 'Inuktitut'),
(357, 'iu', 'de', 'Inuktitut'),
(358, 'ja', 'en', 'Japanese'),
(359, 'ja', 'es', 'Japonés'),
(360, 'ja', 'ja', '日本語 (にほんご)'),
(361, 'ja', 'fr', 'Japonais'),
(362, 'ja', 'de', 'Japanisch'),
(363, 'jv', 'en', 'Javanese'),
(364, 'jv', 'es', 'Javanés'),
(365, 'jv', 'jv', 'ꦧꦱꦗꦮ, Basa Jawa'),
(366, 'jv', 'fr', 'Javanais'),
(367, 'jv', 'de', 'Javanisch'),
(368, 'kl', 'en', 'Kalaallisut, Greenlandic'),
(369, 'kl', 'es', 'Groenlandés (Kalaallisut)'),
(370, 'kl', 'kl', 'kalaallisut, kalaallit oqaasii'),
(371, 'kl', 'fr', 'Groenlandais'),
(372, 'kl', 'de', 'Grönländisch'),
(373, 'kn', 'en', 'Kannada'),
(374, 'kn', 'es', 'Canarés'),
(375, 'kn', 'kn', 'ಕನ್ನಡ'),
(376, 'kn', 'fr', 'Kannada'),
(377, 'kn', 'de', 'Kannada'),
(378, 'kr', 'en', 'Kanuri'),
(379, 'kr', 'es', 'Kanuri'),
(380, 'kr', 'kr', 'Kanuri'),
(381, 'kr', 'fr', 'Kanouri'),
(382, 'kr', 'de', 'Kanuri-Sprache'),
(383, 'ks', 'en', 'Kashmiri'),
(384, 'ks', 'es', 'Cachemiro'),
(385, 'ks', 'ks', 'कश्मीरी, كشميري‎'),
(386, 'ks', 'fr', 'Kashmiri'),
(387, 'ks', 'de', 'Kaschmiri'),
(388, 'kk', 'en', 'Kazakh'),
(389, 'kk', 'es', 'Kazajo (Kazajio)'),
(390, 'kk', 'kk', 'қазақ тілі'),
(391, 'kk', 'fr', 'Kazakh'),
(392, 'kk', 'de', 'Kasachisch'),
(393, 'km', 'en', 'Central Khmer'),
(394, 'km', 'es', 'Camboyano (Jemer)'),
(395, 'km', 'km', 'ខ្មែរ, ខេមរភាសា, ភាសាខ្មែរ'),
(396, 'km', 'fr', 'Jhmer central'),
(397, 'km', 'de', 'Kambodschanisch'),
(398, 'ki', 'en', 'Kikuyu, Gikuyu'),
(399, 'ki', 'es', 'Kikuyu, Gikuyu'),
(400, 'ki', 'ki', 'Gĩkũyũ'),
(401, 'ki', 'fr', 'Kikuyu'),
(402, 'ki', 'de', 'Kikuyu-Sprache'),
(403, 'rw', 'en', 'Kinyarwanda'),
(404, 'rw', 'es', 'Ruandés'),
(405, 'rw', 'rw', 'Ikinyarwanda'),
(406, 'rw', 'fr', 'Rwanda'),
(407, 'rw', 'de', 'Rwanda-Sprache'),
(408, 'ky', 'en', 'Kirghiz, Kyrgyz'),
(409, 'ky', 'es', 'Kirguís'),
(410, 'ky', 'ky', 'Кыргызча, Кыргыз тили'),
(411, 'ky', 'fr', 'Kirghiz'),
(412, 'ky', 'de', 'Kirgisisch'),
(413, 'kv', 'en', 'Komi'),
(414, 'kv', 'es', 'Komi'),
(415, 'kv', 'kv', 'коми кыв'),
(416, 'kv', 'fr', 'Kom'),
(417, 'kv', 'de', 'Komi-Sprache'),
(418, 'kg', 'en', 'Kongo'),
(419, 'kg', 'es', 'Kongo'),
(420, 'kg', 'kg', 'Kikongo'),
(421, 'kg', 'fr', 'Kongo'),
(422, 'kg', 'de', 'Kongo-Sprache'),
(423, 'ko', 'en', 'Korean'),
(424, 'ko', 'es', 'Coreano'),
(425, 'ko', 'ko', '한국어'),
(426, 'ko', 'fr', 'Coréen'),
(427, 'ko', 'de', 'Koreanisch'),
(428, 'ku', 'en', 'Kurdish'),
(429, 'ku', 'es', 'Kurdo'),
(430, 'ku', 'ku', 'Kurdî, کوردی‎'),
(431, 'ku', 'fr', 'Kurde'),
(432, 'ku', 'de', 'Kurdisch'),
(433, 'kj', 'en', 'Kuanyama, Kwanyama'),
(434, 'kj', 'es', 'Kuanyama'),
(435, 'kj', 'kj', 'Kuanyama'),
(436, 'kj', 'fr', 'Kuanyama, kwanyama'),
(437, 'kj', 'de', 'Kwanyama-Sprache'),
(438, 'la', 'en', 'Latin'),
(439, 'la', 'es', 'Latín'),
(440, 'la', 'la', 'Latine, Lingua latina'),
(441, 'la', 'fr', 'Latin'),
(442, 'la', 'de', 'Latein'),
(443, 'lb', 'en', 'Luxembourgish, Letzeburgesch'),
(444, 'lb', 'es', 'Luxemburgués'),
(445, 'lb', 'lb', 'Lëtzebuergesch'),
(446, 'lb', 'fr', 'Luxembourgeois'),
(447, 'lb', 'de', 'Luxemburgisch'),
(448, 'lg', 'en', 'Ganda'),
(449, 'lg', 'es', 'Luganda'),
(450, 'lg', 'lg', 'Luganda'),
(451, 'lg', 'fr', 'Ganda'),
(452, 'lg', 'de', 'Ganda-Sprache'),
(453, 'li', 'en', 'Limburgan, Limburger, Limburgish'),
(454, 'li', 'es', 'Limburgués'),
(455, 'li', 'li', 'Limburgs'),
(456, 'li', 'fr', 'Limbourgeois'),
(457, 'li', 'de', 'Limburgisch'),
(458, 'ln', 'en', 'Lingala'),
(459, 'ln', 'es', 'Lingala'),
(460, 'ln', 'ln', 'Lingála'),
(461, 'ln', 'fr', 'Lingala'),
(462, 'ln', 'de', 'Lingala'),
(463, 'lo', 'en', 'Lao'),
(464, 'lo', 'es', 'Lao'),
(465, 'lo', 'lo', 'ພາສາລາວ'),
(466, 'lo', 'fr', 'Lao'),
(467, 'lo', 'de', 'Laotisch'),
(468, 'lt', 'en', 'Lithuanian'),
(469, 'lt', 'es', 'Lituano'),
(470, 'lt', 'lt', 'Lietuvių kalba'),
(471, 'lt', 'fr', 'Lituanien'),
(472, 'lt', 'de', 'Litauisch'),
(473, 'lu', 'en', 'Luba-Katanga'),
(474, 'lu', 'es', 'Luba-Katanga'),
(475, 'lu', 'lu', 'Kiluba'),
(476, 'lu', 'fr', 'Luba-Katanga'),
(477, 'lu', 'de', 'Luba-Katanga-Sprache'),
(478, 'lv', 'en', 'Latvian'),
(479, 'lv', 'es', 'Letón'),
(480, 'lv', 'lv', 'latviešu valoda'),
(481, 'lv', 'fr', 'Letton'),
(482, 'lv', 'de', 'Lettisch'),
(483, 'gv', 'en', 'Manx'),
(484, 'gv', 'es', 'Manés (gaélico manés o de Isla de Man)'),
(485, 'gv', 'gv', 'Gaelg, Gailck'),
(486, 'gv', 'fr', 'Manx Mannois'),
(487, 'gv', 'de', 'Manx'),
(488, 'mk', 'en', 'Macedonian'),
(489, 'mk', 'es', 'Macedonio'),
(490, 'mk', 'mk', 'македонски јазик'),
(491, 'mk', 'fr', 'Macédonien'),
(492, 'mk', 'de', 'Makedonisch'),
(493, 'mg', 'en', 'Malagasy'),
(494, 'mg', 'es', 'Malgache (Malagasy)'),
(495, 'mg', 'mg', 'Fiteny malagasy'),
(496, 'mg', 'fr', 'Malgache'),
(497, 'mg', 'de', 'Malagassi-Sprache'),
(498, 'ms', 'en', 'Malay'),
(499, 'ms', 'es', 'Malayo'),
(500, 'ms', 'ms', 'Bahasa Melayu, بهاس ملايو‎'),
(501, 'ms', 'fr', 'Malais'),
(502, 'ms', 'de', 'Malaiisch'),
(503, 'ml', 'en', 'Malayalam'),
(504, 'ml', 'es', 'Malayalam'),
(505, 'ml', 'ml', 'മലയാളം'),
(506, 'ml', 'fr', 'Malayalam'),
(507, 'ml', 'de', 'Malayalam'),
(508, 'mt', 'en', 'Maltese'),
(509, 'mt', 'es', 'Maltés'),
(510, 'mt', 'mt', 'Malti'),
(511, 'mt', 'fr', 'Maltais'),
(512, 'mt', 'de', 'Maltesisch'),
(513, 'mi', 'en', 'Maori'),
(514, 'mi', 'es', 'Maorí'),
(515, 'mi', 'mi', 'Te reo Māori'),
(516, 'mi', 'fr', 'Maori'),
(517, 'mi', 'de', 'Maori-Sprache'),
(518, 'mr', 'en', 'Marathi'),
(519, 'mr', 'es', 'Maratí'),
(520, 'mr', 'mr', 'मराठी'),
(521, 'mr', 'fr', 'Marathe'),
(522, 'mr', 'de', 'Marathi'),
(523, 'mh', 'en', 'Marshallese'),
(524, 'mh', 'es', 'Marshalés'),
(525, 'mh', 'mh', 'Kajin M̧ajeļ'),
(526, 'mh', 'fr', 'Marshall'),
(527, 'mh', 'de', 'Marschallesisch'),
(528, 'mn', 'en', 'Mongolian'),
(529, 'mn', 'es', 'Mongol'),
(530, 'mn', 'mn', 'Монгол хэл'),
(531, 'mn', 'fr', 'Mongol'),
(532, 'mn', 'de', 'Mongolisch'),
(533, 'na', 'en', 'Nauru'),
(534, 'na', 'es', 'Nauruano'),
(535, 'na', 'na', 'Dorerin Naoero'),
(536, 'na', 'fr', 'Nauruan'),
(537, 'na', 'de', 'Nauruanisch'),
(538, 'nv', 'en', 'Navajo, Navaho'),
(539, 'nv', 'es', 'Navajo'),
(540, 'nv', 'nv', 'Diné bizaad'),
(541, 'nv', 'fr', 'Chichewa; Chewa; Nyanja'),
(542, 'nv', 'de', 'Nyanja-Sprache'),
(543, 'nd', 'en', 'North Ndebele'),
(544, 'nd', 'es', 'Ndebele del norte'),
(545, 'nd', 'nd', 'isiNdebele'),
(546, 'nd', 'fr', 'Ndébélé du Sud'),
(547, 'nd', 'de', 'Ndebele-Sprache (Transvaal)'),
(548, 'ne', 'en', 'Nepali'),
(549, 'ne', 'es', 'Nepalí'),
(550, 'ne', 'ne', 'नेपाली'),
(551, 'ne', 'fr', 'Népalais'),
(552, 'ne', 'de', 'Nepali'),
(553, 'ng', 'en', 'Ndonga'),
(554, 'ng', 'es', 'Ndonga'),
(555, 'ng', 'ng', 'Owambo'),
(556, 'ng', 'fr', 'Ndonga'),
(557, 'ng', 'de', 'Ndonga'),
(558, 'nb', 'en', 'Norwegian Bokmål'),
(559, 'nb', 'es', 'Noruego Bokmål'),
(560, 'nb', 'nb', 'Norsk Bokmål'),
(561, 'nb', 'fr', 'Norvégien Bokmål'),
(562, 'nb', 'de', 'Bokmål'),
(563, 'nn', 'en', 'Norwegian Nynorsk'),
(564, 'nn', 'es', 'Nynorsk'),
(565, 'nn', 'nn', 'Norsk Nynorsk'),
(566, 'nn', 'fr', 'Norvégien Nynorsk'),
(567, 'nn', 'de', 'Nynorsk'),
(568, 'no', 'en', 'Norwegian'),
(569, 'no', 'es', 'Noruego'),
(570, 'no', 'no', 'Norsk'),
(571, 'no', 'fr', 'Norvégien'),
(572, 'no', 'de', 'Norwegisch'),
(573, 'ii', 'en', 'Sichuan Yi, Nuosu'),
(574, 'ii', 'es', 'Yi de Sichuán'),
(575, 'ii', 'ii', 'ꆈꌠ꒿ Nuosuhxop'),
(576, 'ii', 'fr', 'Yi de Sichuán'),
(577, 'ii', 'de', 'Lalo-Sprache'),
(578, 'nr', 'en', 'South Ndebele'),
(579, 'nr', 'es', 'Ndebele del sur'),
(580, 'nr', 'nr', 'isiNdebele'),
(581, 'nr', 'fr', 'Ndébélé du Sud'),
(582, 'nr', 'de', 'Ndebele-Sprache (Transvaal)'),
(583, 'oc', 'en', 'Occitan'),
(584, 'oc', 'es', 'Occitano'),
(585, 'oc', 'oc', 'occitan, lenga d\'òc'),
(586, 'oc', 'fr', 'Occitan'),
(587, 'oc', 'de', 'Okzitanisch'),
(588, 'oj', 'en', 'Ojibwa'),
(589, 'oj', 'es', 'Ojibwa'),
(590, 'oj', 'oj', 'ᐊᓂᔑᓈᐯᒧᐎᓐ'),
(591, 'oj', 'fr', 'Ojibwa'),
(592, 'oj', 'de', 'Ojibwa-Sprache'),
(593, 'cu', 'en', 'Old Church Slavonic'),
(594, 'cu', 'es', 'Eslavo eclesiástico antiguo'),
(595, 'cu', 'cu', 'ѩзыкъ словѣньскъ'),
(596, 'cu', 'fr', 'Slavon d\'église; Vieux slave; Slavon liturgique; Vieux bulgare'),
(597, 'cu', 'de', 'Kirchenslawisch'),
(598, 'om', 'en', 'Oromo'),
(599, 'om', 'es', 'Oromo'),
(600, 'om', 'om', 'Afaan Oromoo'),
(601, 'om', 'fr', 'Galla'),
(602, 'om', 'de', 'Galla-Sprache'),
(603, 'or', 'en', 'Oriya'),
(604, 'or', 'es', 'Oriya'),
(605, 'or', 'or', 'ଓଡ଼ିଆ'),
(606, 'or', 'fr', 'Oriya'),
(607, 'or', 'de', 'Oriya-Sprach'),
(608, 'os', 'en', 'Ossetian, Ossetic'),
(609, 'os', 'es', 'Osético'),
(610, 'os', 'os', 'ирон æвзаг'),
(611, 'os', 'fr', 'Ossète'),
(612, 'os', 'de', 'Ossetisch'),
(613, 'pa', 'en', 'Panjabi, Punjabi'),
(614, 'pa', 'es', 'Panyabí (Penyabi)'),
(615, 'pa', 'pa', 'ਪੰਜਾਬੀ, پنجابی‎'),
(616, 'pa', 'fr', 'Pendjabi'),
(617, 'pa', 'de', 'Pandschabi-Sprache‎'),
(618, 'pi', 'en', 'Pali'),
(619, 'pi', 'es', 'Pali'),
(620, 'pi', 'pi', 'पालि, पाळि'),
(621, 'pi', 'fr', 'Pali'),
(622, 'pi', 'de', 'Pali'),
(623, 'fa', 'en', 'Persian'),
(624, 'fa', 'es', 'Persa'),
(625, 'fa', 'fa', 'فارسی'),
(626, 'fa', 'fr', 'Persan'),
(627, 'fa', 'de', 'Persisch'),
(628, 'pl', 'en', 'Polish'),
(629, 'pl', 'es', 'Polaco'),
(630, 'pl', 'pl', 'język polski, polszczyzna'),
(631, 'pl', 'fr', 'Polonais'),
(632, 'pl', 'de', 'Polnisch'),
(633, 'ps', 'en', 'Pashto, Pushto'),
(634, 'ps', 'es', 'Pastú (Pashto)'),
(635, 'ps', 'ps', 'پښتو'),
(636, 'ps', 'fr', 'Pachto'),
(637, 'ps', 'de', 'Paschtu'),
(638, 'pt', 'en', 'Portuguese'),
(639, 'pt', 'es', 'Portugués'),
(640, 'pt', 'pt', 'Português'),
(641, 'pt', 'fr', 'Portugais'),
(642, 'pt', 'de', 'Portugiesisch'),
(643, 'qu', 'en', 'Quechua'),
(644, 'qu', 'es', 'Quechua'),
(645, 'qu', 'qu', 'Runa Simi, Kichwa'),
(646, 'qu', 'fr', 'Quechua'),
(647, 'qu', 'de', 'Quechua-Sprache'),
(648, 'rm', 'en', 'Romansh'),
(649, 'rm', 'es', 'Retorrománico'),
(650, 'rm', 'rm', 'Rumantsch Grischun'),
(651, 'rm', 'fr', 'Romanche'),
(652, 'rm', 'de', 'Rätoromanisch'),
(653, 'rn', 'en', 'Rundi'),
(654, 'rn', 'es', 'Kirundi'),
(655, 'rn', 'rn', 'Ikirundi'),
(656, 'rn', 'fr', 'Rundi'),
(657, 'rn', 'de', 'Rundi-Sprache'),
(658, 'ro', 'en', 'Romanian, Moldavian, Moldovan'),
(659, 'ro', 'es', 'Rumano'),
(660, 'ro', 'ro', 'Română'),
(661, 'ro', 'fr', 'Roumain, Moldave'),
(662, 'ro', 'de', 'Rumänisch'),
(663, 'ru', 'en', 'Russian'),
(664, 'ru', 'es', 'Ruso'),
(665, 'ru', 'ru', 'русский'),
(666, 'ru', 'fr', 'Russe'),
(667, 'ru', 'de', 'Russisch'),
(668, 'sa', 'en', 'Sanskrit'),
(669, 'sa', 'es', 'Sánscrito'),
(670, 'sa', 'sa', 'संस्कृतम्'),
(671, 'sa', 'fr', 'Sanskrit'),
(672, 'sa', 'de', 'Sanskrit'),
(673, 'sc', 'en', 'Sardinian'),
(674, 'sc', 'es', 'Sardo'),
(675, 'sc', 'sc', 'Sardu'),
(676, 'sc', 'fr', 'Sarde'),
(677, 'sc', 'de', 'Sardisch'),
(678, 'sd', 'en', 'Sindhi'),
(679, 'sd', 'es', 'Sindhi'),
(680, 'sd', 'sd', 'सिन्धी, سنڌي، سندھی‎'),
(681, 'sd', 'fr', 'Sindhi'),
(682, 'sd', 'de', 'Sindhi-Sprache'),
(683, 'se', 'en', 'Northern Sami'),
(684, 'se', 'es', 'Sami septentrional'),
(685, 'se', 'se', 'Davvisámegiella'),
(686, 'se', 'fr', 'Sami du Nord'),
(687, 'se', 'de', 'Nordsaamisch'),
(688, 'sm', 'en', 'Samoan'),
(689, 'sm', 'es', 'Samoano'),
(690, 'sm', 'sm', 'gagana fa\'a Samoa'),
(691, 'sm', 'fr', 'Samoan'),
(692, 'sm', 'de', 'Samoanisch'),
(693, 'sg', 'en', 'Sango'),
(694, 'sg', 'es', 'Sango'),
(695, 'sg', 'sg', 'yângâ tî sängö'),
(696, 'sg', 'fr', 'Sango'),
(697, 'sg', 'de', 'Sango-Sprache'),
(698, 'sr', 'en', 'Serbian'),
(699, 'sr', 'es', 'Serbio'),
(700, 'sr', 'sr', 'српски језик'),
(701, 'sr', 'fr', 'Serbe'),
(702, 'sr', 'de', 'Serbisch'),
(703, 'gd', 'en', 'Gaelic, Scottish Gaelic'),
(704, 'gd', 'es', 'Gaélico escocés'),
(705, 'gd', 'gd', 'Gàidhlig'),
(706, 'gd', 'fr', 'Gaélique, Gaélique écossais'),
(707, 'gd', 'de', 'Gälisch-Schottisch'),
(708, 'sn', 'en', 'Shona'),
(709, 'sn', 'es', 'Shona'),
(710, 'sn', 'sn', 'chiShona'),
(711, 'sn', 'fr', 'Shona'),
(712, 'sn', 'de', 'Schona-Sprache'),
(713, 'si', 'en', 'Sinhala, Sinhalese'),
(714, 'si', 'es', 'Cingalés'),
(715, 'si', 'si', 'සිංහල'),
(716, 'si', 'fr', 'Singhalais'),
(717, 'si', 'de', 'Singhalesisch'),
(718, 'sk', 'en', 'Slovak'),
(719, 'sk', 'es', 'Eslovaco'),
(720, 'sk', 'sk', 'Slovenčina, Slovenský Jazyk'),
(721, 'sk', 'fr', 'Slovaque'),
(722, 'sk', 'de', 'Slowakisch'),
(723, 'sl', 'en', 'Slovenian'),
(724, 'sl', 'es', 'Esloveno'),
(725, 'sl', 'sl', 'Slovenski Jezik, Slovenščina'),
(726, 'sl', 'fr', 'Slovène'),
(727, 'sl', 'de', 'Slowenisch'),
(728, 'so', 'en', 'Somali'),
(729, 'so', 'es', 'Somalí'),
(730, 'so', 'so', 'Soomaaliga, af Soomaali'),
(731, 'so', 'fr', 'Somali'),
(732, 'so', 'de', 'Somali'),
(733, 'st', 'en', 'Southern Sotho'),
(734, 'st', 'es', 'Sesotho'),
(735, 'st', 'st', 'Sesotho'),
(736, 'st', 'fr', 'Sotho du Sud'),
(737, 'st', 'de', 'Süd-Sotho-Sprache'),
(738, 'es', 'en', 'Spanish (Spain)'),
(739, 'es', 'es', 'Español'),
(740, 'es', 'fr', 'Espagnol, Castillan'),
(741, 'es', 'de', 'Spanisch'),
(742, 'su', 'en', 'Sundanese'),
(743, 'su', 'es', 'Sundanés'),
(744, 'su', 'su', 'Basa Sunda'),
(745, 'su', 'fr', 'Soundanais'),
(746, 'su', 'de', 'Sundanesisch'),
(747, 'sw', 'en', 'Swahili'),
(748, 'sw', 'es', 'Suajili'),
(749, 'sw', 'sw', 'Kiswahili'),
(750, 'sw', 'fr', 'Swahili'),
(751, 'sw', 'de', 'Swahili'),
(752, 'ss', 'en', 'Suazi (Swati - SiSwati)'),
(753, 'ss', 'es', 'Suazi (Swati - SiSwati)'),
(754, 'ss', 'ss', 'SiSwati'),
(755, 'ss', 'fr', 'Swati'),
(756, 'ss', 'de', 'Swasi-Sprache'),
(757, 'sv', 'en', 'Swedish'),
(758, 'sv', 'es', 'Sueco'),
(759, 'sv', 'sv', 'Svenska'),
(760, 'sv', 'fr', 'Suédois'),
(761, 'sv', 'de', 'Schwedisch'),
(762, 'ta', 'en', 'Tamil'),
(763, 'ta', 'es', 'Tamil'),
(764, 'ta', 'ta', 'தமிழ்'),
(765, 'ta', 'fr', 'Tamoul'),
(766, 'ta', 'de', 'Tamil'),
(767, 'te', 'en', 'Telugu'),
(768, 'te', 'es', 'Telugú'),
(769, 'te', 'te', 'తెలుగు'),
(770, 'te', 'fr', 'Télougou'),
(771, 'te', 'de', 'Telugu-Sprache'),
(772, 'tg', 'en', 'Tajik'),
(773, 'tg', 'es', 'Tayiko'),
(774, 'tg', 'tg', 'тоҷикӣ, toçikī, تاجیکی‎'),
(775, 'tg', 'fr', 'Tadjik'),
(776, 'tg', 'de', 'Tadschikisch'),
(777, 'th', 'en', 'Thai'),
(778, 'th', 'es', 'Tailandés'),
(779, 'th', 'th', 'ไทย'),
(780, 'th', 'fr', 'Thai'),
(781, 'th', 'de', 'Thailändisch'),
(782, 'ti', 'en', 'Tigrinya'),
(783, 'ti', 'es', 'Tigriña'),
(784, 'ti', 'ti', 'ትግርኛ'),
(785, 'ti', 'fr', 'Tigrigna'),
(786, 'ti', 'de', 'Tigrinja-Sprache'),
(787, 'bo', 'en', 'Tibetan'),
(788, 'bo', 'es', 'Tibetano'),
(789, 'bo', 'bo', 'བོད་ཡིག'),
(790, 'bo', 'fr', 'Tibétain'),
(791, 'bo', 'de', 'Tibetisch'),
(792, 'tk', 'en', 'Turkmen'),
(793, 'tk', 'es', 'Turcomano'),
(794, 'tk', 'tk', 'Türkmen, Түркмен'),
(795, 'tk', 'fr', 'Turkmène'),
(796, 'tk', 'de', 'Turkmenisch'),
(797, 'tl', 'en', 'Tagalog'),
(798, 'tl', 'es', 'Tagalo'),
(799, 'tl', 'tl', 'Wikang Tagalog'),
(800, 'tl', 'fr', 'Tagalog'),
(801, 'tl', 'de', 'Tagalog'),
(802, 'tn', 'en', 'Tswana'),
(803, 'tn', 'es', 'Setsuana'),
(804, 'tn', 'tn', 'Setswana'),
(805, 'tn', 'fr', 'Tsimshian'),
(806, 'tn', 'de', 'Tsimshian-Sprache'),
(807, 'to', 'en', 'Tonga (Tonga Islands)'),
(808, 'to', 'es', 'Tongano'),
(809, 'to', 'to', 'Faka Tonga'),
(810, 'to', 'fr', 'Tongan (Îles Tonga)'),
(811, 'to', 'es', 'Tongaisch'),
(812, 'tr', 'en', 'Turkish'),
(813, 'tr', 'es', 'Turco'),
(814, 'tr', 'tr', 'Türkçe'),
(815, 'tr', 'fr', 'Turc'),
(816, 'tr', 'de', 'Türkisch'),
(817, 'ts', 'en', 'Tsonga'),
(818, 'ts', 'es', 'Tsonga'),
(819, 'ts', 'ts', 'Xitsonga'),
(820, 'ts', 'fr', 'Tsonga'),
(821, 'ts', 'de', 'Tsonga-Sprache'),
(822, 'tt', 'en', 'Tatar'),
(823, 'tt', 'es', 'Tártaro'),
(824, 'tt', 'tt', 'татар теле, tatar tele'),
(825, 'tt', 'fr', 'Tatar'),
(826, 'tt', 'de', 'Tatarisch'),
(827, 'tw', 'en', 'Twi'),
(828, 'tw', 'es', 'Twi'),
(829, 'tw', 'tw', 'Twi'),
(830, 'tw', 'fr', 'Twi'),
(831, 'tw', 'de', 'Twi'),
(832, 'ty', 'en', 'Tahitian'),
(833, 'ty', 'es', 'Tahitiano'),
(834, 'ty', 'ty', 'Reo Tahiti'),
(835, 'ty', 'fr', 'Tahitien'),
(836, 'ty', 'de', 'Tahitisch'),
(837, 'ug', 'en', 'Uighur, Uyghur'),
(838, 'ug', 'es', 'Uigur'),
(839, 'ug', 'ug', 'ئۇيغۇرچە‎, Uyghurche'),
(840, 'ug', 'fr', 'Ouïgour'),
(841, 'ug', 'de', 'Uigurisch'),
(842, 'uk', 'en', 'Ukrainian'),
(843, 'uk', 'es', 'Ucraniano'),
(844, 'uk', 'uk', 'Українська'),
(845, 'uk', 'fr', 'Ukrainien'),
(846, 'uk', 'de', 'Ukrainisch'),
(847, 'ur', 'en', 'Urdu'),
(848, 'ur', 'es', 'Urdu'),
(849, 'ur', 'ur', 'اردو'),
(850, 'ur', 'fr', 'Ourdou'),
(851, 'ur', 'de', 'Urdu'),
(852, 'uz', 'en', 'Uzbek'),
(853, 'uz', 'es', 'Uzbeko'),
(854, 'uz', 'uz', 'Oʻzbek, Ўзбек, أۇزبېك‎'),
(855, 'uz', 'fr', 'Ouszbek'),
(856, 'uz', 'de', 'Usbekisch'),
(857, 've', 'en', 'Venda'),
(858, 've', 'es', 'Venda'),
(859, 've', 've', 'Tshivenḓa'),
(860, 've', 'fr', 'Venda'),
(861, 've', 'de', 'Venda-Sprache'),
(862, 'vi', 'en', 'Vietnamese'),
(863, 'vi', 'es', 'Vietnamita'),
(864, 'vi', 'vi', 'Tiếng Việt'),
(865, 'vi', 'fr', 'Vietnamien'),
(866, 'vi', 'de', 'Vietnamesisch'),
(867, 'vo', 'en', 'Volapük'),
(868, 'vo', 'es', 'Volapük'),
(869, 'vo', 'vo', 'Volapük'),
(870, 'vo', 'fr', 'Volapük'),
(871, 'vo', 'de', 'Volapük'),
(872, 'wa', 'en', 'Walloon'),
(873, 'wa', 'es', 'Valón'),
(874, 'wa', 'wa', 'Walon'),
(875, 'wa', 'fr', 'Walon'),
(876, 'wa', 'de', 'Wallonisch'),
(877, 'cy', 'en', 'Welsh'),
(878, 'cy', 'es', 'Galés'),
(879, 'cy', 'cy', 'Cymraeg'),
(880, 'cy', 'fr', 'Gallois'),
(881, 'cy', 'de', 'Kymrisch'),
(882, 'wo', 'en', 'Wolof'),
(883, 'wo', 'es', 'Wolof'),
(884, 'wo', 'wo', 'Wollof'),
(885, 'wo', 'fr', 'Wolof'),
(886, 'wo', 'de', 'Wolof-Sprache'),
(887, 'fy', 'en', 'Western Frisian'),
(888, 'fy', 'es', 'Frisón'),
(889, 'fy', 'fy', 'Frysk'),
(890, 'fy', 'fr', 'Frison occidental'),
(891, 'fy', 'de', 'Friesisch'),
(892, 'xh', 'en', 'Xhosa'),
(893, 'xh', 'es', 'Xhosa'),
(894, 'xh', 'xh', 'isiXhosa'),
(895, 'xh', 'fr', 'Xhosa'),
(896, 'xh', 'de', 'Xhosa-Sprache'),
(897, 'yi', 'en', 'Yiddish'),
(898, 'yi', 'es', 'Yídish (Yiddish)'),
(899, 'yi', 'yi', 'ייִדיש'),
(900, 'yi', 'fr', 'Yiddish'),
(901, 'yi', 'de', 'Jiddish'),
(902, 'yo', 'en', 'Yoruba'),
(903, 'yo', 'es', 'Yoruba'),
(904, 'yo', 'yo', 'Yorùbá'),
(905, 'yo', 'fr', 'Yoruba'),
(906, 'yo', 'de', 'Yoruba-Sprache'),
(907, 'za', 'en', 'Zhuang, Chuang'),
(908, 'za', 'es', 'Chuan (Zhuang)'),
(909, 'za', 'za', 'Saɯ cueŋƅ, Saw cuengh'),
(910, 'za', 'fr', 'Zhuang, Chuang'),
(911, 'za', 'de', 'Zhuang'),
(912, 'zu', 'en', 'Zulu'),
(913, 'zu', 'es', 'Zulú'),
(914, 'zu', 'zu', 'isiZulu'),
(915, 'zu', 'fr', 'Zoulou'),
(916, 'zu', 'de', 'Zulu-Sprache');

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
-- Estructura de tabla para la tabla `payment_type`
--

CREATE TABLE `payment_type` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `script` varchar(50) DEFAULT NULL,
  `method` varchar(20) DEFAULT NULL COMMENT 'online, bank_tranfer',
  `image` varchar(50) DEFAULT NULL,
  `ordinal` int(11) DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `payment_type`
--

INSERT INTO `payment_type` (`id`, `name`, `script`, `method`, `image`, `ordinal`, `active`) VALUES
(1, 'Stripe', 'Stripe', 'online', NULL, 10, '1'),
(2, 'Redsys', 'Redsys', 'online', NULL, 40, '0'),
(3, 'Bank transfer', 'BankTransfer', 'bank_transfer', NULL, 30, '1'),
(4, 'Funds', 'Funds', 'funds', NULL, 20, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `product_type` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `period_demo` varchar(2) DEFAULT NULL COMMENT 'M - Months, D - Days',
  `num_period_demo` int(11) DEFAULT NULL,
  `period` varchar(2) DEFAULT NULL COMMENT 'Y - Year, M - Month',
  `num_period` int(11) DEFAULT NULL,
  `period_grace` varchar(2) DEFAULT NULL COMMENT 'Y - Year, M - Month, D - Days',
  `num_period_grace` int(11) DEFAULT NULL,
  `price` varchar(10) NOT NULL DEFAULT '0',
  `payed_class` varchar(25) DEFAULT NULL,
  `payed_method` varchar(50) DEFAULT NULL,
  `generate_commission` varchar(1) NOT NULL DEFAULT '0',
  `show_in_cp` varchar(1) NOT NULL DEFAULT '0',
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `product`
--

INSERT INTO `product` (`id`, `product_type`, `name`,                                                `period_demo`, `num_period_demo`, `period`, `num_period`, `period_grace`, `num_period_grace`, `price`,   `payed_class`, `payed_method`, `generate_commission`, `show_in_cp`, `active`) VALUES
( 1,                          1,              'Consultoría',                                         NULL,          0,                 'M',      1,            'D',            5,                  '300000',  'consultancy', 'paidAction',   '0',                   '1',          '1'),
( 2,                          2,              'Automatización Asistente Personal - Setup',           NULL,          0,                 'M',      0,            'D',            5,                  '350000',  'automation',  'paidAction',   '0',                   '1',          '1'),
( 3,                          3,              'Automatización Asistente Personal - Manten.',         NULL,          0,                 'M',      1,            'D',            5,                  '35000',   'automation',  'paidAction',   '0',                   '1',          '1'),
( 4,                          2,              'Automatización Agente telefónico reservas - Setup',   NULL,          0,                 'M',      0,            'D',            5,                  '250000',  'automation',  'paidAction',   '0',                   '1',          '1'),
( 5,                          3,              'Automatización Agente telefónico reservas - Manten.', NULL,          0,                 'M',      1,            'D',            5,                  '25000',   'automation',  'paidAction',   '0',                   '1',          '1'),
( 6,                          2,              'Automatización Agente telefónico no-show - Setup',    NULL,          0,                 'M',      0,            'D',            5,                  '350000',  'automation',  'paidAction',   '0',                   '1',          '1'),
( 7,                          3,              'Automatización Agente telefónico no-show - Manten.',  NULL,          0,                 'M',      1,            'D',            5,                  '35000',   'automation',  'paidAction',   '0',                   '1',          '1'),
( 8,                          4,              'RAG privado - Setup',                                 NULL,          0,                 'M',      0,            'D',            5,                  '15000',   'rag',         'paidAction',   '0',                   '1',          '1'),
( 9,                          5,              'RAG privado - Manten.',                               NULL,          0,                 'M',      1,            'D',            5,                  '15000',   'rag',         'paidAction',   '0',                   '1',          '1'),
(10,                          4,              'RAG compartido - Setup',                              NULL,          0,                 'M',      0,            'D',            5,                  '5000',    'rag',         'paidAction',   '0',                   '1',          '1'),
(11,                          5,              'RAG compartido - Manten.',                            NULL,          0,                 'M',      1,            'D',            5,                  '5000',    'rag',         'paidAction',   '0',                   '1',          '1'),
(12,                          6,              'Server privado - Setup',                              NULL,          0,                 'M',      0,            'D',            5,                  '5000',    'server',      'paidAction',   '0',                   '1',          '1'),
(13,                          7,              'Server privado - Manten.',                            NULL,          0,                 'M',      1,            'D',            5,                  '5000',    'server',      'paidAction',   '0',                   '1',          '1'),
(14,                          8,              'Server compartido - Setup',                           NULL,          0,                 'M',      0,            'D',            5,                  '5000',    'server',      'paidAction',   '0',                   '1',          '1'),
(15,                          9,              'Server compartido - Manten.',                         NULL,          0,                 'M',      1,            'D',            5,                  '5000',    'server',      'paidAction',   '0',                   '1',          '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_type`
--

CREATE TABLE `product_type` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `name_key` varchar(100) DEFAULT NULL,
  `table` varchar(50) DEFAULT NULL,
  `controller` varchar(50) DEFAULT NULL,
  `has_auto_renew` varchar(1) DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `product_type`
--

INSERT INTO `product_type` (`id`, `name`,                     `name_key`,                            `table`,        `controller`, `has_auto_renew`, `active`) VALUES
(1,                               'Consultancy',              'PRODUCT_TYPE_CONSULTANCY',            'consultancy',  'consultancy', '0',             '1'),
(2,                               'Automation - Setup',       'PRODUCT_TYPE_AUTOMATION_SETUP',       'automation',   'automation',  '0',             '1'),
(3,                               'Automation - Renewal',     'PRODUCT_TYPE_AUTOMATION_RENEWAL',     'automation',   'automation',  '1',             '1'),
(4,                               'RAG - Setup',              'PRODUCT_TYPE_PRIVATE_RAG',            'rag',          'rag',         '0',             '1'),
(5,                               'RAG - Renewal',            'PRODUCT_TYPE_SHARED_RAG',             'rag',          'rag',         '1',             '1'),
(6,                               'Server private - Setup',   'PRODUCT_TYPE_PRIVATE_SERVER_SETUP',   'server',       'server',      '0',             '1'),
(7,                               'Server private - Renewal', 'PRODUCT_TYPE_PRIVATE_SERVER_RENEWAL', 'server',       'server',      '1',             '1'),
(8,                               'Server shared - Setup',    'PRODUCT_TYPE_SHARED_SERVER_SETUP',    'server',       'server',      '0',             '1'),
(9,                               'Server shared - Renewal',  'PRODUCT_TYPE_SHARED_SERVER_RENEWAL',  'server',       'server',      '1',             '1');

-- ------------------------------------------------------

--
-- Estructura de tabla para la tabla `quote_type`
--

CREATE TABLE `quote_type` (
  `id` int(11) NOT NULL,
  `payment_type` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `template` varchar(100) DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `server_service`
--

CREATE TABLE `server_service` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `product_type`
--

INSERT INTO `server_service` (`id`, `name`, `active`) VALUES
(1, 'Rag', '1'),
(2, 'LLMs', '1'),
(3, 'N8N', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `session`
--

CREATE TABLE `session` (
  `sess_id` varbinary(255) NOT NULL,
  `sess_data` longblob DEFAULT NULL,
  `sess_time` int(11) DEFAULT NULL,
  `sess_lifetime` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `site_map`
--

CREATE TABLE `site_map` (
  `id` int(11) NOT NULL,
  `page_title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `subdomain` varchar(50) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `changefreg` varchar(10) DEFAULT NULL,
  `priority` varchar(5) DEFAULT NULL,
  `createddate` date DEFAULT NULL,
  `active` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `site_map`
--

INSERT INTO `site_map` (`id`, `page_title`, `description`,       `subdomain`, `slug`,       `changefreg`,  `priority`, `createddate`, `active`) VALUES
(1, 'Miel Sandonis - Inicio',          'Página inicio',     NULL,        '/',            'weekly',     '0.9',      '2025-10-01', '1'),
(2, 'Miel Sandonis - Conocenos',       'Página conocenos',  NULL,        '/conocenos',  'weekly',     '0.8',      '2025-10-01', '1'),
(3, 'Miel Sandonis - Blog',            'Página blog',       NULL,        '/blog',       'weekly',     '0.8',      '2025-10-01', '1'),
(4, 'Miel Sandonis - Contacto',        'Página contacto',   NULL,        '/contacto',   'weekly',     '0.8',      '2025-10-01', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `skin`
--

CREATE TABLE `skin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `folder` varchar(100) DEFAULT NULL,
  `default` varchar(1) NOT NULL DEFAULT '0',
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `skin`
--

INSERT INTO `skin` (`id`, `name`, `folder`, `default`, `active`) VALUES
(1, 'Default', 'default', '1', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `spammer`
--

CREATE TABLE `spammer` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `text` longtext DEFAULT NULL,
  `remote_addr` varchar(40) DEFAULT NULL,
  `http_x_forwarded_for` VARCHAR(40) DEFAULT NULL,
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vat_type`
--

CREATE TABLE `vat_type` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `percent` varchar(5) NOT NULL DEFAULT '0',
  `active` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `vat_type`
--

INSERT INTO `vat_type` (`id`, `name`, `percent`, `active`) VALUES
(1, 'Super reducido', '200', '1'),
(2, 'Reducido', '400', '1'),
(3, 'Normal', '2100', '1'),
(4, 'Lujo', '3500', '1'),
(5, 'Exento', '0', '1');

-- --------------------------------------------------------

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bank_account`
--
ALTER TABLE `bank_account`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bot`
--
ALTER TABLE `bot`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `blog_article`
--
ALTER TABLE `blog_article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_EECCB3E564C19C1` (`category`),
  ADD KEY `IDX_EECCB3E5BDAFD8C8` (`author`);

--
-- Indices de la tabla `blog_article_lang`
--
ALTER TABLE `blog_article_lang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_DB97ECE423A0E66` (`article`);

--
-- Indices de la tabla `blog_category`
--
ALTER TABLE `blog_category`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `blog_article_faq`
--
ALTER TABLE `blog_article_faq`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A3D0CA4823A0E66` (`article`);

--
-- Indices de la tabla `blog_author`
--
ALTER TABLE `blog_author`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `cron`
--
ALTER TABLE `cron`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lang`
--
ALTER TABLE `lang`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lang_name`
--
ALTER TABLE `lang_name`
  ADD PRIMARY KEY (`id`);

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
-- Indices de la tabla `payment_type`
--
ALTER TABLE `payment_type`
  ADD PRIMARY KEY (`id`);
  
--
-- Indices de la tabla `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D34A04AD1367588` (`product_type`);

--
-- Indices de la tabla `product_type`
--
ALTER TABLE `product_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_13675885E237E06` (`name`);

--
-- Indices de la tabla `quote_type`
--
ALTER TABLE `quote_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1E3908A35E237E06` (`name`),
  ADD UNIQUE KEY `UNIQ_1E3908A397601F83` (`template`),
  ADD KEY `IDX_1E3908A3AD5DC05D` (`payment_type`);

--
-- Indices de la tabla `server_service`
--
ALTER TABLE `server_service`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`sess_id`);

--
-- Indices de la tabla `site_map`
--
ALTER TABLE `site_map`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `skin`
--
ALTER TABLE `skin`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `spammer`
--
ALTER TABLE `spammer`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `vat_type`
--
ALTER TABLE `vat_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_A5B69FD75E237E06` (`name`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bank_account`
--
ALTER TABLE `bank_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bot`
--
ALTER TABLE `bot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `blog_article`
--
ALTER TABLE `blog_article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--

--
-- AUTO_INCREMENT de la tabla `blog_article_lang`
--
ALTER TABLE `blog_article_lang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `blog_category`
--
ALTER TABLE `blog_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `blog_article_faq`
--
ALTER TABLE `blog_article_faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `blog_author`
--
ALTER TABLE `blog_author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cron`
--
ALTER TABLE `cron`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `group`
--
ALTER TABLE `group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lang`
--
ALTER TABLE `lang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lang_name`
--
ALTER TABLE `lang_name`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
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

--
-- AUTO_INCREMENT de la tabla `payment_type`
--
ALTER TABLE `payment_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `product_type`
--
ALTER TABLE `product_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `quote_type`
--
ALTER TABLE `quote_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT de la tabla `server_service`
--
ALTER TABLE `server_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `site_map`
--
ALTER TABLE `site_map`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
--
-- AUTO_INCREMENT de la tabla `skin`
--
ALTER TABLE `skin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `spammer`
--
ALTER TABLE `spammer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `vat_type`
--
ALTER TABLE `vat_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
  
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `blog_article`
--
ALTER TABLE `blog_article`
  ADD CONSTRAINT `FK_EECCB3E564C19C1` FOREIGN KEY (`category`) REFERENCES `blog_category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_EECCB3E5BDAFD8C8` FOREIGN KEY (`author`) REFERENCES `blog_author` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `blog_article_lang`
--
ALTER TABLE `blog_article_lang`
  ADD CONSTRAINT `FK_DB97ECE423A0E66` FOREIGN KEY (`article`) REFERENCES `blog_article` (`id`);

--
-- Filtros para la tabla `blog_article_faq`
--
ALTER TABLE `blog_article_faq`
  ADD CONSTRAINT `FK_A3D0CA4823A0E66` FOREIGN KEY (`article`) REFERENCES `blog_article` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_D34A04AD1367588` FOREIGN KEY (`product_type`) REFERENCES `product_type` (`id`);

--
-- Filtros para la tabla `quote_type`
--
ALTER TABLE `quote_type`
  ADD CONSTRAINT `FK_1E3908A3AD5DC05D` FOREIGN KEY (`payment_type`) REFERENCES `payment_type` (`id`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
