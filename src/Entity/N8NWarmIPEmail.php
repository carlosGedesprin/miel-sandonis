<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * n8n Warm IP emails
 *
 * @ORM\Table(name="n8n_warm_ip_email")
 * @ORM\Entity(repositoryClass="src\Repository\N8NWarmIPEmailRepository")
 */
class N8NWarmIPEmail
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
     * One n8n warm IP account has many emails
     *
     * @ORM\ManyToOne(targetEntity="N8NWarmIPAccount")
     * @ORM\JoinColumn(name="account", referencedColumnName="id", onDelete="CASCADE")
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="date_sent", type="datetime", nullable=true)
     */
    private $dateSent;

    /**
     * @var string
     *
     * @ORM\Column(name="warm_email", type="string", length=250, nullable=true)
     */
    private $warm_email;

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
