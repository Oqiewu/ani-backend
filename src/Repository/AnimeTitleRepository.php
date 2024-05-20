<?php

namespace App\Repository;

use App\Entity\AnimeTitle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Enum\AnimeTitleGenre;
use App\Enum\AnimeTitleType;
use App\Enum\AnimeTitleStatus;
use App\Enum\AgeRating;

/**
 * @extends ServiceEntityRepository<AnimeTitle>
 */
class AnimeTitleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnimeTitle::class);
    }

    /**
     * @param int $year
     * @return AnimeTitle[]
     */
    public function findByReleaseYear(int $year): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->where('YEAR(a.releaseDate) = :year')
           ->setParameter('year', $year);
        
        return $qb->getQuery()->getResult();
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

//    /**
//     * @return AnimeTitle[] Returns an array of AnimeTitle objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AnimeTitle
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
