<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Rag
 *
 * @ORM\Table(name="rag")
 * @ORM\Entity(repositoryClass="src\Repository\RagRepository")
  */
class Rag
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
     * @ORM\Column(name="rag_key", type="string", length=32, nullable=true)
     */
    private $rag_key;

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
     * @ORM\ManyToOne(targetEntity="Server", inversedBy="rags")
     * @ORM\JoinColumn(name="server", referencedColumnName="id")
     */
    private $server;

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
     * @ORM\ManyToOne(targetEntity="Coupon", inversedBy="rags", fetch="EAGER")
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
     * @var string
     *
     * @ORM\Column(name="agent", type="string", length=11, nullable=true)
     */
    private $agent;

    /**
     * @var bool
     *
     * @ORM\Column(name="address", type="string", length=225, nullable=true)
     */
    private $address;

    /**
     * @var bool
     *
     * @ORM\Column(name="folder", type="string", length=100, nullable=true)
     */
    private $folder;

    /**
     * @var bool
     *
     * @ORM\Column(name="username", type="string", length=50, nullable=true)
     */
    private $username;

    /**
     * @var bool
     *
     * @ORM\Column(name="password", type="string", length=50, nullable=true)
     */
    private $password;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, nullable=true)
     */
    private $active;
}
