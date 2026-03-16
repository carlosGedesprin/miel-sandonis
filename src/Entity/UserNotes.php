<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * UserNotes
 *
 * @ORM\Table(name="user_notes")
 * @ORM\Entity(repositoryClass="src\Repository\UserNotesRepository")
 */
class UserNotes
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
     * One user has one user notes
     *
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="CASCADE")
     */
    private $user;

    /**
     * Many Notes have One Group.
     *
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="user_notes")
     * @ORM\JoinColumn(name="group", referencedColumnName="id")
     * @ORM\Column(options={"comment":"3-Staff 4-Customer 5-Agent 6-Supervisor 7-Verificator L1 8-Verificator L2"}))
     *
     */
    private $group;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

}
