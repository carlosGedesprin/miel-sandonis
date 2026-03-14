<?php

//**************************************************************************
// ACOUNT
//**************************************************************************
$filter_getAccountName = new \Twig\TwigFilter('getAccountName', array($utils, 'getAccountName'));
$twig->addFilter($filter_getAccountName);
$filter_getAccountMainUser = new \Twig\TwigFilter('getAccountMainUser', array($utils, 'getAccountMainUser'));
$twig->addFilter($filter_getAccountMainUser);

//**************************************************************************
// ACOUNT PAYMENT METHOD
//**************************************************************************
$filter_getAccountPaymentMethodName = new \Twig\TwigFilter('getAccountPaymentMethodName', array($utils, 'getAccountPaymentMethodName'));
$twig->addFilter($filter_getAccountPaymentMethodName);
$filter_getAccountPaymentMethodTypeName = new \Twig\TwigFilter('getAccountPaymentMethodTypeName', array($utils, 'getAccountPaymentMethodTypeName'));
$twig->addFilter($filter_getAccountPaymentMethodTypeName);

//**************************************************************************
// GROUP
//**************************************************************************
$filter_getGroupName = new \Twig\TwigFilter('getGroupName', array($utils, 'getGroupName'));
$twig->addFilter($filter_getGroupName);
$filter_getGroupCapitalLetter = new \Twig\TwigFilter('getGroupCapitalLetter', array($utils, 'getGroupCapitalLetter'));
$twig->addFilter($filter_getGroupCapitalLetter);

//**************************************************************************
// USER
//**************************************************************************
$filter_getUserName = new \Twig\TwigFilter('getUserName', array($utils, 'getUserName'));
$twig->addFilter($filter_getUserName);

//$filter_getFullAddress = new \Twig\TwigFilter('getFullAddress', array($utils, 'getFullAddress'));
//$twig->addFilter($filter_getFullAddress);

$filter_isUserMainInAccount = new \Twig\TwigFilter('isUserMainInAccount', array($utils, 'isUserMainInAccount'));
$twig->addFilter($filter_isUserMainInAccount);

$filter_getUserRoleName = new \Twig\TwigFilter('getUserRoleName', array($utils, 'getUserRoleName'));
$twig->addFilter($filter_getUserRoleName);

//**************************************************************************
// PRODUCT
//**************************************************************************
$filter_getProductName = new \Twig\TwigFilter('getProductName', array($utils, 'getProductName'));
$twig->addFilter($filter_getProductName);

//**************************************************************************
// CATEGORY
//**************************************************************************
$filter_getCategoryName = new \Twig\TwigFilter('getCategoryName', array($utils, 'getCategoryName'));
$twig->addFilter($filter_getCategoryName);

//**************************************************************************
// CONFIG
//**************************************************************************
$filter_getConfig = new Twig_SimpleFilter('getConfig', array($utils, 'getConfig'));
$twig->addFilter($filter_getConfig);

//**************************************************************************
// LOCATIONS
//**************************************************************************
$filter_getCountryName = new \Twig\TwigFilter('getCountryName', array($utils, 'getCountryName'));
$twig->addFilter($filter_getCountryName);

$filter_getRegionName = new \Twig\TwigFilter('getRegionName', array($utils, 'getRegionName'));
$twig->addFilter($filter_getRegionName);

//**************************************************************************
// PAYMENT TYPE
//**************************************************************************
$filter_getPaymentTypeName = new \Twig\TwigFilter('getPaymentTypeName', array($utils, 'getPaymentTypeName'));
$twig->addFilter($filter_getPaymentTypeName);

//**************************************************************************
// DATES
//**************************************************************************
$filter_db_to_date = new \Twig\TwigFilter('db_to_date', array($utils, 'db_to_date')); //"YYYYMMDD" -> D-M-YYYY
$twig->addFilter($filter_db_to_date);

$filter_full_db_to_date = new \Twig\TwigFilter('full_db_to_date', array($utils, 'full_db_to_date')); //"YYYY-MM-DD H:i:s" -> D-M-YYYY H:i:s
$twig->addFilter($filter_full_db_to_date);

$filter_short_date_from_db = new \Twig\TwigFilter('short_date_from_db', array($utils, 'short_date_from_db'));
$twig->addFilter($filter_short_date_from_db);

$filter_short_date_to_db = new \Twig\TwigFilter('short_date_to_db', array($utils, 'short_date_to_db'));
$twig->addFilter($filter_short_date_to_db);

$filter_ddmmyyyy_to_yyyymmdd = new \Twig\TwigFilter('ddmmyyyy_to_yyyymmdd', array($utils, 'ddmmyyyy_to_yyyymmdd'));
$twig->addFilter($filter_ddmmyyyy_to_yyyymmdd);

$filter_yyyymmdd_to_ddmmyyyy = new \Twig\TwigFilter('yyyymmdd_to_ddmmyyyy', array($utils, 'yyyymmdd_to_ddmmyyyy'));
$twig->addFilter($filter_yyyymmdd_to_ddmmyyyy);

$filter_ddmmyyyyhis_to_yyyymmddhis = new \Twig\TwigFilter('ddmmyyyyhis_to_yyyymmddhis', array($utils, 'ddmmyyyyhis_to_yyyymmddhis'));
$twig->addFilter($filter_ddmmyyyyhis_to_yyyymmddhis);

$filter_yyyymmddhis_to_ddmmyyyyhis = new \Twig\TwigFilter('yyyymmddhis_to_ddmmyyyyhis', array($utils, 'yyyymmddhis_to_ddmmyyyyhis'));
$twig->addFilter($filter_yyyymmddhis_to_ddmmyyyyhis);

//**************************************************************************
// ADDRESS
//**************************************************************************
$filter_getCompanyAddressOneLine = new \Twig\TwigFilter('getCompanyAddressOneLine', array($utils, 'getCompanyAddressOneLine'));
$twig->addFilter($filter_getCompanyAddressOneLine);

//**************************************************************************
// BLOG
//**************************************************************************
$filter_getBlogCategoryTitle = new \Twig\TwigFilter('getBlogCategoryTitle', array($utils, 'getBlogCategoryTitle'));
$twig->addFilter($filter_getBlogCategoryTitle);
$filter_getBlogArticleTitle = new \Twig\TwigFilter('getBlogArticleTitle', array($utils, 'getBlogArticleTitle'));
$twig->addFilter($filter_getBlogArticleTitle);

//**************************************************************************
// CREDENTIAL TYPE
//**************************************************************************
$filter_getCredentialTypeName = new \Twig\TwigFilter('getCredentialTypeName', array($utils, 'getCredentialTypeName'));
$twig->addFilter($filter_getCredentialTypeName);

//**************************************************************************
// RAG
//**************************************************************************
$filter_getRagName = new \Twig\TwigFilter('getRagName', array($utils, 'getRagName'));
$twig->addFilter($filter_getRagName);