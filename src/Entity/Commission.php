<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Commission
 *
 * @ORM\Table(name="commission")
 * @ORM\Entity(repositoryClass="src\Repository\CommissionRepository")
  */
class Commission
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
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * Many commissions belongs to an account
     *
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="commissions")
     * @ORM\JoinColumn(name="account", referencedColumnName="id")
     */
    private $account;
    /**
     * Many commissions belongs to a settlement
     *
     * @ORM\ManyToOne(targetEntity="Settlement", inversedBy="commissions")
     * @ORM\JoinColumn(name="settlement", referencedColumnName="id")
     */
    private $settlement;
    
    /**
     * Many commission belongs to an invoice
     *
     * @ORM\ManyToOne(targetEntity="Invoice", inversedBy="commissions")
     * @ORM\JoinColumn(name="invoice", referencedColumnName="id")
     */
    private $invoice;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_net", type="string", length=10, nullable=true, options={"default": 0})
     */
    private $invoice_net;

    /**
     * @var string
     *
     * @ORM\Column(name="commission_percent", type="string", length=4, options={"default": 0})
     */
    private $commission_percent;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=80, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="total", type="string", length=10, options={"default": 0})
     */
    private $total;

    /**
     * @var bool
     *
     * @ORM\Column(name="payed", type="string", length=1, options={"default": 0})
     */
    private $payed;
}