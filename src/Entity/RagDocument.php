<?php

namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rag document
 *
 * @ORM\Table(name="rag_document")
 * @ORM\Entity(repositoryClass="src\Repository\RagDocumentsRepository")
 */
class RagDocument
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
     * @ORM\Column(name="rag_document_key", type="string", length=32, nullable=true)
     */
    private $rag_document_key;

    /**
     * @ORM\ManyToOne(targetEntity="Rag")
     * @ORM\JoinColumn(name="rag", referencedColumnName="id", nullable=true)
     */
    private $rag;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="text", nullable=true)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=25, nullable=true)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="date_reg", type="datetime", nullable=true)
     */
    private $dateReg;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="string", length=1, options={"comment":"0-Pending 1-Ready", "default": 0})
     */
    private $status;
}
