<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * AccountNotes
 *
 * @ORM\Table(name="account_notes")
 * @ORM\Entity(repositoryClass="src\Repository\AccountNotesRepository")
 */
class AccountNotes
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
     * One account has many account notes
     *
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumn(name="account", referencedColumnName="id", onDelete="CASCADE")
     */
    private $account;

    /**
     * Many Notes have One Group.
     *
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="account_notes")
     * @ORM\JoinColumn(name="group", referencedColumnName="id")
     * @ORM\Column(nullable=true, options={"comment":"3-Staff 4-Customer 5-Agent 6-Integrator 7-PublicSector 8-Verificator"}))
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
