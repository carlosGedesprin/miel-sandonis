<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * LangName
 *
 * @ORM\Table(name="lang_name")
 * @ORM\Entity
 */
class LangName
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lang_code_2a", type="string", length=2, nullable=true)
     */
    private $langCode2a;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lang_2a", type="string", length=2, nullable=true)
     */
    private $lang_2a;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=500, nullable=true)
     */
    private $name;
}
