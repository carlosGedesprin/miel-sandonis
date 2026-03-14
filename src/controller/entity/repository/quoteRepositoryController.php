<?php

namespace src\controller\entity\repository;

use src\controller\entity\accountController;
use src\controller\entity\userController;
use src\controller\entity\documentController;
use src\controller\entity\documentItemController;
use src\controller\entity\quoteLineController;

use src\controller\entity\langTextController;

use DateTime;
use DateTimeZone;

/**
 * Trait quote
 * @package entity
 */
trait quoteRepositoryController
{
    /**
     *
     * Get all quotes that doesn't have any invoice associated with date reg before limit
     *
     * @param $min_date dateTime object
     *
     * @return array List of quotes created after min_date
     */
    public function getAllUnpaidQuotesBeforeLimit( $min_date )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Min date =====> ('.$min_date.')'.PHP_EOL; fwrite($this->myfile, $txt);
        $sql = 'SELECT * FROM `'.$this->table.'` WHERE `invoice` = "" OR `invoice` IS NULL AND `date` >= "'.$min_date->format('Y-m-d').'" AND `total_to_pay` != "0"';
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->db->querySQL( $sql );
    }

    /**
     *
     * Get all quotes with an account payment method
     *
     * @param $payment_method_id string Account payment method id
     *
     * @return array List of quotes with an account payment method
     */
    public function getQuotesWithPaymentMethod( $payment_method )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Account payment method =====> ('.$payment_method.')'.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $this->getAll( ['payment_method' => $payment_method] );
    }


    /**
     *
     * Get quote with a payment reference
     *
     * @param $payment_reference string LeadFunding FundingKey in a Bank transfer payment
     *
     * @return string Quote Id
     */
    public function getQuoteByPaymentReference( $fundingKey )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Funding payment key =====> ('.$fundingKey.')'.PHP_EOL; fwrite($this->myfile, $txt);

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $filter = array( 'payment_reference' => $fundingKey  );

        return $this->getRegFromDB( $filter );
    }

    /**
     *
     * Creates an empty quote for an account
     *
     * @param $account_id int Account id to create quote
     * @param $quote_type int Quote type
     * @throws
     *
     * return void
     */
    public function createQuote( $account, $quote_type, $payment_origin=NULL )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Account id =====> ('.$account_id.') Quote type id =====> ('.$quote_type.')'.PHP_EOL; fwrite($this->myfile, $txt);
        $now = new DateTime('now', new DateTimeZone($this->session->config['time_zone']));

        $this->setId('');
        $this->setAccount( $account->getId() );
        $this->setInvoice( NULL );
        $this->setDate( $now );
        $this->setQuoteType( $quote_type );
        $this->setNet( 0 );
        $this->setVatAmount( 0 );
        $this->setTotalToPay( 0 );
        if ( $payment_origin ) $this->setPaymentOrigin( $payment_origin );
        $this->persistORL();

        $this->setQuoteKey( md5( $this->getId().$this->getAccount().$now->format('YmdHis') ) );
        $this->persistORL();

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Set Quote net, vat and total based on lines
     *
     * @param $quote object Quote to recalculate figures
     *
     * @return array This quote as an array
     */
    public function calculate_net_vat_total( $quote )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Quote id =====> ('.$quote->getId().')'.PHP_EOL; fwrite($this->myfile, $txt);

        $quote_line = new quoteLineController( array( 'env' => $this->env, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils, 'session' => $this->session, 'lang' => $this->lang ) );

        $total_price_quote = 0;

        $quote_lines = $quote_line->getLinesbyQuote( $quote->getId() );

        foreach ( $quote_lines as $quote_line_temp )
        {
            $quote_line->getRegbyId( $quote_line_temp['id'] );
//$txt = 'Quote Line  =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote_line->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            $total_price_quote += $quote_line->getTotal();
        }

//$txt = 'Quote total price  =========='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->setNet( $total_price_quote );

        $vat_amount = round($total_price_quote * ( $this->session->config['vat'] / 100 ) / 100, 0 );
//$txt = 'Vat amount ===> ('.$vat_amount.')'.PHP_EOL; fwrite($this->myfile, $txt);
        $this->setVatAmount( $vat_amount);
        $total_to_pay =  round( $total_price_quote + $vat_amount, 2);
//$txt = 'Total to pay ===> ('.$total_to_pay.')'.PHP_EOL; fwrite($this->myfile, $txt);
        $this->setTotalToPay( $total_to_pay );

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Print this Quote
     *
     * @return object pdf
     */

    public function printQuote( $pdf )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $account = new accountController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang ) );

        $user = new userController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang ) );

        $document = new documentController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang ) );

        $document_item = new documentItemController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang ) );

        $quote_line = new quoteLineController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang ) );

        $account->getRegbyId( $this->getAccount() );
