<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lead n8n
 *
 * @ORM\Table(name="n8n_lead")
 * @ORM\Entity(repositoryClass="src\Repository\LeadN8NRepository")
 */
class N8NLead
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
     * @var string
     *
     * @ORM\Column(name="date_reg", type="datetime", nullable=true)
     */
    private $dateReg;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=200, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=200, nullable=true)
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
     * @ORM\Column(name="locale", type="string", length=2, nullable=true)
     */
    private $locale;

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
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="domain_name", type="string", length=50, nullable=true)
     */
    private $domain_name;

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
     * Many leads have one market
     *
     * @ORM\ManyToOne(targetEntity="leadMarket")
     * @ORM\JoinColumn(name="market", referencedColumnName="id")
     *
     */
    private $market;

    /**
     * Many leads have one origin
     *
     * @ORM\ManyToOne(targetEntity="leadOrigin")
     * @ORM\JoinColumn(name="origin", referencedColumnName="id")
     *
     */
    private $origin;

    /**
     * @var string
     *
     * @ORM\Column(name="conscience", type="string", length=100, nullable=true)
     */
    private $conscience;

    /**
     * @var string
     *
     * @ORM\Column(name="bulk_info", type="text", nullable=true)
     */
    private $bulk_info;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;
}
