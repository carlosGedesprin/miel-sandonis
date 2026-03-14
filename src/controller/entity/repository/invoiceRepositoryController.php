<?php

namespace src\controller\entity\repository;

use \src\controller\entity\accountController;
use \src\controller\entity\userController;
use \src\controller\entity\documentController;
use \src\controller\entity\documentItemController;
use \src\controller\entity\invoiceLineController;

use \src\controller\entity\langTextController;

/**
 * Trait invoice
 * @package entity
 */
trait invoiceRepositoryController
{
    /**
     *
     * Create an Invoice from a Quote
     *
     * @param $quote object     Quote line to be treated
     * @param $date  \DateTime  Invoice creation date time
     *
     * @return void
     */

    public function createInvoice( $quote, $date )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
        $this->setId( '' );
        $this->setAccount( $quote->getAccount() );
        $this->setDate( $date );
        $this->setNet( $quote->getNet() );
        $this->setVatAmount( $quote->getVatAmount() );
        $this->setTotalToPay( $quote->getTotalToPay() );
        $this->setPayed( '1' );
        $this->persistORL();

//fwrite($this->myfile, print_r($this->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
    }

    /**
     *
     * Print this Invoice
     *
     * @return object pdf
     */

    public function printInvoice( $pdf )
    {
//$txt = '====================== '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);

        $account = new accountController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang ) );

        $user = new userController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang ) );

        $document = new documentController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang ) );

        $document_item = new documentItemController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang ) );

        $invoice_line = new invoiceLineController( array( 'env' => $_ENV, 'logger' => $this->logger, 'logger_err' => $this->logger_err, 'startup' => $this->startup, 'db' => $this->db, 'utils' => $this->utils,'session' => $this->session,'lang' => $this->lang ) );

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

        $document->getRegbyReference( 'INVOICE' );

        $doc_data = array(
            'description' => $document->getDescription().' - '.$this->getId(),
            'doc_general' => array(),
            'doc_detail' => array(),
        );

        $doc_data['doc_general']['invoice_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'INVOICE' );
        $doc_data['doc_general']['invoice_id']['text'] = $this->getId();

        $doc_data['doc_general']['date_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'INVOICE_DATE' );
        $doc_data['doc_general']['invoice_date']['text'] = ( !empty( $this->getDate() ) )? $this->getDate()->format('d-m-Y') : '';

        $doc_data['doc_general']['company_name']['text'] = $account->getCompany();

        if ( empty( $account->getCompany() ) || empty( $account->getAddress() ) || empty( $account->getVat() ) )
        {
            $doc_data['doc_general']['not_valid']['text'] = langTextController::getLangText( $this->utils, $locale, 'INVOICE_NOT_VALID' );
            $doc_data['doc_general']['not_valid']['text'] .= "\n".langTextController::getLangText( $this->utils, $locale, 'INVOICE_NOT_VALID_LACK_TAX_DATA' );
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
            $doc_data['doc_general']['nif_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'INVOICE_VAT_AMOUNT' );
            $doc_data['doc_general']['company_nif']['text'] = $account->getVat();
        }

        $doc_data['doc_general']['description_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'INVOICE_DESCRIPTION' );
        $doc_data['doc_general']['amount_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'INVOICE_AMOUNT' );
        $doc_data['doc_general']['discount_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'INVOICE_DISCOUNT' );
        $doc_data['doc_general']['total_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'INVOICE_TOTAL_TO_PAY' );

        $doc_data['doc_general']['totals_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'INVOICE_TOTALS' );

        $doc_data['doc_general']['totals_base_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'INVOICE_TAX_BASE' );
        $doc_data['doc_general']['totals_base']['text'] = number_format( floatval( $this->getNet() ) / 100, 2, ',', '.' ).'€';

        $doc_data['doc_general']['totals_vat_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'INVOICE_VAT_AMOUNT' );
        $doc_data['doc_general']['totals_vat_percent']['text'] = number_format( $this->session->config['vat'] / 100, 1, ',', '.' ).'%';
        $doc_data['doc_general']['totals_vat_amount']['text'] = number_format( floatval( $this->getVatAmount() ) / 100, 2, ',', '.' ).'€';

        $doc_data['doc_general']['totals_total_to_pay_label']['text'] = langTextController::getLangText( $this->utils, $locale, 'INVOICE_TOTAL_TO_PAY_LABEL' );
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

        $payment_tax_data_text = langTextController::getLangText( $this->utils, $locale, 'INVOICE_FOOTER_ADDRESS' );

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

        $invoice_lines = $invoice_line->getAllInvoiceLinesFromInvoice( $this->getId() );
//$txt = 'Invoice lines ==============>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($invoice_lines, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $invoice_line_ids = array();
        foreach ( $invoice_lines as $invoice_line_temp )
        {
            $invoice_line->getRegbyId( $invoice_line_temp['id'] );
            $invoice_line_ids[] = $invoice_line->getId();
            $doc_data['doc_detail'][$invoice_line->getId()]['units']['text'] = $invoice_line->getUnits();
            $doc_data['doc_detail'][$invoice_line->getId()]['description']['text'] = $invoice_line->getDescription();
            $doc_data['doc_detail'][$invoice_line->getId()]['price']['text'] = number_format( (floatval($invoice_line->getPrice()) / 100), 2, ",", "." );
            $doc_data['doc_detail'][$invoice_line->getId()]['amount']['text'] = number_format( (floatval($invoice_line->getAmount()) / 100), 2, ",", "." );
            $doc_data['doc_detail'][$invoice_line->getId()]['discount']['text'] = ( !empty( $invoice_line->getDiscount() ) )? number_format( (floatval($invoice_line->getDiscount()) / 100), 2, ",", "." ) : '';
            $doc_data['doc_detail'][$invoice_line->getId()]['total']['text'] = number_format( (floatval($invoice_line->getTotal()) / 100), 2, ",", "." );
        }
//$txt = 'Invoice line ids ==============>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $invoice_line_ids, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

//$txt = 'Document item detail records filter ==============> document '.$document->getId().' name => detail'.PHP_EOL; fwrite($this->myfile, $txt);
        $document_item_detail = $document_item->getAll( ['document' => $document->getId(), 'name' => 'detail'] );
//$txt = 'Document item detail ==============>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $document_item_detail, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $document_item->getRegbyId( $document_item_detail[0]['id'] );
//$txt = 'Document item detail ==============>'.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r( $document_item->getReg(), TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $line_detail_start = $document_item->getLineStart();
//$txt = 'Detail line start ==============> '.$line_detail_start.PHP_EOL; fwrite($this->myfile, $txt);

        $document_items = $document_item->getAll(['document' => $document->getId(), 'position' => 'detail'], 'ORDER BY `page`');
        $line_detail_increase = 10;
        foreach ( $invoice_line_ids as $invoice_line_id )
        {
//$txt = 'Invoice line ===>'.$invoice_line_id.PHP_EOL; fwrite($this->myfile, $txt);
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
                    'text' => ( isset( $doc_data['doc_detail'][$invoice_line_id][$document_item->getText()]['text'] ) )? $doc_data['doc_detail'][$invoice_line_id][$document_item->getText()]['text'] : NULL,
                    'url' => ( isset( $doc_data['doc_detail'][$invoice_line_id][$document_item->getText()]['url'] ) )? $doc_data['doc_detail'][$invoice_line_id][$document_item->getText()]['url'] : NULL,
                );
//$txt = 'With data ==============> '.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($data_to_print, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
                $pdf = $document_item->printDocItem( $pdf, $data_to_print );
            }
        }

//$txt = '====================== '.__METHOD__.' end ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//fclose( $this->myfile );
        return $pdf;
    }
}
