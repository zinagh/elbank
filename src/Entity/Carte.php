<?php

namespace App\Entity;

use App\Repository\CarteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Validator\Constraints as Assert;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CarteRepository::class)
 * @Vich\Uploadable
 */
class Carte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     * @Groups("post:read")
     */
    private $idclient;

    /**
     * @ORM\Column(type="date")
     * @Groups("post:read")
     */
    private $date_ex;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length( min = 3, max = 20, minMessage = "Merci de Vérifier Votre mot de passe")
     * @Assert\NotBlank(message="Le champs mot de paase est obligatoire * ")
     * @Groups("post:read")
     */
    private $mp;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length( min = 3, max = 20, minMessage = "Merci de Vérifier Votre login")
     * @Assert\NotBlank(message="Le champs login est obligatoire * ")
     * @Groups("post:read")
     */
    private $login;

    /**
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $num_carte;


    /**
     * @ORM\Embedded(class="Vich\UploaderBundle\Entity\File")
     * @Groups("post:read")
     */
    private $Picture;

    /**
     * @Vich\UploadableField(
     *   mapping="product_image",
     *   fileNameProperty="Picture.name"
     * )
     * @var File|null
     */
    private ?File $PictureFile = null;

    /**
     * @ORM\ManyToMany(targetEntity=CategorieCarte::class, inversedBy="cartes")
     */
    private $categorieCartes;

    public function __construct()
    {
        $this->Picture = new EmbeddedFile();
        $this->categorieCartes = new ArrayCollection();
    }

    public function setPictureFile(?File $Picture = null): void
    {
        $this->PictureFile = $Picture;
        if ($Picture !== null) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getPicture(): ?EmbeddedFile
    {
        return $this->Picture;
    }

    public function setPicture(?EmbeddedFile $Picture)
    {
        $this->Picture = $Picture;

        return $this;
    }

    public function getPictureFile(): ?File
    {
        return $this->PictureFile;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdclient(): ?float
    {
        return $this->idclient;
    }

    public function setIdclient(float $idclient): self
    {
        $this->idclient = $idclient;

        return $this;
    }

    public function getDateEx(): ?\DateTimeInterface
    {
        return $this->date_ex;
    }

    public function setDateEx(\DateTimeInterface $date_ex): self
    {
        $this->date_ex = $date_ex;

        return $this;
    }

    public function getMp(): ?string
    {
        return $this->mp;
    }

    public function setMp(string $mp): self
    {
        $this->mp = $mp;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getNumCarte(): ?int
    {
        return $this->num_carte;
    }

    public function setNumCarte(int $num_carte): self
    {
        $this->num_carte = $num_carte;

        return $this;
    }

    /**
     * @return Collection<int, CategorieCarte>
     */
    public function getCategorieCartes(): Collection
    {
        return $this->categorieCartes;
    }

    public function addCategorieCarte(CategorieCarte $categorieCarte): self
    {
        if (!$this->categorieCartes->contains($categorieCarte)) {
            $this->categorieCartes[] = $categorieCarte;
        }

        return $this;
    }

    public function removeCategorieCarte(CategorieCarte $categorieCarte): self
    {
        $this->categorieCartes->removeElement($categorieCarte);

        return $this;
    }


    public function __toString()
    {
        return (String) $this->getNumCarte();
    }




}