-- ============================================================================
-- Eliminar las tablas de la web anterior (Altira Automations)
-- Fecha: 2026-11-22
--
-- La web de Miel Sandonis es solo informativa: no tiene panel de
-- administración, ni usuarios, ni formulario de contacto, ni envío de correos.
-- Estas tablas eran del panel, de los pagos, de n8n/RAG/servidores, del
-- formulario (lead, spammer), de la cola de correos (mail_queue, cron) y del
-- registro de auditoría (orl). Algunas guardan datos personales de la web
-- anterior, y no tiene sentido conservarlos.
--
-- SE CONSERVAN (las usa la web):
--   config, lang, lang_name, lang_text, lang_text_name, site_map, bot
--   blog_article, blog_article_faq, blog_article_lang, blog_author,
--   blog_category (vacías, por si en el futuro hay un blog propio)
--
-- ANTES DE APLICARLA: haz una copia de seguridad de la base de datos
-- (phpMyAdmin > Exportar). DROP TABLE no se puede deshacer.
--
-- Aplicar a mano (phpMyAdmin o cliente MySQL) sobre la BD de Miel Sandonis,
-- después de 2026-11-21_limpieza_restos_web_anterior.sql y de
-- 2026-11-22_vaciar_blog_web_anterior.sql.
-- ============================================================================

-- Las tablas están unidas por claves ajenas; se desactiva la comprobación
-- para poder borrarlas en cualquier orden.
SET FOREIGN_KEY_CHECKS = 0;

-- Formulario de contacto, cola de correos y cron
DROP TABLE IF EXISTS `lead`;
DROP TABLE IF EXISTS `lead_fair`;
DROP TABLE IF EXISTS `lead_market`;
DROP TABLE IF EXISTS `lead_origin`;
DROP TABLE IF EXISTS `spammer`;
DROP TABLE IF EXISTS `mail_queue`;
DROP TABLE IF EXISTS `cron`;

-- Usuarios, cuentas y sesiones del panel
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `user_notes`;
DROP TABLE IF EXISTS `user_profile`;
DROP TABLE IF EXISTS `user_role`;
DROP TABLE IF EXISTS `group`;
DROP TABLE IF EXISTS `session`;
DROP TABLE IF EXISTS `account`;
DROP TABLE IF EXISTS `account_funds`;
DROP TABLE IF EXISTS `account_funds_settings`;
DROP TABLE IF EXISTS `account_notes`;
DROP TABLE IF EXISTS `account_pay_details`;
DROP TABLE IF EXISTS `account_payment_method`;
DROP TABLE IF EXISTS `orl`;

-- Facturación, pagos y productos
DROP TABLE IF EXISTS `bank_account`;
DROP TABLE IF EXISTS `commission`;
DROP TABLE IF EXISTS `coupon`;
DROP TABLE IF EXISTS `invoice`;
DROP TABLE IF EXISTS `invoice_line`;
DROP TABLE IF EXISTS `lead_funding`;
DROP TABLE IF EXISTS `payment`;
DROP TABLE IF EXISTS `payment_transaction`;
DROP TABLE IF EXISTS `payment_type`;
DROP TABLE IF EXISTS `product`;
DROP TABLE IF EXISTS `product_type`;
DROP TABLE IF EXISTS `quote`;
DROP TABLE IF EXISTS `quote_extra`;
DROP TABLE IF EXISTS `quote_line`;
DROP TABLE IF EXISTS `quote_type`;
DROP TABLE IF EXISTS `settlement`;
DROP TABLE IF EXISTS `vat_type`;

-- Automatizaciones, n8n, RAG, servidores y catálogos de la web anterior
DROP TABLE IF EXISTS `automation`;
DROP TABLE IF EXISTS `credential`;
DROP TABLE IF EXISTS `credential_data`;
DROP TABLE IF EXISTS `credential_type`;
DROP TABLE IF EXISTS `credential_type_data`;
DROP TABLE IF EXISTS `n8n_lead`;
DROP TABLE IF EXISTS `n8n_lead_email`;
DROP TABLE IF EXISTS `n8n_warm_ip_account`;
DROP TABLE IF EXISTS `n8n_warm_ip_email`;
DROP TABLE IF EXISTS `rag`;
DROP TABLE IF EXISTS `rag_document`;
DROP TABLE IF EXISTS `sector`;
DROP TABLE IF EXISTS `sub_sector`;
DROP TABLE IF EXISTS `server`;
DROP TABLE IF EXISTS `server_service`;
DROP TABLE IF EXISTS `skin`;
DROP TABLE IF EXISTS `solution`;
DROP TABLE IF EXISTS `supplier`;
DROP TABLE IF EXISTS `workflow`;

SET FOREIGN_KEY_CHECKS = 1;

-- Comprobación: deberían quedar solo las 12 tablas que se conservan
-- (más alguna creada a mano después del volcado, si la hubiera).
SELECT `TABLE_NAME`
  FROM `information_schema`.`TABLES`
 WHERE `TABLE_SCHEMA` = DATABASE()
 ORDER BY `TABLE_NAME`;
