<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lead
 *
 * @ORM\Table(name="lead")
 * @ORM\Entity(repositoryClass="src\Repository\LeadRepository")
 */
class Lead
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_reg", type="datetime", nullable=true)
     */
    private $dateReg;

    /**
     * @var string
     *
     * @ORM\Column(name="lead_key", type="string", length=32, nullable=true)
     */
    private $lead_key;

    /**
     * @var int
     *
     * @ORM\Column(name="account", type="integer", nullable=true)
     */
    private $account;

    /**
     * @var int
     *
     * @ORM\Column(name="user", type="integer", nullable=true)
     */
    private $user;

    /**
     * Many Accounts have One Group.
     *
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="accounts")
     * @ORM\JoinColumn(name="group", referencedColumnName="id")
     * @ORM\Column(options={"comment":"3-Staff 4-Customer 5-Agent 6-Integrator"}))
     *
     */
    private $group;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=25, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=191, unique=true, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=2, nullable=true)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=100, nullable=true)
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="post_code", type="string", length=10, nullable=true)
     */
    private $postCode;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=2, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=20, nullable=true)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=20, nullable=true)
     */
    private $city;

    /**
     * @var string|null
     *
     * @ORM\Column(name="alt_city", type="string", length=100, nullable=true)
     */
    private $altCity;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=15, nullable=true)
     */
    private $phone;

    /**
     * @var bool
     *
     * @ORM\Column(name="send_emails", type="string", length=1, options={"default": 1})
     */
    private $send_emails;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 1})
     */
    private $active;

}