//$txt = 'Account: '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $account->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//        if ( $account->getMainUser() )
//        {
//            $user->getRegbyId( $account->getMainUser() );
//            $locale = $user->getLocale();
//            $name = $user->getName();
//        }
//        else
//        {
            $locale = $account->getLocale();
//            $name = ( $account->getName() )? $account->getName() : langTextController::getLangText( $this->utils, $locale, 'CUSTOMER');
//        }

        $document->getRegbyReference( 'QUOTE' );

        $doc_data = array(
            'description' => $document->getDescription().' - '.$this->getId(),
            'doc_general' => array(),
            'doc_detail' => array(),
        );

        $doc_data['doc_general']['quote_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'QUOTE' );
        $doc_data['doc_general']['quote_id']['text'] = $this->getId();

        $doc_data['doc_general']['date_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'QUOTE_DATE' );
        $doc_data['doc_general']['quote_date']['text'] = ( !empty( $this->getDate() ) )? $this->getDate()->format('d-m-Y') : '';

        $doc_data['doc_general']['company_name']['text'] = $account->getCompany();

        if ( empty( $account->getCompany() ) || empty( $account->getAddress() ) || empty( $account->getVat() ) )
        {
            $doc_data['doc_general']['not_valid']['text'] = langTextController::getLangText( $this->utils, $locale, 'QUOTE_NOT_VALID' );
            $doc_data['doc_general']['not_valid']['text'] .= "\n".langTextController::getLangText( $this->utils, $locale, 'QUOTE_NOT_VALID_LACK_TAX_DATA' );
        }
//$txt = 'Account country  =====> ('.$account->getCountry().')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Account region  =====> ('.$account->getRegion().')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Account city  =====> ('.$account->getCity().')'.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Account alt city  =====> ('.$account->getAltCity().')'.PHP_EOL; fwrite($this->myfile, $txt);
        $account_country = $account->getCountry();
        $data_to_api = array(
                                'data' => array()
        );
        if ( !empty( $account_country ) )
        {
            $response = $this->utils->send_to_api( $_ENV['locations_api'].'/get_country_name/'.$_ENV['locations_api_key'].'/'.$account->getCountry().'/es', $data_to_api );
//$txt = 'Api response =====>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            if ( $response ) $account_country = $response['msg'][$account->getCountry()]['name'];
        }
        $account_region = $account->getRegion();
        if ( !empty( $account->getRegion() ) )
        {
            $response = $this->utils->send_to_api( $_ENV['locations_api'].'/get_region_name/'.$_ENV['locations_api_key'].'/'.$account->getCountry().'/'.$account->getRegion().'/es', $data_to_api );
//$txt = 'Api response =====>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            if ( $response ) $account_region = $response['msg'][$account->getRegion()]['name'];
        }
        $account_city = $account->getCity();
        if ( $account->getCity() !== NULL && $account->getCity() !== '' && $account->getCity() !== '0' )
        {
            $response = $this->utils->send_to_api($_ENV['locations_api'] . '/get_city_name/' . $_ENV['locations_api_key'] . '/' . $account->getCountry() . '/' . $account->getRegion() . '/' . $account->getCity() . '/es', $data_to_api );
//$txt = 'Api response =====>' . PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($response, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
            if ( $response ) $account_city = $response['msg'][$account->getCity()]['name'];
        }
        else
        {
            $account_city = $account->getAltCity();
        }

        $doc_data['doc_general']['company_address']['text'] = $account->getAddress();
        $doc_data['doc_general']['company_region']['text'] = $account_region;
        $doc_data['doc_general']['company_city']['text'] = $account->getPostCode().'  '.$account_city;

        if ( !empty( $account->getVat() ) ) {
            $doc_data['doc_general']['nif_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'QUOTE_VAT_AMOUNT' );
            $doc_data['doc_general']['company_nif']['text'] = $account->getVat();
        }

        $doc_data['doc_general']['description_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'QUOTE_LINE_DESCRIPTION' );
        $doc_data['doc_general']['amount_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'QUOTE_LINE_AMOUNT' );
        $doc_data['doc_general']['discount_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'QUOTE_LINE_DISCOUNT' );
        $doc_data['doc_general']['total_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'QUOTE_LINE_TOTAL' );

        $doc_data['doc_general']['totals_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'QUOTE_TOTALS' );

        $doc_data['doc_general']['totals_base_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'QUOTE_TAX_BASE' );
        $doc_data['doc_general']['totals_base']['text'] = number_format( floatval( $this->getNet() ) / 100, 2, ',', '.' ).'€';

        $doc_data['doc_general']['totals_vat_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'QUOTE_VAT_AMOUNT' );
        $doc_data['doc_general']['totals_vat_percent']['text'] = number_format( $this->session->config['vat'] / 100, 1, ',', '.' ).'%';
        $doc_data['doc_general']['totals_vat_amount']['text'] = number_format( floatval( $this->getVatAmount() ) / 100, 2, ',', '.' ).'€';

        $doc_data['doc_general']['totals_total_to_pay_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'QUOTE_TOTAL_TO_PAY_LABEL' );
        $doc_data['doc_general']['totals_total_to_pay']['text'] = number_format( floatval( $this->getTotalToPay() ) / 100, 2, ',', '.' ).'€';

        $franchise_addresses = array(
            $this->session->config['company_address_1'],
            $this->session->config['company_address_2'],
            $this->session->config['company_address_3'],
            $this->session->config['company_address_4'],
            $this->session->config['company_address_5']
        );

        $address = '';

        foreach ( $franchise_addresses as $franchise_address )
        {
            if ( !empty( $franchise_address ) ) {
                $address .= ' '.$franchise_address;
            }
        }

        $address .= ' '.$this->session->config['company_postcode'];

        $payment_tax_data_text = langTextController::getLangText( $this->utils, $locale, 'QUOTE_FOOTER_ADDRESS' );

        $doc_data['doc_general']['franchise_data']['text'] = sprintf( $payment_tax_data_text,
            $this->session->config['company_name'],
            $this->session->config['company_vat'],
            chr(10),
            $address,
            chr(10),
            $this->session->config['company_phone'],
            $this->session->config['company_email']
        );

        $pdf = $document->printDoc( $pdf, $doc_data );

        $quote_lines = $quote_line->getAllQuoteLinesFromQuote( $this->getId() );
//$txt = 'Quote lines ==============>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($quote_lines, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $quote_line_ids = array();
        foreach ( $quote_lines as $quote_line_temp )
        {
            $quote_line->getRegbyId( $quote_line_temp['id'] );
            $quote_line_ids[] = $quote_line->getId();
            $doc_data['doc_detail'][$quote_line->getId()]['units']['text'] = $quote_line->getUnits();
            $doc_data['doc_detail'][$quote_line->getId()]['description']['text'] = $quote_line->getDescription();
            $doc_data['doc_detail'][$quote_line->getId()]['price']['text'] = number_format( (floatval($quote_line->getPrice()) / 100), 2, ",", "." );
            $doc_data['doc_detail'][$quote_line->getId()]['amount']['text'] = number_format( (floatval($quote_line->getAmount()) / 100), 2, ",", "." );
            $doc_data['doc_detail'][$quote_line->getId()]['discount']['text'] = ( !empty( $quote_line->getDiscount() ) )? number_format( (floatval($quote_line->getDiscount()) / 100), 2, ",", "." ) : '';
            $doc_data['doc_detail'][$quote_line->getId()]['total']['text'] = number_format( (floatval($quote_line->getTotal()) / 100), 2, ",", "." );
        }
//$txt = 'Quote line ids ==============>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $quote_line_ids, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Document item detail records filter ==============> document '.$document->getId().' name => detail'.PHP_EOL; fwrite($this->myfile, $txt);
        $document_item_detail = $document_item->getAll( ['document' => $document->getId(), 'name' => 'detail'] );
//$txt = 'Document item detail box ==============> '.$document_item_detail[0]['id'].PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $document_item_detail, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $document_item->getRegbyId( $document_item_detail[0]['id'] );
//$txt = 'Document item detail ==============>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $document_item->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $line_detail_start = $document_item->getLineStart();
//$txt = 'Detail line start ==============> '.$line_detail_start.PHP_EOL; fwrite($this->myfile, $txt);

        $document_items = $document_item->getAll(['document' => $document->getId(), 'position' => 'detail'], 'ORDER BY `page`');
//$txt = 'Document items ==============>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $document_items, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $line_detail_increase = 10;
        foreach ( $quote_line_ids as $quote_line_id )
        {
//$txt = 'Quote line ===>'.$quote_line_id.PHP_EOL; fwrite($this->myfile, $txt);
            $line_detail_start = $line_detail_start + $line_detail_increase;
            foreach ( $document_items as $document_item_temp )
            {
                $document_item->getRegbyId( $document_item_temp['id'] );
//$txt = 'Item line ===>'.$document_item->getName().PHP_EOL; fwrite($this->myfile, $txt);
                $document_item->setLineStart( $line_detail_start );
                $document_item->setLineEnd( $line_detail_start );
                //$document_item->setColumnEnd( $document_item->getColumnEnd() + );
//$txt = 'Item to print ==============>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($document_item->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                $data_to_print = array (
                    'text' => ( isset( $doc_data['doc_detail'][$quote_line_id][$document_item->getText()]['text'] ) )? $doc_data['doc_detail'][$quote_line_id][$document_item->getText()]['text'] : NULL,
                    'url' => ( isset( $doc_data['doc_detail'][$quote_line_id][$document_item->getText()]['url'] ) )? $doc_data['doc_detail'][$quote_line_id][$document_item->getText()]['url'] : NULL,
                );
//$txt = 'With data ==============> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data_to_print, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                $pdf = $document_item->printDocItem( $pdf, $data_to_print );
            }
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        return $pdf;
    }
}
