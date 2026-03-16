<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Quote
 *
 * @ORM\Table(name="quote")
 * @ORM\Entity(repositoryClass="src\Repository\QuoteRepository")
 */
class Quote
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
     * Many quotes belongs to an account
     *
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="quotes")
     * @ORM\JoinColumn(name="account", referencedColumnName="id")
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="quote_key", type="string", length=32, nullable=true)
     */
    private $quote_key;

    /**
     * @ORM\ManyToOne(targetEntity="Quote_Type", inversedBy="quotes")
     * @ORM\JoinColumn(name="quote_type", referencedColumnName="id")
     */
    private $quote_type;

    /**
     * @var string
     *
     * @ORM\Column(name="net", type="string", length=10, options={"default": 0})
     */
    private $net;

    /**
     * @var string
     *
     * @ORM\Column(name="vat_amount", type="string", length=10, options={"default": 0})
     */
    private $vatAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="total_to_pay", type="string", length=10, options={"default": 0})
     */
    private $totalToPay;

    /**
     * Many Quotes belongs to an invoice
     *
     * @ORM\ManyToOne(targetEntity="Invoice", inversedBy="quotes")
     * @ORM\JoinColumn(name="invoice", referencedColumnName="id")
     */

    private $invoice;

    /**
     * @ORM\ManyToOne(targetEntity="PaymentType", inversedBy="payment_types")
     * @ORM\JoinColumn(name="payment_type", referencedColumnName="id")
     */
    private $payment_type;

    /**
     * Many Quotes belongs to a payment method
     *
     * @ORM\ManyToOne(targetEntity="AccountPaymentMethod", inversedBy="quotes")
     * @ORM\JoinColumn(name="payment_method", referencedColumnName="id")
     */
    private $payment_method;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_origin", type="string", length=10, nullable=true, options={"comment":"online, cron"})
     */
    private $payment_origin;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_reference", type="string", length=50, nullable=true)
     */
    private $payment_reference;

    /**
     * One quote has many quote lines
     *
     * @ORM\OneToMany(targetEntity="QuoteLine", mappedBy="quote")
     */
    private $lines;

    /**
     * One quote has many payments
     *
     * @ORM\OneToMany(targetEntity="Payment", mappedBy="quote")
     */
    private $payments;

    public function __construct()
    {
        $this->lines = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }
}
