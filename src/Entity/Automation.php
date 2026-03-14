<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Automation
 *
 * @ORM\Table(name="automation")
 * @ORM\Entity(repositoryClass="src\Repository\AutomationRepository")
  */
class Automation
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
     * @ORM\Column(name="automation_key", type="string", length=32, nullable=true)
     */
    private $automation_key;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumn(name="account", referencedColumnName="id")
     */
    private $account;

    /**
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumn(name="billing_account", referencedColumnName="id")
     */
    private $billing_account;

    /**
     * @var string
     *
     * @ORM\Column(name="date_reg", type="datetime", nullable=true)
     */
    private $dateReg;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_setup", referencedColumnName="id", nullable=true)
     */
    private $product_setup;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_renewal", referencedColumnName="id", nullable=true)
     */
    private $product_renewal;

    /**
     * @var string
     *
     * @ORM\Column(name="price_setup", type="string", length=10, options={"default": 0})
     */
    private $price_setup;

    /**
     * @var string
     *
     * @ORM\Column(name="price_renewal", type="string", length=10, options={"default": 0})
     */
    private $price_renewal;

    /**
     * @ORM\ManyToOne(targetEntity="Coupon", inversedBy="automations", fetch="EAGER")
     * @ORM\JoinColumn(name="coupon", referencedColumnName="id")
     */
    private $coupon;

    /**
     * @var bool
     *
     * @ORM\Column(name="auto_renew", type="string", length=1, options={"default": 1})
     */
    private $auto_renew;

    /**
     * @var int
     *
     * @ORM\Column(name="date_start", type="date", nullable=true)
     */
    private $dateStart;

    /**
     * @var int
     *
     * @ORM\Column(name="date_end", type="date", nullable=true)
     */
    private $dateEnd;

    /**
     * @ORM\ManyToOne(targetEntity="Rag")
     * @ORM\JoinColumn(name="rag", referencedColumnName="id", nullable=true)
     */
    private $rag;

    /**
     * @var string
     *
     * @ORM\Column(name="agent", type="string", length=11, nullable=true)
     */
    private $agent;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, nullable=true)
     */
    private $active;
}
