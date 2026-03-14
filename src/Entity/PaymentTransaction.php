<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Payment transactions
 *
 * @ORM\Table(name="payment_transaction")
 * @ORM\Entity(repositoryClass="src\Repository\paymentTransactionRepository")
 */
class PaymentTransaction
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="date_reg", type="datetime", nullable=true)
     */
    private $date_reg;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="payment_transactions")
     * @ORM\JoinColumn(name="account", referencedColumnName="id")
     */
    private $account;

    /**
     * @ORM\ManyToOne(targetEntity="AccountPaymentMethod", inversedBy="payment_transactions")
     * @ORM\JoinColumn(name="account_payment_method", referencedColumnName="id")
     */
    private $account_payment_method;

    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="string", length=20, nullable=true, options={"comment":"quote, funding"})
     */
    private $origin;

    /**
     * @ORM\ManyToOne(targetEntity="Quote", inversedBy="payment_transactions")
     * @ORM\JoinColumn(name="quote", referencedColumnName="id")
     */
    private $quote;

    /**
     * @ORM\ManyToOne(targetEntity="LeadFunding", inversedBy="payment_transactions")
     * @ORM\JoinColumn(name="funding", referencedColumnName="id")
     */
    private $funding;

    /**
     * @ORM\ManyToOne(targetEntity="PaymentType", inversedBy="payment_transactions")
     * @ORM\JoinColumn(name="payment_type", referencedColumnName="id")
     */
    private $payment_type;

    /**
     * @var string
     *
     * @ORM\Column(name="result", type="string", length=100, nullable=true)
     */
    private $result;

    /**
     * @var string
     *
     * @ORM\Column(name="event_id", type="string", length=100, nullable=true)
     */
    private $event_id;

    /**
     * @var string
     *
     * @ORM\Column(name="origin_id", type="string", length=100, nullable=true)
     */
    private $origin_id;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_id", type="string", length=100, nullable=true)
     */
    private $transaction_id;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction", type="text", nullable=true)
     */
    private $transaction;
}
