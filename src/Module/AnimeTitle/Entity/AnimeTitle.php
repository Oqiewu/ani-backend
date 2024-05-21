<?php

namespace App\Module\AnimeTitle\Entity;

use App\Repository\AnimeTitleRepository;
use Doctrine\ORM\Mapping as ORM;

use App\Module\AnimeTitle\Enum\AnimeTitleGenre;
use App\Module\AnimeTitle\Enum\AnimeTitleType;
use App\Module\AnimeTitle\Enum\AnimeTitleStatus;
use App\Module\AnimeTitle\Enum\AgeRating;

#[ORM\Entity(repositoryClass: AnimeTitleRepository::class)]
class AnimeTitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: false)]
    private ?string $originalName = null;

    #[ORM\Column(length: 500, nullable: false)]
    private ?string $description = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $genres = [];

    #[ORM\Column(type: 'string', enumType: AnimeTitleType::class, nullable: false)]
    private ?AnimeTitleType $type = null;

    #[ORM\Column(type: 'string', enumType: AnimeTitleStatus::class, nullable: false)]
    private ?AnimeTitleStatus $status = null;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTime $releaseDate = null;

    #[ORM\Column(type: 'string', enumType: AgeRating::class, nullable: false)]
    private ?AgeRating $ageRating = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): static
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
    public function getGenres(): ?array
    {
        return $this->genres;
    }

    public function setGenres(?array $genres): self
    {
        $this->genres = $genres;
        return $this;
    }
    
    public function addGenre(AnimeTitleGenre $genre): self
    {
        if (!in_array($genre, $this->genres, true)) {
            $this->genres[] = $genre;
        }
        return $this;
    }
    
    public function removeGenre(AnimeTitleGenre $genre): self
    {
        $key = array_search($genre, $this->genres, true);
        if ($key !== false) {
            unset($this->genres[$key]);
        }
        return $this;
    }

    public function getType(): ?AnimeTitleType
    {
        return $this->type;
    }

    public function setType(AnimeTitleType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?AnimeTitleStatus
    {
        return $this->status;
    }

    public function setStatus(AnimeTitleStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getReleaseDate(): ?\DateTime
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTime $releaseDate): static
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getAgeRating(): ?AgeRating
    {
        return $this->ageRating;
    }

    public function setAgeRating(AgeRating $ageRating): static
    {
        $this->ageRating = $ageRating;

        return $this;
    }
}
