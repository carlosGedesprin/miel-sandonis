<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * AccountPaymentDetails
 *
 * @ORM\Table(name="account_pay_details")
 * @ORM\Entity(repositoryClass="src\Repository\AccountPaymentDetailsRepository")
  */
class AccountPaymentDetails
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
     * @ORM\Column(name="IBAN", type="string", length=24, nullable=true)
     */
    private $IBAN;

    /**
     * @var string
     *
     * @ORM\Column(name="last_4", type="string", length=4, nullable=true)
     */
    private $last_4;

    /**
     * @var int
     *
     * @ORM\Column(name="exp_date", type="date", nullable=true)
     */
    private $exp_date;

    /**
     * 
     * @ORM\OneToOne(targetEntity="Account", mappedBy="pay_details")
     * @ORM\JoinColumn(name="account", referencedColumnName="id", onDelete="CASCADE")
     */
    private $account;
}