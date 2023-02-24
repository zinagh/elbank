<?php

namespace App\Entity;

use Andante\TimestampableBundle\Timestampable\TimestampableTrait;
use App\Repository\ChequesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ChequesRepository::class)
 */
class Cheques
{
    use TimestampableTrait;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;


    /**
     * @ORM\Column(type="float", length=255)
     * @Assert\Length( min = 3 , minMessage = "Veuillez saisir un montant >100")
     * @Assert\NotBlank(message="Veuillez saisir le montant *")
     * @Assert\Type(type="numeric", message="Le nombre ne doit pas contenir des caractères .")
     * @Groups("post:read")
     */
    private $montant;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="cheques")
     * @Groups("post:read")
     */
    private $proprietaire;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThanOrEqual("today", message="La date est incorrecte .")
     * @Groups("post:read")
     */

    private $date_cheque;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length( min = 4 , minMessage = "Veuillez saisir une addresse valide")
     * @Assert\NotBlank(message="Veuillez saisir le lieu*")
     * @Groups("post:read")
     */
    private $lieu;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length( max = 3 , maxMessage = "Veuillez saisir le Code")
     * @Assert\NotBlank(message=" Code obligatoire *")
     * @Groups("post:read")
     */
    private $signature;
    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="cheques")
     * @Groups("post:read")
     */
    private $destinataire;

    /**
     * @ORM\ManyToOne(targetEntity=Chequier::class, inversedBy="cheque")
     * @ORM\JoinColumn(nullable=true)
     */
    private $Carnets_cheques;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups("post:read")
     */
    private $client_tel;

    /**
     * @ORM\ManyToOne(targetEntity=Chequier::class, inversedBy="cheques")
     */
    private $idchequiers;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $RIB_Sender;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $RIB_Reciever;


    public function __construct()
    {
        $this->setDateCheque(new \DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdCheques(): ?int
    {
        return $this->id_cheques;
    }

    public function setIdCheques(int $id_cheques): self
    {
        $this->id_cheques = $id_cheques;

        return $this;
    }
    public function getClientTel(): ?int
    {
        return $this->client_tel;
    }

    public function setClientTel(?int $client_tel): self
    {
        $this->client_tel = $client_tel;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getProprietaire(): ?Compte
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?Compte $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    public function getDateCheque(): ?\DateTimeInterface
    {
        return $this->date_cheque;
    }

    public function setDateCheque(\DateTimeInterface $date_cheque): self
    {
        $this->date_cheque = $date_cheque;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(string $signature): self
    {
        $this->signature = $signature;

        return $this;
    }

    public function getDestinataire(): ?Compte
    {
        return $this->destinataire;
    }


    public function setDestinataire(?Compte $destinataire): self
    {
        $this->destinataire = $destinataire;

        return $this;
    }

    //  public function __toString()
    // {
    //  return (string) $this->getCarnet_cheques();
    // }


    public function __toString()
    {
        return (string)$this->getProprietaire();
        return (string)$this->getCarnetsCheques();

    }

    public function getCarnetsCheques(): ?Chequier
    {
        return $this->Carnets_cheques;
    }

    public function setCarnetsCheques(?Chequier $Carnets_cheques): self
    {
        $this->Carnets_cheques = $Carnets_cheques;

        return $this;
    }

    public function getIdchequiers(): ?Chequier
    {
        return $this->idchequiers;
    }

    public function setIdchequiers(?Chequier $idchequiers): self
    {
        $this->idchequiers = $idchequiers;

        return $this;
    }

    public function getRIBSender(): ?string
    {
        return $this->RIB_Sender;
    }

    public function setRIBSender(string $RIB_Sender): self
    {
        $this->RIB_Sender = $RIB_Sender;

        return $this;
    }

    public function getRIBReciever(): ?string
    {
        return $this->RIB_Reciever;
    }

    public function setRIBReciever(string $RIB_Reciever): self
    {
        $this->RIB_Reciever = $RIB_Reciever;

        return $this;
    }

}