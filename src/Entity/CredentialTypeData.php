<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Credential Type data
 *
 * @ORM\Table(name="credential_type_data")
 * @ORM\Entity(repositoryClass="src\Repository\CredentialTypeDataRepository")
 */
class CredentialTypeData
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
     * One credential type have many data
     *
     * @ORM\ManyToOne(targetEntity="CredentialType", inversedBy="data")
     * @ORM\JoinColumn(name="credential_type", referencedColumnName="id")
     */
    private $credential_type;

    /**
     * @var string
     *
     * @ORM\Column(name="field", type="string", length=100, nullable=true)
     */
    private $field;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;
}
