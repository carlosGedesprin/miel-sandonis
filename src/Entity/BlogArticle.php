<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Blog article
 *
 * @ORM\Table(name="blog_article")
 * @ORM\Entity(repositoryClass="src\Repository\BlogArticleRepository")
 */
class BlogArticle
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
     * One category has many articles
     *
     * @ORM\ManyToOne(targetEntity="BlogCategory")
     * @ORM\JoinColumn(name="category", referencedColumnName="id", onDelete="CASCADE")
     */
    private $category;

    /**
     * @var int
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * One author has many articles
     *
     * @ORM\ManyToOne(targetEntity="BlogAuthor")
     * @ORM\JoinColumn(name="author", referencedColumnName="id", onDelete="CASCADE")
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="picture_thumb", type="string", length=100, nullable=true)
     */
    private $picture_thumb;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=100, nullable=true)
     */
    private $picture;

    /**
     * @var int
     *
     * @ORM\Column(name="ordinal", type="integer", nullable=true)
     */
    private $ordinal;

    /**
     * @var int
     *
     * @ORM\Column(name="visits", type="integer", nullable=true)
     */
    private $visits;

    /**
     * @var bool
     *
     * @ORM\Column(name="featured", type="string", length=1, options={"default": 0})
     */
    private $featured;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;
}
