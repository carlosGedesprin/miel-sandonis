<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * LangText
 *
 * @ORM\Table(name="lang_text_name")
 * @ORM\Entity(repositoryClass="src\Repository\LangTextRepository")
 */
class LangTextName
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
     * One LangText has many LangTextName
     *
     * @ORM\ManyToOne(targetEntity="LangText")
     * @ORM\JoinColumn(name="lang_text", referencedColumnName="id", onDelete="CASCADE")
     */
    private $lang_text;

    /**
     * @var string
     *
     * @ORM\Column(name="lang_code_2a", type="string", length=2, nullable=true)
     */
    private $lang_code_2a;


    /**
     * @var string
     *
     * @ORM\Column(name="lang_variant", type="string", length=2, nullable=true)
     */
    private $lang_variant;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;
}
