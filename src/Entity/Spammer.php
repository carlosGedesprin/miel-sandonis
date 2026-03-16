<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Spammer
 *
 * @ORM\Table(name="spammer")
 * @ORM\Entity(repositoryClass="src\Repository\spammerRepository")
 */
class Spammer
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
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="remote_addr", type="string", length=40, nullable=true)
     */
    private $remote_addr;


    /**
     * @var string
     *
     * @ORM\Column(name="http_x_forwarded_for", type="string", length=40, nullable=true)
     */
    private $http_x_forwarded_for;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;
}
