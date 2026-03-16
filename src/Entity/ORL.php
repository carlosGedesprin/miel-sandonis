<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * ORL
 *
 * @ORM\Table(name="orl")
 * @ORM\Entity(repositoryClass="src\Repository\ORLRepository")
 */
class ORL
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
     * @var int
     *
     * @ORM\Column(name="createdby", type="integer", nullable=true)
     */
    private $createdby;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="createddate", type="datetime", nullable=true)
     */
    private $createddate;

    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="string", length=255, nullable=true)
     */
    private $entity;

    /**
     * @var string
     *
     * @ORM\Column(name="old", type="text", nullable=true)
     */
    private $old;

    /**
     * @var string
     *
     * @ORM\Column(name="new", type="text", nullable=true)
     */
    private $new;
}
