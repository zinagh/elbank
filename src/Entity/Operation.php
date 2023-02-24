<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Operation
 *
 * @ORM\Table(name="operation")
 * @ORM\Entity
 */
class Operation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="id_operation", type="integer", nullable=false)
     */
    private $idOperation;

    /**
     * @var float
     *
     * @ORM\Column(name="montant", type="float", precision=10, scale=0, nullable=false)
     */
    private $montant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_operation", type="date", nullable=false)
     */
    private $dateOperation;

    /**
     * @var string
     *
     * @ORM\Column(name="type_c", type="string", length=100, nullable=false)
     */
    private $typeC;

    /**
     * @var float
     *
     * @ORM\Column(name="depense_hebdomadaire", type="float", precision=10, scale=0, nullable=false)
     */
    private $depenseHebdomadaire;


}
