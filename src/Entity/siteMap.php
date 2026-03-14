<?php
namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="site_map")
 * @ORM\Entity(repositoryClass="src\Repository\siteMapRepository")
 */
class siteMap
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="page_title", type="string", length=255, nullable=true)
     */
    private $page_title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="subdomain", type="string", length=50, nullable=true)
     */
    private $subdomain;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="changefreg", type="string", length=10, nullable=true)
     */
    private $changefreg;

    /**
     * @var string
     *
     * @ORM\Column(name="priority", type="string", length=5, nullable=true)
     */
    private $priority;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createddate", type="date", nullable=true)
     */
    private $createddate;

    /**
     * @var string
     *
     * @ORM\Column(name="active", type="string", length=1, nullable=true)
     */
    private $active;
}
