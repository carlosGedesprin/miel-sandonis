<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * UserProfile
 *
 * @ORM\Table(name="user_profile")
 * @ORM\Entity(repositoryClass="src\Repository\UserProfileRepository")
 */
class UserProfile
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
     * One user profile has one user
     *
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

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
     * @ORM\Column(name="photo", type="string", length=50, nullable=true)
     */
    private $photo;

}
