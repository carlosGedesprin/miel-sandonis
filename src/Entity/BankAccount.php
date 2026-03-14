<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bank account
 *
 * @ORM\Table(name="bank_account")
 * @ORM\Entity(repositoryClass="src\Repository\bankAccountRepository")
 */
class BankAccount
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
     * @ORM\Column(name="iban", type="string", length=10, nullable=true)
     */
    private $iban;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=50, nullable=true)
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="default", type="string", length=1, options={"default": 0})
     */
    private $default;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;
}
