<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * MailQueue
 *
 * @ORM\Table(name="mail_queue")
 * @ORM\Entity(repositoryClass="src\Repository\MailQueueRepository")
 */
class MailQueue
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
     * @var \DateTime|null
     *
     * @ORM\Column(name="send", type="datetime", nullable=true)
     */
    private $send;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer", nullable=true)
     */
    private $priority = 3;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="sent", type="datetime", nullable=true)
     */
    private $sent = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="to_address", type="string", length=100, nullable=true)
     */
    private $toAddress = '';

    /**
     * @var string
     *
     * @ORM\Column(name="to_name", type="string", length=100, nullable=true)
     */
    private $toName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="cc_address", type="string", length=100, nullable=true)
     */
    private $ccAddress = '';

    /**
     * @var string
     *
     * @ORM\Column(name="cc_name", type="string", length=100, nullable=true)
     */
    private $ccName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="bcc_address", type="string", length=100, nullable=true)
     */
    private $bccAddress = '';

    /**
     * @var string
     *
     * @ORM\Column(name="bcc_name", type="string", length=100, nullable=true)
     */
    private $bccName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="from_address", type="string", length=100, nullable=true)
     */
    private $fromAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="from_name", type="string", length=100, nullable=true)
     */
    private $fromName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="string", length=50, nullable=true)
     */
    private $template;

    /**
     * @var string
     *
     * @ORM\Column(name="process", type="string", length=500, nullable=true)
     */
    private $process;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="pre_header", type="string", length=255, nullable=true)
     */
    private $preHeader = '';

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=10, nullable=true)
     */
    private $locale = '';

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="headers", type="string", length=255, nullable=true)
     */
    private $headers = '';

    /**
     * @var string
     *
     * @ORM\Column(name="images", type="text", nullable=true)
     */
    private $images = '';

    /**
     * @var string
     *
     * @ORM\Column(name="assign_vars", type="text", nullable=true)
     */
    private $assignVars = '';

    /**
     * @var string
     *
     * @ORM\Column(name="block_name", type="string", length=100, nullable=true)
     */
    private $blockName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="assign_block_vars", type="text", nullable=true)
     */
    private $assignBlockVars = '';

    /**
     * @var string
     *
     * @ORM\Column(name="attached", type="text", nullable=true)
     */
    private $attached = '';

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=10, nullable=true)
     */
    private $token = '';
}
