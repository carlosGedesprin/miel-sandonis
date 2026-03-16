<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * PaymentType
 *
 * @ORM\Table(name="payment_type")
 * @ORM\Entity(repositoryClass="src\Repository\PaymentTypeRepository")
 */
class PaymentType
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
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="script", type="string", length=50, nullable=true)
     */
    private $script;

    /**
     * @var string
     *
     * @ORM\Column(name="method", type="string", length=20, nullable=true, options={"comment":"online, bank_tranfer"})
     */
    private $method;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=50, nullable=true)
     */
    private $image;

    /**
     * @var int
     *
     * @ORM\Column(name="ordinal", type="integer", nullable=true)
     */
    private $ordinal;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;
}
