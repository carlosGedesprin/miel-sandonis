<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * AccountNotes
 *
 * @ORM\Table(name="account_funds_settings")
 * @ORM\Entity(repositoryClass="src\Repository\AccountFundsSettingsRepository")
 */
class AccountFundsSettings
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
     * One account funds settings has one account
     *
     * @ORM\OneToOne(targetEntity="Account")
     * @ORM\JoinColumn(name="account", referencedColumnName="id", onDelete="CASCADE")
     */
    private $account;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;

    /**
     * @var integer
     *
     * @ORM\Column(name="min", type="integer", nullable=true)
     */
    private $min;

    /**
     * @var bool
     *
     * @ORM\Column(name="auto_fill", type="string", length=1, options={"default": 0})
     */
    private $auto_fill;

    /**
     * @var integer
     *
     * @ORM\Column(name="fill_amount", type="integer", nullable=true)
     */
    private $fill_amount;

    /**
     * Many auto fundings belongs to a payment method
     *
     * @ORM\ManyToOne(targetEntity="AccountPaymentMethod", inversedBy="funds_auto_fill")
     * @ORM\JoinColumn(name="account_payment_method", referencedColumnName="id")
     */
    private $account_payment_method;
}
