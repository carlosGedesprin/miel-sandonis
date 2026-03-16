<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * QuoteLine
 *
 * @ORM\Table(name="quote_extra")
 * @ORM\Entity(repositoryClass="src\Repository\QuoteExtraRepository")
 */
class QuoteExtra
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
     * @ORM\ManyToOne(targetEntity="Quote", inversedBy="quote_extras")
     * @ORM\JoinColumn(name="quote", referencedColumnName="id")
     */
    private $quote;

    /**
     * @var string
     *
     * @ORM\Column(name="next_action", type="string", length=250, nullable=true)
     */
    private $next_action;
}
