<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VilleRepository")
 */
class Ville
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Type("string", message="Le nom de la ville ne peut pas contenir de chiffre.")
     */
    private $nom;

    /**
     * @ORM\Column(type="integer", length=5)
     * @Assert\Regex(pattern = "/^[0-9]{5}$/", message="Le code postal doit être composé de 5 chiffres. ex: 44000")
     */
    private $codePostal;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lieu", mappedBy="ville")
     */
    private $lieus;

    public function __construct()
    {
        $this->lieus = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = strtoupper($nom);


        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * @return Collection|Lieu[]
     */
    public function getLieus(): Collection
    {
        return $this->lieus;
    }

    public function addLieus(Lieu $lieus): self
    {
        if (!$this->lieus->contains($lieus)) {
            $this->lieus[] = $lieus;
            $lieus->setVille($this);
        }

        return $this;
    }

    public function removeLieus(Lieu $lieus): self
    {
        if ($this->lieus->contains($lieus)) {
            $this->lieus->removeElement($lieus);
            // set the owning side to null (unless already changed)
            if ($lieus->getVille() === $this) {
                $lieus->setVille(null);
            }
        }

        return $this;
    }

    public function __toString(): ?string
    {
        return $this->getNom();
    }


}
