<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Session
 *
 * @ORM\Table(name="session")
 * @ORM\Entity(repositoryClass="src\Repository\SessionRepository")
 */
class Session
{
    /**
     * @var mixed
     *
     * @ORM\Column(name="sess_id", type="binary")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $sessId;

    /**
     * @var string
     *
     * @ORM\Column(name="sess_data", type="blob", nullable=true)
     */
    private $sessData;

    /**
     * @var int
     *
     * @ORM\Column(name="sess_time", type="integer", nullable=true)
     */
    private $sessTime;

    /**
     * @var int
     *
     * @ORM\Column(name="sess_lifetime", type="integer", nullable=true)
     */
    private $sessLifetime;
}
