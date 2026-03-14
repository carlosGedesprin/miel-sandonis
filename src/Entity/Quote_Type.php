<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Quote Type
 *
 * @ORM\Table(name="quote_type")
 * @ORM\Entity(repositoryClass="src\Repository\QuoteTypeRepository")
 */
class Quote_Type
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
     * @ORM\Column(name="template", type="string", length=100, unique=true, nullable=true)
     */
    private $template;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity="PaymentType", inversedBy="payment_types")
     * @ORM\JoinColumn(name="payment_type", referencedColumnName="id")
     */
    private $payment_type;

    /**
     * One quote type has many quotes
     *
     * @ORM\OneToMany(targetEntity="Quote", mappedBy="quote_type")
     * @ORM\JoinColumn(name="quote_type", referencedColumnName="id")
     */
    private $quotes;

    public function __construct() {
        $this->quotes = new ArrayCollection();
    }
}
