-- =====================================================================
-- Miel Sandonis · Limpieza de restos de la web anterior (Altira Automations)
-- Fecha: 2026-11-21
--
-- Aplicar sobre la base de datos `mielsandonis` (por ejemplo desde phpMyAdmin).
-- Recomendado: hacer antes una copia de seguridad de las tablas
--   config, lang_text_name, site_map
--
-- Todo va dentro de una transacción: si algo falla, no se aplica nada.
-- =====================================================================

START TRANSACTION;

-- ---------------------------------------------------------------------
-- 1. Configuración (tabla `config`)
-- ---------------------------------------------------------------------

-- Región geográfica: era ES-MAD (Madrid). Valladolid está en Castilla y León.
UPDATE `config` SET `config_value` = 'ES-CL'              WHERE `config_name` = 'web_meta_geo_region';
UPDATE `config` SET `config_value` = 'ES'                 WHERE `config_name` = 'web_meta_country';

-- Teléfono real (era el de relleno +34 555 55 55 55)
UPDATE `config` SET `config_value` = '+34 660 790 595'    WHERE `config_name` IN ('web_info_phone', 'company_phone');
UPDATE `config` SET `config_value` = '34660790595'        WHERE `config_name` = 'whatsapp_phone';

-- Redes sociales: tenían URLs genéricas (https://facebook.com, ...). Vacías hasta tener las reales.
UPDATE `config` SET `config_value` = ''
 WHERE `config_name` IN ('company_facebook', 'company_linkedin', 'company_youtube', 'company_twitter',
                         'company_pinterest', 'company_skype', 'company_instagram');

-- Descripción y copyright
UPDATE `config` SET `config_value` = REPLACE(`config_value`, 'Valldolid', 'Valladolid') WHERE `config_name` = 'web_description';
UPDATE `config` SET `config_value` = 'Miel Sandonis'      WHERE `config_name` = 'web_copy_right';
UPDATE `config` SET `config_value` = 'Miel Sandonis'      WHERE `config_name` = 'web_meta_author';
UPDATE `config` SET `config_value` = 'miel, miel artesanal, apicultura sostenible, Portillo, Valladolid, Pinares de Portillo, Villaverde de Medina'
 WHERE `config_name` = 'web_keywords';

-- Titular: Conrado Pérez Ramos (persona física; "Miel Sandonis" es el nombre comercial)
UPDATE `config` SET `config_value` = 'Calle Colegio, 13'  WHERE `config_name` = 'company_address_1';
UPDATE `config` SET `config_value` = ''                   WHERE `config_name` IN ('company_address_2', 'company_address_3');
UPDATE `config` SET `config_value` = 'Portillo'           WHERE `config_name` IN ('company_address_4', 'company_city');
UPDATE `config` SET `config_value` = 'Valladolid'         WHERE `config_name` IN ('company_address_5', 'company_region');
UPDATE `config` SET `config_value` = '47160'              WHERE `config_name` = 'company_postcode';

-- PENDIENTE: NIF = DNI de Conrado Pérez Ramos (ahora es el de relleno B55555555).
-- Cuando se tenga, descomentar y ponerlo también en la variable `titular.nif` de app/Resources/views/web/default.html.twig
-- UPDATE `config` SET `config_value` = '<DNI>' WHERE `config_name` = 'company_vat';

-- PENDIENTE: el formulario de contacto envía los mensajes a `customer_service_email_address`
-- (ahora info@mielsandonis.com). Si ese buzón no existe, descomentar para recibirlos en Gmail:
-- UPDATE `config` SET `config_value` = 'mielsandonis@gmail.com' WHERE `config_name` = 'customer_service_email_address';


-- ---------------------------------------------------------------------
-- 2. Textos (tablas `lang_text` / `lang_text_name`)
-- ---------------------------------------------------------------------

-- (Los textos legales y los datos para buscadores ya no se leen de la base de datos: están en las plantillas.)

-- Email de confirmación al cliente: "Uno de nuestros expertos de %1$s se pondrá en contacto..."
UPDATE `lang_text_name` ltn JOIN `lang_text` lt ON lt.`id` = ltn.`lang_text`
   SET ltn.`text` = 'Hemos recibido tu mensaje. Desde %1$s te responderemos lo antes posible.'
 WHERE lt.`lang_key` = 'MAIL_CONTACT_MAIL_TITLE_SENDER' AND ltn.`lang_code_2a` = 'es';
UPDATE `lang_text_name` ltn JOIN `lang_text` lt ON lt.`id` = ltn.`lang_text`
   SET ltn.`text` = 'We have received your message. The %1$s team will get back to you as soon as possible.'
 WHERE lt.`lang_key` = 'MAIL_CONTACT_MAIL_TITLE_SENDER' AND ltn.`lang_code_2a` = 'en';

-- Asunto del email que recibe Miel Sandonis
UPDATE `lang_text_name` ltn JOIN `lang_text` lt ON lt.`id` = ltn.`lang_text`
   SET ltn.`text` = 'Nuevo mensaje desde el formulario de contacto de la web'
 WHERE lt.`lang_key` IN ('MAIL_CONTACT_MAIL_SUBJECT', 'MAIL_CONTACT_MAIL_PREHEADER') AND ltn.`lang_code_2a` = 'es';


-- ---------------------------------------------------------------------
-- 3. Mapa del sitio (tabla `site_map`, la usa /sitemap_website.xml)
-- ---------------------------------------------------------------------

-- /conocenos no existe: la página es /nosotros
UPDATE `site_map` SET `slug` = '/nosotros', `page_title` = 'Miel Sandonis - Nosotros', `description` = 'Página nosotros'
 WHERE `slug` = '/conocenos';

-- El blog está desactivado (su contenido era de la web anterior)
UPDATE `site_map` SET `active` = '0' WHERE `slug` = '/blog';

-- Páginas que faltaban
INSERT INTO `site_map` (`page_title`, `description`, `subdomain`, `slug`, `changefreg`, `priority`, `createddate`, `active`)
SELECT 'Miel Sandonis - Conservación', 'Página conservación', NULL, '/conservacion', 'monthly', '0.8', '2026-11-21', '1'
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `site_map` WHERE `slug` = '/conservacion');
INSERT INTO `site_map` (`page_title`, `description`, `subdomain`, `slug`, `changefreg`, `priority`, `createddate`, `active`)
SELECT 'Miel Sandonis - Nuestra miel', 'Página nuestra miel', NULL, '/nuestra-miel', 'monthly', '0.8', '2026-11-21', '1'
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM `site_map` WHERE `slug` = '/nuestra-miel');


-- ---------------------------------------------------------------------
-- 4. Comprobaciones (revisar el resultado antes del COMMIT si se ejecuta paso a paso)
-- ---------------------------------------------------------------------
SELECT `config_name`, `config_value` FROM `config`
 WHERE `config_name` IN ('web_meta_geo_region', 'web_info_phone', 'company_facebook', 'web_description', 'customer_service_email_address');
SELECT `slug`, `active` FROM `site_map` ORDER BY `id`;

COMMIT;
