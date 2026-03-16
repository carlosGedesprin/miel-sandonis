<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * QuoteLine
 *
 * @ORM\Table(name="quote_line")
 * @ORM\Entity(repositoryClass="src\Repository\QuoteLineRepository")
 */
class QuoteLine
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
     * @ORM\ManyToOne(targetEntity="Quote", inversedBy="quote_lines")
     * @ORM\JoinColumn(name="quote", referencedColumnName="id")
     */
    private $quote;

    /**
     * @var string
     *
     * @ORM\Column(name="item", type="string", length=100, nullable=true)
     */
    private $item;

    /**
     * @var string
     *
     * @ORM\Column(name="units", type="string", length=10, options={"default": 1})
     */
    private $units;

    /**
     * @var string
     *
     * @ORM\Column(name="product", type="string", length=40, nullable=true)
     */
    private $product;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="string", length=10, options={"default": 0})
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="string", length=10, options={"default": 0})
     */
    private $amount;
    
    /**
     * @ORM\ManyToOne(targetEntity="Coupon", inversedBy="quotes", fetch="EAGER")
     * @ORM\JoinColumn(name="coupon", referencedColumnName="id")
     */
    private $coupon;

    /**
     * @var string
     *
     * @ORM\Column(name="discount", type="string", length=10, options={"default": 0})
     */
    private $discount;

    /**
     * @var string
     *
     * @ORM\Column(name="total", type="string", length=10, options={"default": 0})
     */
    private $total;
}
