<?php
namespace src\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Cron
 *
 * @ORM\Table(name="cron")
 * @ORM\Entity
 */
class Cron
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="process", type="string", length=50, nullable=true)
     */
    private $process;

    /**
     * @var bool
     *
     * @ORM\Column(name="run", type="boolean", nullable=true)
     */
    private $run;

    /**
     * @var string
     *
     * @ORM\Column(name="periodicity", type="string", length=15, nullable=true, options={"comment":"minute hour day webcron"}))
     */
    private $periodicity;

    /**
     * @var int
     *
     * @ORM\Column(name="size", type="integer", nullable=true)
     */
    private $size;

    /**
     * @var int
     *
     * @ORM\Column(name="delaytime", type="integer", nullable=true)
     */
    private $delaytime;

    /**
     * @var int
     *
     * @ORM\Column(name="ordinal", type="integer", nullable=true)
     */
    private $ordinal;

    /**
     * @var string
     *
     * @ORM\Column(name="last_run", type="datetime", nullable=true)
     */
    private $lastRun;
}
