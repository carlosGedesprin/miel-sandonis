<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Credential Type
 *
 * @ORM\Table(name="credential_type")
 * @ORM\Entity(repositoryClass="src\Repository\CredentialTypeRepository")
 */
class CredentialType
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
     * @ORM\Column(name="n8n_name", type="string", length=50, nullable=true)
     */
    private $n8n_name;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;
}
