<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lead fair
 *
 * @ORM\Table(name="lead_fair")
 * @ORM\Entity(repositoryClass="src\Repository\LeadFairRepository")
 */
class LeadFair
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
     * @ORM\Column(name="lead_key", type="string", length=32, nullable=true)
     */
    private $lead_key;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_reg", type="datetime", nullable=true)
     */
    private $dateReg;

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
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=191, unique=true, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=32, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_mobile", type="string", length=15, nullable=true)
     */
    private $phone_mobile;

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
     * @ORM\Column(name="position", type="string", length=200, nullable=true)
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="linkedin", type="string", length=200, nullable=true)
     */
    private $linkedin;

    /**
     * @var string
     *
     * @ORM\Column(name="instagram", type="string", length=200, nullable=true)
     */
    private $instagram;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=200, nullable=true)
     */
    private $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="market", type="string", length=100, nullable=true)
     */
    private $market;

    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="string", length=100, nullable=true)
     */
    private $origin;

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
     * @var bool
     *
     * @ORM\Column(name="send_emails", type="string", length=1, options={"default": 1})
     */
    private $send_emails;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 1})
     */
    private $active;

}
