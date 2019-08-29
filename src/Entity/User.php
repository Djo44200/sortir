<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Le champ email ne peut pas être vide !")
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Le champ pseudo ne peut pas être vide !")
     * @ORM\Column(type="string", length=180, nullable=true)
     * @return mixed
     */

    private $pseudo;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;


    /**
     * @var string
     * @Assert\NotBlank(message="Le champ mot de passe ne peut pas être vide !")
     */
    private $plainPassword;

    /**
     * @var string
     *
     * @SecurityAssert\UserPassword(
     *     message = "USER_OLD_PASSWORD_INVALID_DATA",
     *     groups={"password"}
     * )
     */
    protected $oldPassword;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $telephone;


    /**
     * @var string
     * @ORM\Column(name="img", type="string", nullable=true)
     * @Assert\File(
     *     mimeTypes={"image/png" ,"image/jpg","image/jpeg"},
     *     mimeTypesMessage = "Svp inserer une image valide (png,jpg,jpeg)")
     */
    private $img;

    /**
     * @ORM\Column(type="binary")
     *
     */
    private $actif;

    /**
     * @ManyToOne(targetEntity="App\Entity\Site", inversedBy="users")
     * @JoinColumn(name="site_id", referencedColumnName="id")
     */
    private $site;

    /**
     * @OneToMany(targetEntity="App\Entity\Inscription", mappedBy="participant")
     */
    private $inscriptions;

    public function __construct()
    {
        // Roles des utilisateurs
        $this->roles = ['ROLE_USER'];
        $this->actif = 1;

    }

    /**
     * @return mixed
     */
    public function getSite(): ?Site
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }


    public function getPseudo()
    {
        return $this->pseudo;
    }


    /**
     * @param mixed $pseudo
     */
    public function setPseudo($pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */

    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $roles = $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }


    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }
    /**
     * @param string $plainPassword
     */
    /**
     * @param string|null $plainPassword
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return string
     */
    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    /**
     * @param string $oldPassword
     */
    public function setOldPassword(string $oldPassword): void
    {
        $this->oldPassword = $oldPassword;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getActif()
    {
        return $this->actif;
    }

    public function setActif($actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVilleRattachement()
    {
        return $this->villeRattachement;
    }

    /**
     * @param mixed $villeRattachement
     */
    public function setVilleRattachement($villeRattachement): void
    {
        $this->villeRattachement = $villeRattachement;
    }

    /**
     * @return string
     */
    public function getImg(): ?string
    {
        return $this->img;
    }

    /**
     * @param string $img
     */
    public function setImg(string $img): void
    {
        $this->img = $img;
    }


    public function __toString(): ?string
    {

        $sb = $this->getPrenom() . " " . $this->getNom();

        return $sb;

    }
}
