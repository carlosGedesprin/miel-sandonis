<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product Type
 *
 * @ORM\Table(name="product_type")
 * @ORM\Entity(repositoryClass="src\Repository\ProductTypeRepository")
 */
class Product_Type
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
     * @ORM\Column(name="name", type="string", length=100, unique=true, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="name_key", type="string", length=100, nullable=true)
     */
    private $name_key;

    /**
     * @var string
     *
     * @ORM\Column(name="table", type="string", length=50, nullable=true)
     */
    private $table;


    /**
     * @var string
     *
     * @ORM\Column(name="controller", type="string", length=50, nullable=true)
     */
    private $controller;

    /**
     * @var bool
     *
     * @ORM\Column(name="has_auto_renew", type="string", length=1, nullable=true)
     */
    private $has_auto_renew;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;

    /**
     * One product type has many products
     *
     * @ORM\OneToMany(targetEntity="Product", mappedBy="product_type")
     * @ORM\JoinColumn(name="product_type", referencedColumnName="id")
     */
    private $products;

    public function __construct() {
        $this->products = new ArrayCollection();
    }
}
