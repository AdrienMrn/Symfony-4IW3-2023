<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'users')]
    private Collection $categoriesFavorites;

    #[ORM\ManyToMany(targetEntity: Movie::class, inversedBy: 'users')]
    private Collection $moviesFavorites;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Review::class, orphanRemoval: true)]
    private Collection $reviews;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Watch::class, orphanRemoval: true)]
    private Collection $watches;

    public function __construct()
    {
        $this->categoriesFavorites = new ArrayCollection();
        $this->moviesFavorites = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->watches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategoriesFavorites(): Collection
    {
        return $this->categoriesFavorites;
    }

    public function addCategoriesFavorite(Category $categoriesFavorite): static
    {
        if (!$this->categoriesFavorites->contains($categoriesFavorite)) {
            $this->categoriesFavorites->add($categoriesFavorite);
        }

        return $this;
    }

    public function removeCategoriesFavorite(Category $categoriesFavorite): static
    {
        $this->categoriesFavorites->removeElement($categoriesFavorite);

        return $this;
    }

    /**
     * @return Collection<int, Movie>
     */
    public function getMoviesFavorites(): Collection
    {
        return $this->moviesFavorites;
    }

    public function addMoviesFavorite(Movie $moviesFavorite): static
    {
        if (!$this->moviesFavorites->contains($moviesFavorite)) {
            $this->moviesFavorites->add($moviesFavorite);
        }

        return $this;
    }

    public function removeMoviesFavorite(Movie $moviesFavorite): static
    {
        $this->moviesFavorites->removeElement($moviesFavorite);

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setOwner($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getOwner() === $this) {
                $review->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Watch>
     */
    public function getWatches(): Collection
    {
        return $this->watches;
    }

    public function addWatch(Watch $watch): static
    {
        if (!$this->watches->contains($watch)) {
            $this->watches->add($watch);
            $watch->setOwner($this);
        }

        return $this;
    }

    public function removeWatch(Watch $watch): static
    {
        if ($this->watches->removeElement($watch)) {
            // set the owning side to null (unless already changed)
            if ($watch->getOwner() === $this) {
                $watch->setOwner(null);
            }
        }

        return $this;
    }
}
