<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Server
 *
 * @ORM\Table(name="server")
 * @ORM\Entity(repositoryClass="src\Repository\ServerRepository")
 */
class Server
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
     * @ORM\Column(name="server_key", type="string", length=32, unique=true, nullable=true)
     */
    private $server_key;

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
     * @var string
     *
     * @ORM\Column(name="server_name", type="string", length=100, nullable=true)
     */
    private $server_name;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255, nullable=true)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="server_id", type="string", length=15, nullable=true)
     */
    private $server_id;

    /**
     * @var bool
     *
     * @ORM\Column(name="root_password", type="string", length=50, nullable=true)
     */
    private $root_password;

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
     * @ORM\Column(name="customer_username", type="string", length=50, nullable=true)
     */
    private $customer_username;

    /**
     * @var bool
     *
     * @ORM\Column(name="customer_password", type="string", length=50, nullable=true)
     */
    private $customer_password;

    /**
     * @var string
     *
     * @ORM\Column(name="services", type="string", length=50, nullable=true)
     */
    private $services;

    /**
     * @var string
     *
     * @ORM\Column(name="bulk_info", type="text", nullable=true)
     */
    private $bulk_info;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"comment":"0-un active 1-active 2-initializing"}))
     */
    private $active;
}
