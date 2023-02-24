<?php

namespace App\Entity;

use App\Repository\CreditRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;



/**
 * @ORM\Entity(repositoryClass=CreditRepository::class)
 */
class Credit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le montant du crédit est requis")
     * @Groups("post:read")
     */
    private $montCredit;

    /**
     * @var \date
     * @Assert\NotBlank()
     * @ORM\Column(type="date")
     * @Groups("post:read")
     */
    private $datepe;

    /**
     * @var \date
     * @Assert\NotBlank()
     * @ORM\Column(type="date")
     * @Assert\GreaterThan(propertyPath="datepe")
     * @Groups("post:read")
     */
    private $datede;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le durée est requise")
     * @Assert\Range(
     *      min = 1,
     *      max = 10,
     *     notInRangeMessage ="La durée doit etre entre 1 et 10"
     * )
     * @Groups("post:read")
     */
    private $dureeC;

    /**
     *  * @var \date
     * @ORM\Column(type="date")
     * @Assert\GreaterThan(propertyPath="datepe")
     * @Groups("post:read")
     */
    private $echeance;

    /**
     *
     * @ORM\Column(type="integer",)
     *  @ORM\GeneratedValue(strategy="AUTO")
     * @Groups("post:read")
     */
    private $tauxInteret;

    /**
     * @ORM\Column(type="boolean")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups("post:read")
     */
    private $decision;

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Groups("post:read")
     */
    private $etatCredit;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le type du crédit est requis")
     * @Groups("post:read")
     */
    private $typeCredit;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="credits")
     * @Groups("post:read")
     */
    private $numero_compte;

    /**
     * @ORM\OneToMany(targetEntity=OperationCredit::class, mappedBy="credit", orphanRemoval=true)
     */
    private $OperationCredits;


    public function __construct()
    {
        $this->tauxInteret    = 1;
        $this->decision    = false;
        $this->etatCredit    = 'encours';

        $this->Credit = new ArrayCollection();
        $this->OperationCredits = new ArrayCollection();


    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontCredit(): ?int
    {
        return $this->montCredit;
    }

    public function setMontCredit(int $montCredit): self
    {
        $this->montCredit = $montCredit;

        return $this;
    }

    public function getDatepe(): ?\DateTimeInterface
    {
        return $this->datepe;
    }

    public function setDatepe(\DateTimeInterface $datepe): self
    {
        $this->datepe = $datepe;

        return $this;
    }

    public function getDatede(): ?\DateTimeInterface
    {
        return $this->datede;
    }

    public function setDatede(\DateTimeInterface $datede): self
    {
        $this->datede = $datede;

        return $this;
    }

    public function getDureeC(): ?int
    {
        return $this->dureeC;
    }

    public function setDureeC(int $dureeC): self
    {
        $this->dureeC = $dureeC;

        return $this;
    }

    public function getEcheance(): ?\DateTimeInterface
    {
        return $this->echeance;
    }

    public function setEcheance(\DateTimeInterface $echeance): self
    {
        $this->echeance = $echeance;

        return $this;
    }

    public function getTauxInteret(): ?int
    {
        return $this->tauxInteret;
    }

    public function setTauxInteret(int $tauxInteret): self
    {
        $this->tauxInteret = $tauxInteret;

        return $this;
    }

    public function getDecision(): ?bool
    {
        return $this->decision;
    }

    public function setDecision(bool $decision): self
    {
        $this->decision = $decision;

        return $this;
    }

    public function getEtatCredit(): ?string
    {
        return $this->etatCredit;
    }

    public function setEtatCredit(string $etatCredit): self
    {
        $this->etatCredit = $etatCredit;

        return $this;
    }

    public function getTypeCredit(): ?string
    {
        return $this->typeCredit;
    }

    public function setTypeCredit(string $typeCredit): self
    {
        $this->typeCredit = $typeCredit;

        return $this;
    }

    public function __toString():string
    {
        return $this->id;
    }


    public function getNumeroCompte(): ?Compte
    {
        return $this->numero_compte;
    }

    public function setNumeroCompte(?Compte $numero_compte): self
    {
        $this->numero_compte = $numero_compte;

        return $this;
    }


    public function getCompleteDecision()
    {
        $expr = Criteria::expr();
        $criteria = Criteria::create();

        $criteria->where($expr->eq('decision', true));

        return $this->getDecision()->matching($criteria);
    }

    public function getNumberOfCredit()
    {
        return $this->getDecision()->count();
    }

    public function getPercentComplete()
    {
        $percentage = 0;
        $totalSize = $this->getNumberOfCredit();
        if ($totalSize>0)
        {
            $completedSize = $this->getCompleteDecision()->count();
            $percentage =  $completedSize / $totalSize * 100;
        }

        return $percentage;
    }

    /**
     * @return Collection<int, OperationCredit>
     */
    public function getOperationCredits(): Collection
    {
        return $this->OperationCredits;
    }

    public function addOperationCredit(OperationCredit $operationCredit): self
    {
        if (!$this->OperationCredits->contains($operationCredit)) {
            $this->OperationCredits[] = $operationCredit;
            $operationCredit->setCredit($this);
        }

        return $this;
    }

    public function removeOperationCredit(OperationCredit $operationCredit): self
    {
        if ($this->OperationCredits->removeElement($operationCredit)) {
            // set the owning side to null (unless already changed)
            if ($operationCredit->getCredit() === $this) {
                $operationCredit->setCredit(null);
            }
        }

        return $this;
    }


}
