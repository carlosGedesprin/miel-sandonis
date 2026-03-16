<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Invoice
 *
 * @ORM\Table(name="invoice")
 * @ORM\Entity(repositoryClass="src\Repository\InvoiceRepository")
 */
class Invoice
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
     * Many invoices belongs to an account
     *
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="invoices")
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
     * @var bool
     *
     * @ORM\Column(name="payed", type="string", length=1, options={"default": 0})
     */
    private $payed;

    /**
     * One invoice has one successful payment
     *
     * @ORM\OneToOne(targetEntity="Payment")
     * @ORM\JoinColumn(name="payment", referencedColumnName="id", onDelete="CASCADE")
     */
    private $payment;

    /**
     * One invoice has many invoice lines
     *
     * @ORM\OneToMany(targetEntity="InvoiceLine", mappedBy="invoice")
     */
    private $lines;
    
    /**
     * One invoice has many commissions
     *
     * @ORM\OneToMany(targetEntity="Commission", mappedBy="invoice")
     */
    private $commissions;
    
    /**
     * One invoice has many quotes
     *
     * @ORM\OneToMany(targetEntity="Quote", mappedBy="invoice")
     */
    private $quotes;

    public function __construct()
    {
        $this->lines = new ArrayCollection();
        $this->commissions = new ArrayCollection();
        $this->quotes = new ArrayCollection();
    }
}
