<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Credential data
 *
 * @ORM\Table(name="credential_data")
 * @ORM\Entity(repositoryClass="src\Repository\CredentialDataRepository")
 */
class CredentialData
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
     * One credential have many data
     *
     * @ORM\ManyToOne(targetEntity="Credential", inversedBy="data")
     * @ORM\JoinColumn(name="credential", referencedColumnName="id")
     */
    private $credential;

    /**
     * @var string
     *
     * @ORM\Column(name="field_name", type="string", length=100, nullable=true)
     */
    private $field_name;

    /**
     * @var string
     *
     * @ORM\Column(name="field_value", type="string", length=100, nullable=true)
     */
    private $field_value;
}
