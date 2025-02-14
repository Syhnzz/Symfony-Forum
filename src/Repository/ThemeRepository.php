<?php

namespace App\Repository;

use App\Entity\Theme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Theme>
 */
class ThemeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Theme::class);
    }

//    /**
//     * @return Theme[] Returns an array of Theme objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Theme
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    public function findThemesPaginated(int $page = 1, int $limit = 5)
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->leftJoin('t.discussions', 'd')
            ->addSelect('d')
            ->orderBy('t.id', 'DESC')
            ->getQuery();

        $paginator = new Paginator($queryBuilder);
        $paginator->getQuery()->setFirstResult($limit * ($page - 1))
        ->setMaxResults($limit);

        return $paginator;
    }
}
