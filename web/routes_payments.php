<?php

// Payment distributor
$r->addRoute(['GET', 'POST'], '/pay_a_quote/{quote_key}', 'views/payments/paymentViewController:payQuoteAction');

// Pay a quote process
// contract link in mail = choose_product -> choose_payment_method -> pay_a_quote/{PaymentMethod}/
$r->addRoute(['GET', 'POST'], '/choose_quote_product/{quote_key}', 'views/payments/chooseProductViewController:chooseProductAction');
$r->addRoute(['GET', 'POST'], '/choose_quote_payment_method[/{quote_key}]', 'views/payments/choosePaymentMethodViewController:choosePaymentMethodAction');

// Payment method handlers
// We know the source
$r->addRoute(['GET', 'POST'], '/pay_a_quote/a9ce8c1201020e2b3e77/{quote_key}', 'views/payments/paymentStripeViewController:payQuoteAction');
$r->addRoute(['GET', 'POST'], '/pay_a_quote/5c2o6564f0db0aec4156/{quote_key}', 'views/payments/paymentRedsysViewController:payQuoteAction');
$r->addRoute(['GET', 'POST'], '/pay_a_quote/fde97e0eda19a6d80c94/{quote_key}', 'views/payments/paymentBankTransferViewController:payQuoteAction');

// We don't know the source
$r->addRoute(['GET', 'POST'], '/pay_a_quote/d0fc4acbd986c8f3dafe/{quote_key}', 'views/payments/paymentStripeViewController:getSourceAndpayQuoteAction');
$r->addRoute(['GET', 'POST'], '/pay_a_quote/7c976gd4640d1883b49b/{quote_key}', 'views/payments/paymentRedsysViewController:getSourceAndpayQuoteAction');

// Paid with funds
$r->addRoute(['GET', 'POST'], '/pay_a_quote/gd4640d18837c976b49b/{quote_key}', 'views/payments/paymentFundsViewController:payQuoteAction');

// Free quote
$r->addRoute(['GET', 'POST'], '/payment_result/free_quote/{quote_key}', 'views/payments/paymentResultViewController:paymentResultFree');

// Get discount from coupon
$r->addRoute(['GET', 'POST'], '/get_product_discounted', 'views/payments/chooseProductViewController:discountedProductPost');

$r->addRoute(['GET'], '/success_payment', 'views/payments/paymentStripeViewController:successPaymentAction');
$r->addRoute(['GET'], '/success_payment_info/{quote_key}', 'views/payments/paymentStripeViewController:successPaymentAction');

$r->addRoute(['GET'], '/auth_payment_result/{quote_key}', 'views/payments/paymentStripeViewController:paymentResultViewAction');

// Renew card distributor
$r->addRoute('GET', '/renew_card/{account_payment_method_key}', 'views/payments/cardRenewViewController:renewCardAction');

$r->addRoute(['GET', 'POST'], '/renew_card/o6564f05c2o6247f0db0vyz/{account_payment_method_key}', 'views/payments/paymentStripeViewController:payRenewCardAction');
$r->addRoute(['GET', 'POST'], '/renew_card/s22e8c1201020e2b3e77/{account_payment_method_key}', 'views/payments/paymentStripeViewController:renewCardAction');
$r->addRoute(['GET', 'POST'], '/renew_card/r53c2o6564f0db0aec4156/{account_payment_method_key}', 'views/payments/paymentRedsysViewController:renewCardAction');

//$r->addRoute(['GET', 'POST'], '/pay_a_renew_quote/e1096d7aac2614d6fb71/{quote_key}', 'views/payments/paymentBankTransferViewController:getSourceAndpayQuoteAction');

// Stripe Webhook listeners
$r->addRoute(['GET', 'POST'], '/stripe_things', 'payment_system/stripeController:WebhookStripe');
$r->addRoute(['GET', 'POST'], '/stripe_things_test', 'payment_system/stripeController:WebhookStripe');