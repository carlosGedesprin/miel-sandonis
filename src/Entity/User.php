<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="src\Repository\UserRepository")
 */
class User
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
     * @var integer
     *
     * @ORM\Column(name="account", type="integer", length=11, nullable=true)
     */
    private $account;

    /**
     * Many Users have One Role.
     *
     * @ORM\ManyToOne(targetEntity="UserRole", inversedBy="users")
     * @ORM\JoinColumn(name="role", referencedColumnName="id", nullable=true)
     *
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="user_key", type="string", length=32, unique=true, nullable=true)
     */
    private $user_key;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=25, nullable=true)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, nullable=true)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=191, unique=true, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="lastlogin", type="datetime", nullable=true)
     */
    private $lastLogin;

    /**
     * @var string
     *
     * @ORM\Column(name="attempt", type="string", length=15, nullable=true)
     */
    private $attempt;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=2, nullable=true)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="activation_key", type="string", length=10, nullable=true)
     */
    private $activationKey;

    /**
     * @var string
     *
     * @ORM\Column(name="change_password_key", type="string", length=10, nullable=true)
     */
    private $changePasswordKey;

    /**
     * @var string
     *
     * @ORM\Column(name="show_to_staff", type="string", length=1, options={"comment":"With 0 only admin can see it", "default": 1}))
     */
    private $show_to_staff;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;
}
