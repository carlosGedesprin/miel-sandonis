<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="src\Repository\ProductRepository")
 */
class Product
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
     * @ORM\ManyToOne(targetEntity="Product_Type", inversedBy="products")
     * @ORM\JoinColumn(name="product_type", referencedColumnName="id")
     */
    private $product_type;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="period_demo", type="string", length=2, nullable=true, options={"comment":"M - Months, D - Days"})
     */
    private $period_demo;

    /**
     * @var string
     *
     * @ORM\Column(name="num_period_demo", type="integer", nullable=true)
     */
    private $num_period_demo;

    /**
     * @var string
     *
     * @ORM\Column(name="period", type="string", length=2, nullable=true, options={"comment":"Y - Year, M - Month"})
     */
    private $period;

    /**
     * @var string
     *
     * @ORM\Column(name="num_period", type="integer", nullable=true)
     */
    private $num_period;

    /**
     * @var string
     *
     * @ORM\Column(name="period_grace", type="string", length=2, nullable=true, options={"comment":"Y - Year, M - Month, D - Days"})
     */
    private $period_grace;

    /**
     * @var string
     *
     * @ORM\Column(name="num_period_grace", type="integer", nullable=true)
     */
    private $num_period_grace;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="string", length=10, options={"default": 0})
     */
    private $price;

    /**
     * @var bool
     *
     * @ORM\Column(name="payed_class", type="string", length=25, nullable=true)
     */
    private $payed_class;

    /**
     * @var bool
     *
     * @ORM\Column(name="payed_method", type="string", length=50, nullable=true)
     */
    private $payed_method;

    /**
     * @var bool
     *
     * @ORM\Column(name="generate_commission", type="string", length=1, options={"default": 0})
     */
    private $generate_commission;

    /**
     * @var bool
     *
     * @ORM\Column(name="show_in_cp", type="string", length=1, options={"default": 0})
     */
    private $show_in_cp;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;
}
