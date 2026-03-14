<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Credential
 *
 * @ORM\Table(name="credential")
 * @ORM\Entity(repositoryClass="src\Repository\CredentialRepository")
 */
class Credential
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
     * @ORM\Column(name="credential_key", type="string", length=32, unique=true, nullable=true)
     */
    private $credential_key;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * Many credentials has One account
     *
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumn(name="account", referencedColumnName="id", onDelete="CASCADE")
     */
    private $account;

    /**
     * Many Credential have credential type.
     *
     * @ORM\ManyToOne(targetEntity="CredentialType", inversedBy="credentials")
     * @ORM\JoinColumn(name="credential_type", referencedColumnName="id", nullable=true)
     *
     */
    private $credential_type;

    /**
     * @var string
     *
     * @ORM\Column(name="n8n_id", type="string", length=50, nullable=true)
     */
    private $n8n_id;

    /**
     * @var string
     *
     * @ORM\Column(name="n8n_name", type="string", length=50, nullable=true)
     */
    private $n8n_name;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;
}
