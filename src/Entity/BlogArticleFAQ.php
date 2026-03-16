<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Blog article FAQ's
 *
 * @ORM\Table(name="blog_article_faq")
 * @ORM\Entity(repositoryClass="src\Repository\BlogArticleFAQRepository")
 */
class BlogArticleFAQ
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
     * One article has many FAQ's
     *
     * @ORM\ManyToOne(targetEntity="BlogArticle")
     * @ORM\JoinColumn(name="article", referencedColumnName="id", onDelete="CASCADE")
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
     * @ORM\Column(name="question", type="string", length=250, nullable=true)
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="reply", type="text", nullable=true)
     */
    private $reply;

    /**
     * @var int
     *
     * @ORM\Column(name="ordinal", type="integer", nullable=true)
     */
    private $ordinal;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;
}
