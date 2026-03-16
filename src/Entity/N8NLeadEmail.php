<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lead n8n emails
 *
 * @ORM\Table(name="n8n_lead_email")
 * @ORM\Entity(repositoryClass="src\Repository\LeadN8NEmailRepository")
 */
class N8NLeadEmail
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
     * One Lead n8n has many emails
     *
     * @ORM\ManyToOne(targetEntity="N8NLead")
     * @ORM\JoinColumn(name="lead", referencedColumnName="id", onDelete="CASCADE")
     */
    private $lead_n8n;

    /**
     * @var string
     *
     * @ORM\Column(name="date_sent", type="datetime", nullable=true)
     */
    private $dateSent;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=250, nullable=true)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text", nullable=true)
     */
    private $body;

}
