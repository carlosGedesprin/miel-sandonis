<?php

$r->addRoute(['GET', 'POST'], '/n8n/get_warm_ip_accounts_to_process', 'api/n8nWarmIPControler:n8nGetAccountsAction');
$r->addRoute(['GET', 'POST'], '/n8n/get_warm_ip_account_by_id', 'api/n8nWarmIPControler:n8nGetAccountByIdAction');
$r->addRoute(['GET', 'POST'], '/n8n/get_warm_ip_account_by_email', 'api/n8nWarmIPControler:n8nGetAccountByEmailAction');
$r->addRoute(['GET', 'POST'], '/n8n/set_warm_ip_account_email', 'api/n8nWarmIPControler:n8nSetAccountEmailAction');

$r->addRoute(['GET', 'POST'], '/n8n/get_leads_to_process', 'api/n8nLeadController:n8nGetLeadsAction');
$r->addRoute(['GET', 'POST'], '/n8n/get_lead_by_id', 'api/n8nLeadController:n8nGetLeadByIdAction');
$r->addRoute(['GET', 'POST'], '/n8n/get_lead_by_email', 'api/n8nLeadController:n8nGetLeadByEmailAction');
$r->addRoute(['GET', 'POST'], '/n8n/set_lead_email', 'api/n8nLeadController:n8nSetLeadEmailAction');

$r->addRoute(['GET', 'POST'], '/n8n/get_news_sources', 'api/n8nNewsSourceController:n8nGetNewsSourcesAction');

$r->addRoute(['GET', 'POST'], '/n8n/get_news', 'api/n8nNewsController:n8nGetNewsAction');
$r->addRoute(['GET', 'POST'], '/n8n/set_news', 'api/n8nNewsController:n8nSetNewsAction');