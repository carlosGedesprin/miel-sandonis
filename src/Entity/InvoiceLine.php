<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * InvoiceLine
 *
 * @ORM\Table(name="invoice_line")
 * @ORM\Entity(repositoryClass="src\Repository\InvoiceLineRepository")
 */
class InvoiceLine
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
     * @ORM\ManyToOne(targetEntity="Invoice", inversedBy="lines")
     * @ORM\JoinColumn(name="invoice", referencedColumnName="id")
     */
    private $invoice;

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
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="invoice_lines")
     * @ORM\JoinColumn(name="product", referencedColumnName="id")
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
