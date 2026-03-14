<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bot
 *
 * @ORM\Table(name="blog_author")
 * @ORM\Entity(repositoryClass="src\Repository\BlogAuthorRepository")
 */
class BlogAuthor
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
     * @var string
     *
     * @ORM\Column(name="metadescription", type="text", nullable=true)
     */
    private $metadescription;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=100, nullable=true)
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="picture_alt_text", type="string", length=255, nullable=true)
     */
    private $picture_alt_text;

    /**
     * @var string
     *
     * @ORM\Column(name="linkedin", type="string", length=200, nullable=true)
     */
    private $linkedin;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="string", length=1, options={"default": 0})
     */
    private $active;
}
