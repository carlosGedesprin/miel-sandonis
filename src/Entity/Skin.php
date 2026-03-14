<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Skin
 *
 * @ORM\Table(name="skin")
 * @ORM\Entity(repositoryClass="src\Repository\SkinRepository")
 */
class Skin
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
     * @var string name
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string folder
     *
     * @ORM\Column(name="folder", type="string", length=100, nullable=true)
     */
    private $folder;

    /**
     * @var string
     *
     * @ORM\Column(name="default", type="string", length=1, options={"default": 0})
     */
    private $default;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;
}
