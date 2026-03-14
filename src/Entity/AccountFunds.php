<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * AccountFunds
 *
 * @ORM\Table(name="account_funds")
 * @ORM\Entity(repositoryClass="src\Repository\AccountFundsRepository")
 */
class AccountFunds
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
     * @var string
     *
     * @ORM\Column(name="funding_key", type="string", length=32, nullable=true)
     */
    private $funding_key;

    /**
     * One account has many account funds
     *
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumn(name="account", referencedColumnName="id", onDelete="CASCADE")
     */
    private $account;

    /**
     * @var int
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * user responsible
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="PaymentType", inversedBy="fundings")
     * @ORM\JoinColumn(name="payment_type", referencedColumnName="id")
     */
    private $payment_type;

    /**
     * Many fundings belongs to a payment method
     *
     * @ORM\ManyToOne(targetEntity="AccountPaymentMethod", inversedBy="fundings")
     * @ORM\JoinColumn(name="account_payment_method", referencedColumnName="id")
     */
    private $account_payment_method;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_reference", type="string", length=50, nullable=true)
     */
    private $payment_reference;

    /**
     * @var string
     *
     * @ORM\Column(name="credit", type="string", length=10, nullable=true)
     */
    private $credit;

    /**
     * @var string
     *
     * @ORM\Column(name="debit", type="string", length=10, nullable=true)
     */
    private $debit;

}
