<?php

namespace App\Entity;

use App\Repository\AnimeTitleRepository;
use Doctrine\ORM\Mapping as ORM;

use App\Enum\AnimeTitleGenre;
use App\Enum\AnimeTitleType;
use App\Enum\AnimeTitleStatus;
use App\Enum\AgeRating;

#[ORM\Entity(repositoryClass: AnimeTitleRepository::class)]
class AnimeTitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $original_name = null;

    #[ORM\Column(type: 'string', enumType: AnimeTitleGenre::class)]
    private ?AnimeTitleGenre $genre = null;

    #[ORM\Column(type: 'string', enumType: AnimeTitleType::class)]
    private ?AnimeTitleType $type = null;

    #[ORM\Column(type: 'string', enumType: AnimeTitleStatus::class)]
    private ?AnimeTitleStatus $status = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $releaseDate = null;

    #[ORM\Column(type: 'string', enumType: AgeRating::class)]
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
        return $this->original_name;
    }

    public function setOriginalName(string $original_name): static
    {
        $this->original_name = $original_name;

        return $this;
    }

    public function getGenre(): ?AnimeTitleGenre
    {
        return $this->genre;
    }

    public function setGenre(AnimeTitleGenre $genre): static
    {
        $this->genre = $genre;

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
