<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Warm IP Account
 *
 * @ORM\Table(name="n8n_warm_ip_account")
 * @ORM\Entity(repositoryClass="src\Repository\N8NWarmIPAccountRepository")
 */
class N8NWarmIPAccount
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
     * @ORM\Column(name="email", type="string", length=191, nullable=true)
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;
}
