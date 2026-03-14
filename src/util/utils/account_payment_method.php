<?php
namespace src\util\utils;

/**
 * Trait account payment methods
 * @package Utils
 */
trait account_payment_method
{
    /**
    * Get the account payment method name
    *
    * Used in Twig filter
    *
    * @param $id      account payment method id
    * @return string   Account payment method name
    */
    public function getAccountPaymentMethodName( $id )
    {
        return $this->db->fetchField('account_payment_method', 'name', ['id' => $id]);
    }

    /**
    * Get the account payment method payment type name
    *
    * Used in Twig filter
    *
    * @param $id      account payment method id
    * @return string   Account payment method type name
     */
    public function getAccountPaymentMethodTypeName( $id )
    {
        $payment_type_id = $this->db->fetchField('account_payment_method', 'payment_type', ['id' => $id]);
        return $this->db->fetchField('payment_type', 'name', ['id' => $payment_type_id]);
    }

    /**
     * Check that the input value has a valid International Bank Account Number IBAN syntax
     * Requirements are uppercase, no whitespaces, max length 34, country code and checksum exist at right spots,
     * body matches against checksum via Mod97-10 algorithm
     *
     * @param mixed $check The value to check
     * @return bool Success
     */
    public function check_iban( $check ): bool
    {
        if (
            !is_string($check) ||
            !preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]{1,30}$/', $check)
        ) {
            return false;
        }

        $country = substr($check, 0, 2);
        $checkInt = intval(substr($check, 2, 2));
        $account = substr($check, 4);
        $search = range('A', 'Z');
        $replace = [];
        foreach (range(10, 35) as $tmp) {
            $replace[] = strval($tmp);
        }
        $numStr = str_replace($search, $replace, $account . $country . '00');
        $checksum = intval(substr($numStr, 0, 1));
        $numStrLength = strlen($numStr);
        for ($pos = 1; $pos < $numStrLength; $pos++) {
            $checksum *= 10;
            $checksum += intval(substr($numStr, $pos, 1));
            $checksum %= 97;
        }

        return $checkInt === 98 - $checksum;
    }
}
