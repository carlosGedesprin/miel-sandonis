<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coupon
 *
 * @ORM\Table(name="coupon")
 * @ORM\Entity(repositoryClass="src\Repository\CouponRepository")
  */
class Coupon
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
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=10, unique=true, nullable=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="discount", type="string", length=5, options={"default": 0})
     */
    private $discount;

    /**
     * @var string
     *
     * @ORM\Column(name="discount_type", type="string", length=10, nullable=true, options={"comment":"% - Percentage, amount - Amount"})
     */
    private $discount_type;

    /**
     * @var string
     *
     * @ORM\Column(name="period", type="string", length=1, nullable=true, options={"comment":"Y - Year, M - Month"})
     */
    private $period;

    /**
     * @var int
     * 
     * @ORM\Column(name="num_period", type="integer", nullable=true)
     */
    private $num_period;

    /**
     * @var int
     *
     * @ORM\Column(name="validity_date_start", type="date", nullable=true)
     */
    private $validity_date_start;

    /**
     * @var int
     *
     * @ORM\Column(name="validity_date_end", type="date", nullable=true)
     */
    private $validity_date_end;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="coupons")
     * @ORM\JoinColumn(name="agent", referencedColumnName="id", onDelete="SET NULL")
     */
    private $agent;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="coupons")
     * @ORM\JoinColumn(name="integrator", referencedColumnName="id", onDelete="SET NULL")
     */
    private $integrator;

    /**
     * @var string
     *
     * @ORM\Column(name="commission_percent", type="string", length=4, options={"default": 0})
     */
    private $commission_percent;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;
    
    /**
     * @ORM\OneToMany(targetEntity="Quote", mappedBy="quotes", cascade={"persist", "remove", "merge"})
     */
    private $quotes;

    /**
     * @ORM\OneToMany(targetEntity="Invoice", mappedBy="invoices", cascade={"persist", "remove", "merge"})
     */
    private $invoices;
}