<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Payment
 *
 * @ORM\Table(name="payment")
 * @ORM\Entity(repositoryClass="src\Repository\PaymentRepository")
 */
class Payment
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
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="payments")
     * @ORM\JoinColumn(name="account", referencedColumnName="id", onDelete="CASCADE")
     */
    private $account;

    /**
     * @ORM\ManyToOne(targetEntity="Quote", inversedBy="payments")
     * @ORM\JoinColumn(name="quote", referencedColumnName="id", onDelete="CASCADE")
     */
    private $quote;

    /**
     * @ORM\ManyToOne(targetEntity="LeadFunding", inversedBy="payments")
     * @ORM\JoinColumn(name="funding", referencedColumnName="id", onDelete="CASCADE")
     */
    private $funding;

    /**
     * Many Payments have One payment type.
     *
     * @ORM\ManyToOne(targetEntity="PaymentType")
     * @ORM\JoinColumn(name="payment_type", referencedColumnName="id")
     *
     */
    private $payment_type;

    /**
     * @var integer
     *
     * @ORM\Column(name="instalment", type="integer", nullable=true)
     */
    private $instalment;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="string", length=10, options={"default": 0})
     */
    private $amount;

    /**
     * @var int
     *
     * @ORM\Column(name="result", type="integer", nullable=true)
     */
    private $result;

    /**
     * @var string
     *
     * @ORM\Column(name="typeTrans", type="string", length=50, nullable=true)
     */
    private $typeTrans;

    /**
     * @var string
     *
     * @ORM\Column(name="idTrans", type="string", length=50, nullable=true)
     */
    private $idTrans;

    /**
     * @var string
     *
     * @ORM\Column(name="codAproval", type="string", length=50, nullable=true)
     */
    private $codAproval;

    /**
     * @var string
     *
     * @ORM\Column(name="codError", type="string", length=50, nullable=true)
     */
    private $codError;

    /**
     * @var string
     *
     * @ORM\Column(name="desError", type="text", nullable=true)
     */
    private $desError;
}
