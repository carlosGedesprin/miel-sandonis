-- ============================================================================
-- Vaciar el blog de la web anterior (Altira Automations)
-- Fecha: 2026-11-22
--
-- Todos los artículos, categorías, autores y FAQ del blog eran de la web
-- anterior. La parte pública del blog ya se ha borrado del código.
-- Se usan DELETE (no TRUNCATE) porque las tablas están unidas por claves
-- ajenas; el orden va de las tablas hijas a las padres.
-- Las tablas se mantienen vacías por si en el futuro se crea un blog propio.
--
-- Aplicar a mano (phpMyAdmin o cliente MySQL) sobre la BD de Miel Sandonis.
-- ============================================================================

START TRANSACTION;

DELETE FROM `blog_article_faq`;
DELETE FROM `blog_article_lang`;
DELETE FROM `blog_article`;
DELETE FROM `blog_category`;
DELETE FROM `blog_author`;

-- La entrada /blog del mapa del sitio ya no tiene página detrás
-- (la migración del 2026-11-21 solo la desactivaba)
DELETE FROM `site_map` WHERE `slug` LIKE '/blog%';

COMMIT;

-- Los nuevos registros vuelven a empezar por el id 1
-- (fuera de la transacción: ALTER TABLE hace commit implícito en MySQL)
ALTER TABLE `blog_article_faq`  AUTO_INCREMENT = 1;
ALTER TABLE `blog_article_lang` AUTO_INCREMENT = 1;
ALTER TABLE `blog_article`      AUTO_INCREMENT = 1;
ALTER TABLE `blog_category`     AUTO_INCREMENT = 1;
ALTER TABLE `blog_author`       AUTO_INCREMENT = 1;
