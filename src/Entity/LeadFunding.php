<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lead
 *
 * @ORM\Table(name="lead_funding")
 * @ORM\Entity(repositoryClass="src\Repository\LeadFundingRepository")
 */
class LeadFunding
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
     * Many lead fundings belongs to an account
     *
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="lead_fundings")
     * @ORM\JoinColumn(name="account", referencedColumnName="id", nullable=true)
     */
    private $account;

    /**
     * Many lead fundings createds by an user
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="lead_fundings")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_reg", type="datetime", nullable=true)
     */
    private $dateReg;

    /**
     * @var string
     *
     * @ORM\Column(name="funding_key", type="string", length=32, nullable=true)
     */
    private $funding_key;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="string", length=50, nullable=true)
     */
    private $amount;


    /**
     * @var string
     *
     * @ORM\Column(name="amount_received", type="string", length=50, nullable=true)
     */
    private $amount_received;

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
     * @var bool
     *
     * @ORM\Column(name="applied", type="string", length=1, options={"default": 0})
     */
    private $applied;

    /**
     * @var string
     *
     * @ORM\Column(name="next_action", type="string", length=250, nullable=true)
     */
    private $next_action;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=32, nullable=true)
     */
    private $token;

}
