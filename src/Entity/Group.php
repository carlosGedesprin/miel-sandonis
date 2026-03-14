<?php
namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="group")
 * @ORM\Entity(repositoryClass="src\Repository\GroupRepository")
 */
class Group
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
     * @ORM\Column(name="name", type="string", length=30, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="show_to_staff", type="string", length=1, options={"comment":"With 0 only admin can see it", "default": 1}))
     */
    private $show_to_staff;

    /**
     * @var string
     *
     * @ORM\Column(name="folder", type="string", length=20, nullable=true)
     */
    private $folder;

    /**
     * One Groups have Many Accounts
     * 
     * @ORM\OneToMany(targetEntity="Account", mappedBy="group")
     */
    private $accounts;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();   
        $this->contacts = new ArrayCollection();
    }
}
