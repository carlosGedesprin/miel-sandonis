<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * AccountPaymentMethod
 *
 * @ORM\Table(name="account_payment_method")
 * @ORM\Entity(repositoryClass="src\Repository\AccountPaymentMethodRepository")
  */
class AccountPaymentMethod
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
     * @var int
     *
     * Many payment_methods have one account.
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="payment_methods")
     * @ORM\JoinColumn(name="account", referencedColumnName="id")
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="key", type="string", length=32, unique=true, nullable=true)
     */
    private $key;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * Many Payments methods have One payment type.
     *
     * @ORM\ManyToOne(targetEntity="PaymentType")
     * @ORM\JoinColumn(name="payment_type", referencedColumnName="id")
     *
     */
    private $payment_type;

    /**
     * @var string
     *
     * @ORM\Column(name="IBAN", type="string", length=24, nullable=true)
     */
    private $IBAN;

    /**
     * @var string
     *
     * @ORM\Column(name="object_id", type="string", length=50, nullable=true, options={"comment":"Stripe object `pm_` id"})
     */
    private $object_id;

    /**
     * @var string
     *
     * @ORM\Column(name="object", type="string", length=20, nullable=true, options={"comment":"Stripe object `payment_method`"})
     */
    private $object;

    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=20, nullable=true, options={"comment":"Visa, MasterCard..."})
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=20, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="name_on_card", type="string", length=100, nullable=true)
     */
    private $name_on_card;

    /**
     * @var string
     *
     * @ORM\Column(name="last_4", type="string", length=4, nullable=true)
     */
    private $last_4;

    /**
     * @var string
     *
     * @ORM\Column(name="exp_month", type="string", length=2, nullable=true)
     */
    private $exp_month;

    /**
     * @var string
     *
     * @ORM\Column(name="exp_year", type="string", length=4, nullable=true)
     */
    private $exp_year;

    /**
     * @var bool
     *
     * @ORM\Column(name="cvc_check", type="string", length=20, nullable=true)
     */
    private $cvc_check;

    /**
     * @var bool
     *
     * @ORM\Column(name="funding", type="string", length=20, nullable=true)
     */
    private $funding;

    /**
     * @var bool
     *
     * @ORM\Column(name="preferred", type="string", length=1, options={"default": 0})
     */
    private $preferred;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 1})
     */
    private $active;

    /**
     * One quote has many payments
     *
     * @ORM\OneToMany(targetEntity="quote", mappedBy="payment_method")
     */
    private $quotes;
}