<?php

namespace App\Module\AnimeTitle\Repository;

use App\Module\AnimeTitle\Entity\AnimeTitle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Module\AnimeTitle\Enum\AnimeTitleGenre;
use App\Module\AnimeTitle\Enum\AnimeTitleType;
use App\Module\AnimeTitle\Enum\AnimeTitleStatus;
use App\Module\AnimeTitle\Enum\AgeRating;

/**
 * @extends ServiceEntityRepository<AnimeTitle>
 */
class AnimeTitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnimeTitle::class);
    }

    public function save(AnimeTitle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AnimeTitle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findById(int $id): ?AnimeTitle
    {
        return $this->find($id);
    }

    /**
     * Get paginated results.
     *
     * @param int $page
     * @param int $limit
     * @param string $name
     * @return array
     */
    public function findPaginated(int $page, int $limit, string $name = ''): array
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);
    
        if ($name) {
            $queryBuilder->andWhere('a.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }
    
        return $queryBuilder->getQuery()->getResult();
    }
    
    public function countAll(string $name = ''): int
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->select('COUNT(a.id)');
    
        if ($name) {
            $queryBuilder->andWhere('a.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }
    
        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }
    

    /**
     * @param int $year
     * @return AnimeTitle[]
     */
    public function findByReleaseYear(int $year): array
    {
        return $this->createQueryBuilder('a')
            ->where('YEAR(a.releaseDate) = :year')
            ->setParameter('year', $year)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param AnimeTitleGenre $genre
     * @return AnimeTitle[]
     */
    public function findByGenre(AnimeTitleGenre $genre): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.genre = :genre')
            ->setParameter('genre', $genre)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param AnimeTitleType $type
     * @return AnimeTitle[]
     */
    public function findByType(AnimeTitleType $type): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();
    }

        /**
     * @param AnimeTitleStatus $status
     * @return AnimeTitle[]
     */
    public function findByStatus(AnimeTitleStatus $status): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.status = :status')
            ->setParameter('status', $status)
            ->getQuery()
            ->getResult();
    }

        /**
     * @param AgeRating $ageRating
     * @return AnimeTitle[]
     */
    public function findByAgeRating(AgeRating $ageRating): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.ageRating = :ageRating')
            ->setParameter('ageRating', $ageRating)
            ->getQuery()
            ->getResult();
    }
}
