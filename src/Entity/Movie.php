<?php

namespace App\Entity;

use App\Repository\MovieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MovieRepository::class)]
class Movie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'movies')]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: Review::class, orphanRemoval: true)]
    private Collection $reviews;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'moviesFavorites')]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'movie', targetEntity: Watch::class, orphanRemoval: true)]
    private Collection $watches;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->watches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

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
            $review->setMovie($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getMovie() === $this) {
                $review->setMovie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addMoviesFavorite($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeMoviesFavorite($this);
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
            $watch->setMovie($this);
        }

        return $this;
    }

    public function removeWatch(Watch $watch): static
    {
        if ($this->watches->removeElement($watch)) {
            // set the owning side to null (unless already changed)
            if ($watch->getMovie() === $this) {
                $watch->setMovie(null);
            }
        }

        return $this;
    }
}
