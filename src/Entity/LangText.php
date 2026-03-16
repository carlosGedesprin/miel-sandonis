<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * LangText
 *
 * @ORM\Table(name="lang_text")
 * @ORM\Entity(repositoryClass="src\Repository\LangTextRepository")
 */
class LangText
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
     * @ORM\Column(name="lang_key", type="string", length=50, nullable=true)
     */
    private $lang_key;

    /**
     * @var string
     *
     * @ORM\Column(name="context", type="string", length=50, nullable=true)
     */
    private $context;

}
