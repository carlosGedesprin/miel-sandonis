<?php

//$r->addRoute('GET', '/populate_domain', 'testController:populateDomain');
$r->addRoute('GET', '/get_interval', 'testController:testDatesIntervalAction');
$r->addRoute('GET', '/get_page', 'testController:getPageAction');
$r->addRoute('GET', '/generateMail', 'testController:generateMailAction');
//$r->addRoute('GET', '/set_domain/{domain_name}', 'testController:setDomainAction');
//$r->addRoute(['GET', 'POST'], '/foto', 'fotoController:fotoAction');
//$r->addRoute(['GET', 'POST'], '/show-message', 'web/webViewController:showmessageAction');
$r->addRoute('GET', '/cron/{process}', 'testCronController:cronTestAction');

$r->addRoute('GET', '/mail_test/{template}', 'testMailController:mailTestAction');
$r->addRoute('GET', '/php_mailer_test', 'testMailController:phpMailerTestAction');

$r->addRoute('GET', '/test_webhook_stripe', 'testPaymentController:stripeWebhookTest');
$r->addRoute('GET', '/test_new_pi', 'testPaymentController:newPItest');
$r->addRoute('GET', '/create_customer/{account}', 'testPaymentController:createCustomerTest');
$r->addRoute('GET', '/pay_quote/{quote}', 'testPaymentController:payQuoteTest');
$r->addRoute('GET', '/info_payment_success', 'testPaymentController:testPaymentResultView');
$r->addRoute('GET', '/info_payment_free', 'testPaymentController:testFreePaymentResultView');
$r->addRoute('GET', '/test_account_balance', 'testPaymentController:testAccountFundBalanceView');

$r->addRoute('GET', '/miele', 'web/webViewController:mieleAction');
$r->addRoute('GET', '/rusticae', 'web/webViewController:rusticaeAction');
$r->addRoute('GET', '/rusticae_mail', 'web/webViewController:rusticaeMailAction');
$r->addRoute('GET', '/doblemente', 'web/webViewController:doblementeAction');

$r->addRoute('GET', '/recup_orl', 'testController:recupOrlAction');
$r->addRoute('GET', '/set_billing_account', 'testController:addBillingAccountAction');

$r->addRoute('GET', '/send_email_to_mailer/{mail_id}', 'testMailController:sendEmailToMailerAction');

//Utils tests
$r->addRoute('GET', '/test_user_email/{user_notifications_email}', 'testController:testUsersWithSameEmail');

$r->addRoute('GET', '/send_pa11y_domain/{domain}/{action}[/{report_type}]', 'testController:sendPa11yDomainAction');

$r->addRoute('GET', '/testWCAGReportPDF/{report}', 'testWCAGReportPDF:createReport');

$r->addRoute('GET', '/createReportPDF', 'testWCAGReportPDF:createPDFReport');
$r->addRoute('GET', '/send_WCAG_Report_PDF_to_queue', 'testWCAGReportPDF:sendReportToQueue');
$r->addRoute('GET', '/send_WCAG_Report_PDF', 'testWCAGReportPDF:requestReport');

$r->addRoute('GET', '/fill_payment_keys', 'testController:FillPaymentKey');
$r->addRoute('GET', '/fill_widget_price', 'testController:FillWidgetPrice');
$r->addRoute('GET', '/give_me_wcag_list', 'testWCAGReportPDF:giveMeWCAGList');

$r->addRoute('GET', '/to_utf8mb4_1', 'testToUTF8MN4Controller:doAction_1');
$r->addRoute('GET', '/to_utf8mb4_2', 'testToUTF8MN4Controller:doAction_2');

// n8n
$r->addRoute('GET', '/test_webhook_n8n/{param}', 'testn8nController:testWebHook');