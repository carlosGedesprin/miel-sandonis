<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Blog article
 *
 * @ORM\Table(name="blog_article_lang")
 * @ORM\Entity(repositoryClass="src\Repository\BlogArticleLangRepository")
 */
class BlogArticleLang
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
     * Many article langs belongs to an article
     * @ORM\ManyToOne(targetEntity="BlogArticle", inversedBy="langs")
     * @ORM\JoinColumn(name="article", referencedColumnName="id")
     */
    private $article;

    /**
     * @var string
     *
     * @ORM\Column(name="lang_code_2a", type="string", length=2, nullable=true)
     */
    private $lang_code_2a;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=100, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="metadescription", type="text", nullable=true)
     */
    private $metadescription;

    /**
     * @var string
     *
     * @ORM\Column(name="picture_alt_text", type="string", length=255, nullable=true)
     */
    private $picture_alt_text;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="faq_title", type="string", length=160, nullable=true)
     */
    private $faq_title;
}
