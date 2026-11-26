-- La página de Conservación se oculta de momento (sus rutas están comentadas en web/app.php).
-- Se quita del mapa del sitio para que /sitemap_website.xml no enlace a una página que da 404.
-- Para recuperarla: UPDATE `site_map` SET `active` = '1' WHERE `slug` = '/conservacion';
UPDATE `site_map` SET `active` = '0' WHERE `slug` = '/conservacion';
