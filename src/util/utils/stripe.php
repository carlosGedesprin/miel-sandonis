<?php

namespace src\util\utils;

/**
 * Trait stripe
 * @package Utils
 */
trait stripe
{
    private $stripe;

    public function createCustomer( $account, $token ) 
    {
//$this->myfile = fopen(APP_ROOT_PATH.'/var/logs/utils'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
//$txt = 'utils '.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($this->myfile, $txt);
//$txt = 'Account =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($account, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);

        $customer = $this->stripe->customers->create([
            'email' => $account['notifications_email'],
            'name' => (!empty($account['company'])) ? $account['company'] : $account['name'],
            'phone' => $account['phone'],
            'metadata' => ["account" => $account['id']],
            'source' => $token,
        ]);
//$txt = 'Customer from stripe =========='.PHP_EOL; fwrite($this->myfile, $txt);
//fwrite($this->myfile, print_r($customer, TRUE)); $txt = PHP_EOL; fwrite($this->myfile, $txt);
        $this->db->updateArrayORL( 'account', '2', 'id', $account['id'], ['stripe_id' => $customer->id] );

        return $customer;
    }

    public function retrieveCustomer( $customer_stripe_id ) 
    {
        $customer = $this->stripe->customers->retrieve(
            $customer_stripe_id
        );

        return $customer;
    }
    
    public function deleteCustomer( $customer_stripe_id ) 
    {
        $customer = $this->stripe->customers->delete(
            $customer_stripe_id
        );

        return $customer;
    }

    public function updateSourceCustomer( $customer_stripe_id, $new_source )
    {
        $customer = $this->stripe->customers->update(
            $customer_stripe_id,
            ['source' => $new_source]
          );

        return $customer;
    }


    public function createPI( $product, $customer, $website, $coupon = null, $percent_iva, $return_domain, $total_price_visits )
    {
// $myfile = fopen(APP_ROOT_PATH.'/var/logs/util_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
// $txt = 'util_'.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);

        $total = $product['price'];

        if ( $product['needs_visits'] ) {
            $total = intval( $total_price_visits );
        }
        
        if ( isset( $coupon ) )
        {
            if ( $coupon['discount_type'] == 'amount' )
            {
                $amount_off = $coupon['discount'];
                $total -= $amount_off;
            }
            elseif ( $coupon['discount_type'] == '%' )
            {
                $percent_off = $coupon['discount'] / 100;

                $total_percent_off = $total * ( $percent_off / 100 );
                $total -= $total_percent_off;
            }
        }

        $iva = round( $total * ( $percent_iva / 10000) );
        
        $total += $iva;
        
        if ( isset( $coupon ) )
        {
            $pi = $this->stripe->paymentIntents->create([
                'amount' => $total,
                'currency' => 'eur',
                'customer' => $customer['id'],
                'confirm' => true,
                'setup_future_usage' => 'off_session',
                'return_url' => 'https://'.$return_domain,
                'use_stripe_sdk' => false,
                'metadata' => [ 'website' => $website['id'], 'product' => $product['id'], 'coupon' => $coupon['id']],
            ]);
            $this->db->updateArrayORL('website', '2', 'id', $website['id'], ['coupon' => $coupon['id']]);
        }
        else 
        {
            $pi = $this->stripe->paymentIntents->create([
                'amount' => $total,
                'currency' => 'eur',
                'customer' => $customer['id'],
                'confirm' => true,
                'setup_future_usage' => 'off_session',
                'return_url' => 'https://'.$return_domain,
                'use_stripe_sdk' => false,
                'metadata' => [ 'website' => $website['id'], 'product' => $product['id'] ],
            ]);
        }

        $this->db->updateArrayORL('website', '2', 'id', $website['id'], ['stripe_id' => $pi->id]);

        return $pi;
    }

    public function retrievePI( $pi_stripe_id ) 
    {
        $pi = $this->stripe->paymentIntents->retrieve(
            $pi_stripe_id
        );
        return $pi;
    }

    public function searchPIsByWebsiteAndCoupon( $website_id, $coupon )
    {
 $myfile = fopen(APP_ROOT_PATH.'/var/logs/util_'.__FUNCTION__.'.txt', 'a+') or die('Unable to open file!');
 $txt = 'util_'.__METHOD__.' start ==============================================================='.PHP_EOL; fwrite($myfile, $txt);
 $txt = 'Website ('.$website_id.') Coupon ('.$coupon.')'.PHP_EOL; fwrite($myfile, $txt);
        try {
            $PIs = $this->stripe->paymentIntents->search([
                'query' => 'metadata[\'coupon\']:\''.$coupon.'\' AND metadata[\'website\']:\''.$website_id.'\'',
            ]);
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Stripe search Error.');
            $this->logger_err->error('*************************************************************************');
            $this->logger_err->error('Website ('.$website_id.')');
            $this->logger_err->error('==================================================');
            $this->logger_err->error('Coupon ('.$coupon.')');
            $this->logger_err->error('==================================================');
            $this->logger_err->error('Error ('.$e.')');
            $this->logger_err->error('*************************************************************************');
            http_response_code(400);
            exit();
        }

        return $PIs;
    }

    public function setStripeKey($stripe_key)
    {
        if ( !isset($this->stripe) ) {
            $this->stripe = new \Stripe\StripeClient($stripe_key);
        }
    }
}