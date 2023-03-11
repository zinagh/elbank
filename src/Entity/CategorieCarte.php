<?php

namespace App\Entity;

use App\Repository\CategorieCarteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CategorieCarteRepository::class)
 * @ORM\Table(name="categorieCarte", indexes={@ORM\Index(columns={"type", "description"}, flags={"fulltext"})})
 */
class CategorieCarte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups("post:read")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le description est requise")
     * @Groups("post:read")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     *  @Assert\NotBlank(message="Le prix est requise")
     * @Assert\Range(
     *      min = 20,
     *      max = 100,
     *     notInRangeMessage ="Le prix doit etre entre 20 et 100"
     * )
     * @Groups("post:read")
     */
    private $prix;

    /**
     * @ORM\Column(type="float")
     * @Groups("post:read")
     */
    private $montant_max;



    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date_categorie;

    /**
     * @ORM\ManyToMany(targetEntity=Carte::class, mappedBy="categorieCartes")
     */
    private $cartes;

    public function __construct()
    {
        $this->cartes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getMontantMax(): ?float
    {
        return $this->montant_max;
    }

    public function setMontantMax(float $montant_max): self
    {
        $this->montant_max = $montant_max;

        return $this;
    }



    public function getDateCategorie(): ?\DateTimeInterface
    {
        return $this->date_categorie;
    }

    public function setDateCategorie(?\DateTimeInterface $date_categorie): self
    {
        $this->date_categorie = $date_categorie;

        return $this;
    }

    /**
     * @return Collection<int, Carte>
     */
    public function getCartes(): Collection
    {
        return $this->cartes;
    }

    public function addCarte(Carte $carte): self
    {
        if (!$this->cartes->contains($carte)) {
            $this->cartes[] = $carte;
            $carte->addCategorieCarte($this);
        }

        return $this;
    }

    public function removeCarte(Carte $carte): self
    {
        if ($this->cartes->removeElement($carte)) {
            $carte->removeCategorieCarte($this);
        }

        return $this;
    }
    public function __toString()
    {
        return  $this->getType();
    }

}