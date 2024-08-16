<?php

namespace App\Module\AnimeTitle\DTO;

use App\Module\AnimeTitle\Enum\AnimeTitleGenre;
use App\Module\AnimeTitle\Enum\AnimeTitleType;
use App\Module\AnimeTitle\Enum\AnimeTitleStatus;
use App\Module\AnimeTitle\Enum\AgeRating;
use Symfony\Component\Validator\Constraints as Assert;

class AnimeTitleDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $name;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $originalName;

    #[Assert\NotBlank]
    #[Assert\Length()]
    private ?string $description;

    #[Assert\NotBlank]
    private ?array $genres = [];

    #[Assert\NotBlank]
    private ?AnimeTitleType $type;

    #[Assert\NotBlank]
    private ?AnimeTitleStatus $status;

    #[Assert\NotBlank]
    private ?\DateTime $releaseDate;

    #[Assert\NotBlank]
    private ?AgeRating $ageRating;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): self
    {
        $this->originalName = $originalName;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
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

    public function setType(?AnimeTitleType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getStatus(): ?AnimeTitleStatus
    {
        return $this->status;
    }

    public function setStatus(?AnimeTitleStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getReleaseDate(): ?\DateTime
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?\DateTime $releaseDate): self
    {
        $this->releaseDate = $releaseDate;
        return $this;
    }

    public function getAgeRating(): ?AgeRating
    {
        return $this->ageRating;
    }

    public function setAgeRating(?AgeRating $ageRating): self
    {
        $this->ageRating = $ageRating;
        return $this;
    }
}
