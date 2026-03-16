<?php
namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="user_role")
 * @ORM\Entity(repositoryClass="src\Repository\UserRoleRepository")
 */
class UserRole
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
     * @ORM\Column(name="name_lang_key", type="string", length=30, nullable=true)
     */
    private $name_lang_key;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=20, nullable=true)
     */
    private $role;

    /**
     * One User Role have Many Users
     * 
     * @ORM\OneToMany(targetEntity="User", mappedBy="role")
     */
    private $users;
}
