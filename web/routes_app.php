<?php

$r->addRoute(['GET', 'POST'], '/dashboard', 'views/app/dashboardViewController:dashboardAction');
$r->addRoute(['GET', 'POST'], '/panel_de_control', 'views/app/dashboardViewController:dashboardAction');

// Auxilary tables
$r->addRoute(['GET', 'POST'], '/bots', 'views/app/botListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/bot/edit/{id:\d+}', 'views/app/botEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/bot/delete/{id:\d+}', 'views/app/botDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/crons', 'views/app/cronListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/cron/edit/{id:\d+}', 'views/app/cronEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/cron/delete/{id:\d+}', 'views/app/cronDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/langs', 'views/app/langListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/lang/edit/{id}', 'views/app/langEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/lang/delete/{id}', 'views/app/langDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/langtexts', 'views/app/langtextListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/langtext/edit/{id}', 'views/app/langtextEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/langtext/delete/{id}', 'views/app/langtextDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/product_types', 'views/app/product_typeListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/product_type/edit/{id:\d+}', 'views/app/product_typeEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/product_type/delete/{id:\d+}', 'views/app/product_typeDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/tipos_producto', 'views/app/product_typeListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/tipo_producto/edit/{id:\d+}', 'views/app/product_typeEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/tipo_product/delete/{id:\d+}', 'views/app/product_typeDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/vat_types', 'views/app/vat_typeListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/vat_type/edit/{id:\d+}', 'views/app/vat_typeEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/vat_type/delete/{id:\d+}', 'views/app/vat_typeDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/tipos_iva', 'views/app/vat_typeListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/tipo_iva/edit/{id:\d+}', 'views/app/vat_typeEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/tipo_iva/delete/{id:\d+}', 'views/app/vat_typeDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/bank_accounts', 'views/app/bankAccountListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/bank_account/edit/{id:\d+}', 'views/app/bankAccountEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/bank_account/delete/{id:\d+}', 'views/app/bankAccountDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/cuentas_banco', 'views/app/bankAccountListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/cuenta_banco/edit/{id:\d+}', 'views/app/bankAccountEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/cuenta_banco/delete/{id:\d+}', 'views/app/bankAccountDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/spammers', 'views/app/spammerListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/spammer/edit/{id:\d+}', 'views/app/spammerEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/spammer/delete/{id:\d+}', 'views/app/spammerDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/countries', 'views/app/countryListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/country/edit/{code_2a}', 'views/app/countryEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/country/delete/{code_2a}', 'views/app/countryDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/paises', 'views/app/countryListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/pais/edit/{code_2a}', 'views/app/countryEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/pais/delete/{code_2a}', 'views/app/countryDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/regions', 'views/app/regionListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/region/edit/{region_code}', 'views/app/regionEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/region/delete/{region_code}', 'views/app/regionDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/regiones', 'views/app/regionListViewController:itemslistAction');
//$r->addRoute(['GET', 'POST'], '/region/edit/{id:\d+}', 'views/app/regionEditViewController:edititemAction');
//$r->addRoute(['GET', 'POST'], '/region/delete/{id:\d+}', 'views/app/regionDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/cities', 'views/app/cityListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/city/edit/{city_code}', 'views/app/cityEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/city/delete/{city_code}', 'views/app/cityDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/localidades', 'views/app/cityListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/localidad/edit/{city_code}', 'views/app/cityEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/localidad/delete/{city_code}', 'views/app/cityDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/mail_queues', 'views/app/mailqueueListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/mail_queue/edit/{id:\d+}', 'views/app/mailqueueEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/mail_queue/delete/{id:\d+}', 'views/app/mailqueueDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/cola_mails', 'views/app/mailqueueListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/cola_mail/edit/{id:\d+}', 'views/app/mailqueueEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/cola_mail/delete/{id:\d+}', 'views/app/mailqueueDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/site_maps', 'views/app/sitemapListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/site_map/edit/{id:\d+}', 'views/app/sitemapEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/site_map/delete/{id:\d+}', 'views/app/sitemapDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/payments', 'views/app/paymentListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/payment/edit/{id:\d+}', 'views/app/paymentEditViewController:edititemAction');
//$r->addRoute(['GET', 'POST'], '/payment/delete/{id:\d+}', 'views/app/paymentDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/payment_types', 'views/app/payment_typeListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/payment_type/edit/{id:\d+}', 'views/app/payment_typeEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/payment_type/delete/{id:\d+}', 'views/app/payment_typeDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/payment_transactions', 'views/app/payment_transactionListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/payment_transaction/edit/{id:\d+}', 'views/app/payment_transactionEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/payment_transaction/delete/{id:\d+}', 'views/app/payment_transactionDeleteViewController:deleteitemAction');

