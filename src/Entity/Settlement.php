<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Settlement
 *
 * @ORM\Table(name="settlement")
 * @ORM\Entity(repositoryClass="src\Repository\SettlementRepository")
 */
class Settlement
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
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * Many commissions belongs to an account
     *
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="commissions")
     * @ORM\JoinColumn(name="account", referencedColumnName="id")
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="net", type="string", length=10, options={"default": 0})
     */
    private $net;
    
    /**
     * @var string
     *
     * @ORM\Column(name="vat_amount", type="string", length=10, options={"default": 0})
     */
    private $vat_amount;
    
    /**
     * @var string
     *
     * @ORM\Column(name="total_to_pay", type="string", length=10, options={"default": 0})
     */
    private $total_to_pay;

    /**
     * @var bool
     *
     * @ORM\Column(name="payed", type="string", length=1, options={"default": 0})
     */
    private $payed;
}