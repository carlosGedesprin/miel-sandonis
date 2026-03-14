<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Account
 *
 * @ORM\Table(name="account")
 * @ORM\Entity(repositoryClass="src\Repository\AccountRepository")
  */
class Account
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
     * @ORM\Column(name="account_key", type="string", length=32, unique=true, nullable=true)
     */
    private $account_key;

    /**
     * Many Accounts have One Group.
     *
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="accounts")
     * @ORM\JoinColumn(name="group", referencedColumnName="id")
     * @ORM\Column(options={"comment":"3-Staff 4-Customer 5-Agent"}))
     *
     */
    private $group;

    /**
     * @var string
     *
     * @ORM\Column(name="main_user", type="string", length=5, nullable=true)
     */
    private $main_user;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=100, nullable=true)
     */
    private $company;

    /**
     * @var string
     *
     * @ORM\Column(name="notifications_email", type="string", length=100, nullable=true)
     */
    private $notifications_email;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=2, nullable=true)
     */
    private $locale;

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
     * @var string
     *
     * @ORM\Column(name="vat", type="string", length=25, nullable=true)
     */
    private $vat;

    /**
     * @ORM\ManyToOne(targetEntity="VAT_Type")
     * @ORM\JoinColumn(name="vat_type", referencedColumnName="id")
     */
    private $vat_type;

    /**
     * @var string
     *
     * @ORM\Column(name="agent", type="string", length=11, nullable=true)
     */
    private $agent;

    /**
     * @var string
     *
     * @ORM\Column(name="show_to_staff", type="string", length=1, options={"comment":"With 0 only admin can see it", "default": 1}))
     */
    private $show_to_staff;

    /**
     * @var string
     *
     * @ORM\Column(name="allow_staff_use_card", type="string", length=1, options={"default": 1})
     */
    private $allow_staff_use_card;

    /**
     * @ORM\ManyToOne(targetEntity="PaymentType", inversedBy="accounts", fetch="EAGER")
     * @ORM\JoinColumn(name="preferred_payment_type", referencedColumnName="id")
     */
    private $preferred_payment_type;

    /**
     * @var string
     *
     * @ORM\Column(name="stripe_id", type="string", length=100, nullable=true)
     */
    private $stripe_id;

    /**
     * @var string
     *
     * @ORM\Column(name="commission_percent", type="string", length=4, options={"default": 0}))
     */
    private $commission_percent;

    /**
     * @ORM\ManyToOne(targetEntity="Coupon", inversedBy="accounts", fetch="EAGER")
     * @ORM\JoinColumn(name="coupon", referencedColumnName="id")
     */
    private $coupon;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;
}