// Management tables
$r->addRoute(['GET', 'POST'], '/accounts', 'views/app/accountListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/account/edit/{id:\d+}', 'views/app/accountEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/account/delete/{id:\d+}', 'views/app/accountDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/cuentas', 'views/app/accountListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/cuenta/edit/{id:\d+}', 'views/app/accountEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/cuenta/delete/{id:\d+}', 'views/app/accountDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/account_funds', 'views/app/accountFundsListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/account_fund/edit/{id}', 'views/app/accountFundEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/account_fund/delete/{id}', 'views/app/accountFundDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/users', 'views/app/userListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/user/edit/{id:\d+}', 'views/app/userEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/user/delete/{id:\d+}', 'views/app/userDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/usuarios', 'views/app/userListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/usuario/edit/{id:\d+}', 'views/app/userEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/usuario/delete/{id:\d+}', 'views/app/userDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/leads', 'views/app/leadListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/lead/edit/{id:\d+}', 'views/app/leadEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/lead/delete/{id:\d+}', 'views/app/leadDeleteViewController:deleteitemAction');
//$r->addRoute(['GET', 'POST'], '/leads', 'views/app/leadListViewController:itemslistAction');
//$r->addRoute(['GET', 'POST'], '/lead/edit/{id:\d+}', 'views/app/leadEditViewController:edititemAction');
//$r->addRoute(['GET', 'POST'], '/lead/delete/{id:\d+}', 'views/app/leadDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/n8n_leads', 'views/app/N8NleadListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/n8n_lead/edit/{id:\d+}', 'views/app/N8NleadEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/n8n_lead/delete/{id:\d+}', 'views/app/leadDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/n8n_warm_ip_accounts', 'views/app/N8NWarmIPAccountListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/n8n_warm_ip_account/edit/{id:\d+}', 'views/app/N8NWarmIPAccountEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/n8n_warm_ip_account/delete/{id:\d+}', 'views/app/N8NWarmIPAccountDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/lead_origins', 'views/app/leadOriginListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/lead_origin/edit/{id:\d+}', 'views/app/leadOriginEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/lead_origin/delete/{id:\d+}', 'views/app/leadOriginDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/lead_markets', 'views/app/leadMarketListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/lead_market/edit/{id:\d+}', 'views/app/leadMarketEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/lead_market/delete/{id:\d+}', 'views/app/leadMarketDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/groups', 'views/app/groupListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/group/edit/{id:\d+}', 'views/app/groupEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/group/delete/{id:\d+}', 'views/app/groupDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/grupos', 'views/app/groupListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/grupo/edit/{id:\d+}', 'views/app/groupEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/grupo/delete/{id:\d+}', 'views/app/groupDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/sectors', 'views/app/sectorListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/sector/edit/{id:\d+}', 'views/app/sectorEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/sector/delete/{id:\d+}', 'views/app/sectorDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/sectores', 'views/app/sectorListViewController:itemslistAction');
//$r->addRoute(['GET', 'POST'], '/sector/edit/{id:\d+}', 'views/app/sectorEditViewController:edititemAction');
//$r->addRoute(['GET', 'POST'], '/sector/delete/{id:\d+}', 'views/app/sectorDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/sub-sectors', 'views/app/subSectorListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/sub-sector/edit/{id:\d+}', 'views/app/subSectorEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/sub-sector/delete/{id:\d+}', 'views/app/subSectorDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/sub-sectores', 'views/app/subSectorListViewController:itemslistAction');
//$r->addRoute(['GET', 'POST'], '/sub-sector/edit/{id:\d+}', 'views/app/subSectorEditViewController:edititemAction');
//$r->addRoute(['GET', 'POST'], '/sub-sector/delete/{id:\d+}', 'views/app/subSectorDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/solutions', 'views/app/solutionListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/solution/edit/{id:\d+}', 'views/app/solutionEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/solution/delete/{id:\d+}', 'views/app/solutionDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/soluciones', 'views/app/solutionListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/solucion/edit/{id:\d+}', 'views/app/solutionEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/solucion/delete/{id:\d+}', 'views/app/solutionDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/credentials', 'views/app/credentialListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/credential/edit/{id:\d+}', 'views/app/credentialEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/credential/delete/{id:\d+}', 'views/app/credentialDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/credenciales', 'views/app/credentialListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/credencial/edit/{id:\d+}', 'views/app/credentialEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/credencial/delete/{id:\d+}', 'views/app/credentialDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/credential_types', 'views/app/credentialTypeListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/credential_type/edit/{id:\d+}', 'views/app/credentialTypeEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/credential_type/delete/{id:\d+}', 'views/app/credentialTypeEditViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/tipos_credenciales', 'views/app/credentialTypeListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/tipo_credencial/edit/{id:\d+}', 'views/app/credentialTypeEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/tipo_credencial/delete/{id:\d+}', 'views/app/credentialTypeEditViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/workflows', 'views/app/workflowListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/workflow/edit/{id:\d+}', 'views/app/workflowEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/workflow/delete/{id:\d+}', 'views/app/workflowDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/flujos', 'views/app/workflowListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/flujo/edit/{id:\d+}', 'views/app/workflowEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/flujo/delete/{id:\d+}', 'views/app/workflowDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/products', 'views/app/productListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/product/edit/{id:\d+}', 'views/app/productEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/product/delete/{id:\d+}', 'views/app/productDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/productos', 'views/app/productListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/producto/edit/{id:\d+}', 'views/app/productEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/producto/delete/{id:\d+}', 'views/app/productDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/categories', 'views/app/categoryListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/category/edit/{id:\d+}', 'views/app/categoryEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/category/delete/{id:\d+}', 'views/app/categoryDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/categorias', 'views/app/categoryListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/categoria/edit/{id:\d+}', 'views/app/categoryEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/categoria/delete/{id:\d+}', 'views/app/categoryDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/automations', 'views/app/automationListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/automation/edit/{id:\d+}', 'views/app/automationEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/automation/delete/{id:\d+}', 'views/app/automationDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/automatizaciones', 'views/app/automationListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/automatizacion/edit/{id:\d+}', 'views/app/automationEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/automatizacion/delete/{id:\d+}', 'views/app/automationDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/rags', 'views/app/ragListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/rag/edit/{id:\d+}', 'views/app/ragEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/rag/delete/{id:\d+}', 'views/app/ragDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/servers', 'views/app/serverListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/server/edit/{id:\d+}', 'views/app/serverEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/server/delete/{id:\d+}', 'views/app/serverDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/servidores', 'views/app/serverListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/servidor/edit/{id:\d+}', 'views/app/serverEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/servidor/delete/{id:\d+}', 'views/app/serverDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/rag-documents', 'views/app/ragDocumentListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/rag-document/edit/{id:\d+}', 'views/app/ragDocumentEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/rag-document/delete/{id:\d+}', 'views/app/ragDocumentDeleteViewController:deleteitemAction');
$r->addRoute(['GET', 'POST'], '/rag-documentos', 'views/app/ragDocumentListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/rag-documento/edit/{id:\d+}', 'views/app/ragDocumentEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/rag-documento/delete/{id:\d+}', 'views/app/ragDocumentDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/my_users', 'views/app/userViewController:itemslistAction'); //Pending
$r->addRoute(['GET', 'POST'], '/my_user/edit/{id:\d+}', 'views/app/userEditViewController:edititemAction'); //Pending
$r->addRoute(['GET', 'POST'], '/my_user/delete/{id:\d+}', 'views/app/userDeleteViewController:deleteitemAction'); //Pending
$r->addRoute(['GET', 'POST'], '/mis_usuarios', 'views/app/userViewController:itemslistAction'); //Pending
$r->addRoute(['GET', 'POST'], '/mi_usuario/edit/{id:\d+}', 'views/app/userEditViewController:edititemAction'); //Pending
$r->addRoute(['GET', 'POST'], '/mi_usuario/delete/{id:\d+}', 'views/app/userDeleteViewController:deleteitemAction'); //Pending

$r->addRoute(['GET', 'POST'], '/blog_authors', 'views/app/blogAuthorListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/blog_author/edit/{id:\d+}', 'views/app/blogAuthorEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/blog_author/delete/{id:\d+}', 'views/app/blogAuthorDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/blog_categories', 'views/app/blogCategoryListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/blog_category/edit/{id:\d+}', 'views/app/blogCategoryEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/blog_category/delete/{id:\d+}', 'views/app/blogCategoryDeleteViewController:deleteitemAction');

$r->addRoute(['GET', 'POST'], '/blog_articles', 'views/app/blogArticleListViewController:itemslistAction');
$r->addRoute(['GET', 'POST'], '/blog_article/edit/{id:\d+}', 'views/app/blogArticleEditViewController:edititemAction');
$r->addRoute(['GET', 'POST'], '/blog_article/delete/{id:\d+}', 'views/app/blogArticleDeleteViewController:deleteitemAction');