<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lang
 *
 * @ORM\Table(name="lang")
 * @ORM\Entity(repositoryClass="src\Repository\LangRepository")
 */
class Lang
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code_2a", type="string", length=2, nullable=true)
     */
    private $code2a = '';

    /**
     * @var string
     *
     * @ORM\Column(name="code_3a", type="string", length=3, nullable=true)
     */
    private $code3a = '';

    /**
     * @var string
     *
     * @ORM\Column(name="family", type="string", length=20, nullable=true)
     */
    private $family;

    /**
     * @var string
     *
     * @ORM\Column(name="iso_name", type="string", length=100, nullable=true)
     */
    private $isoName;

    /**
     * @var string
     *
     * @ORM\Column(name="folder", type="string", length=10, nullable=true)
     */
    private $folder;

    /**
     * @var string
     *
     * @ORM\Column(name="default", type="string", length=1, nullable=true, options={"default": 0})
     */
    private $default;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0, "comment"="0-no active 1-active 2- pre-active"})
     */
    private $active;
}
