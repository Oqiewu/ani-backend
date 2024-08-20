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
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $originalName = null;
        
    #[ORM\Column(length: 255)]
    private ?string $imageUrl = null;

    #[ORM\Column(length: 255)]
    private ?string $smallImageUrl = null;

    #[ORM\Column(length: 255)]
    private ?string $largeImageUrl = null;


    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $genres = [];

    #[ORM\Column(type: 'string', enumType: AnimeTitleType::class)]
    private ?AnimeTitleType $type = null;

    #[ORM\Column(type: 'string', enumType: AnimeTitleStatus::class)]
    private ?AnimeTitleStatus $status = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $releaseDate = null;

    #[ORM\Column(type: 'string', enumType: AgeRating::class)]
    private ?AgeRating $ageRating = null;

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

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): static
    {
        $this->originalName = $originalName;
        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;
        return $this;
    }

    public function getSmallImageUrl(): ?string
    {
        return $this->smallImageUrl;
    }

    public function setSmallImageUrl(string $smallImageUrl): static
    {
        $this->smallImageUrl = $smallImageUrl;
        return $this;
    }

    public function getLargeImageUrl(): ?string
    {
        return $this->largeImageUrl;
    }

    public function setLargeImageUrl(string $largeImageUrl): static
    {
        $this->largeImageUrl = $largeImageUrl;
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

    public function setGenres(?array $genres): static
    {
        $this->genres = $genres;
        return $this;
    }

    public function addGenre(AnimeTitleGenre $genre): static
    {
        if (!in_array($genre->value, $this->genres, true)) {
            $this->genres[] = $genre->value;
        }
        return $this;
    }

    public function removeGenre(AnimeTitleGenre $genre): static
    {
        $key = array_search($genre->value, $this->genres, true);
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
