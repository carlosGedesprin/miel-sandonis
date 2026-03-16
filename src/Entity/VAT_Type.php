<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * VAT Type
 *
 * @ORM\Table(name="vat_type")
 * @ORM\Entity(repositoryClass="src\Repository\VATTypeRepository")
 */
class VAT_Type
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
     * @ORM\Column(name="percent", type="string", length=5, options={"default": 0})
     */
    private $percent;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;
}