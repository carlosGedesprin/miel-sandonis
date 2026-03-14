<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * LeadMarket
 *
 * @ORM\Table(name="lead_market")
 * @ORM\Entity(repositoryClass="src\Repository\LeadMarketRepository")
  */
class LeadMarket
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
     * @ORM\Column(name="lang_key", type="string", length=50, nullable=true)
     */
    private $lang_key;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 1})
     */
    private $active;
}