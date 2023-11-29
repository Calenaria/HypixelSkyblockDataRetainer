<?php

namespace App\Repository;

use App\Entity\SkyblockUniqueItemHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SkyblockUniqueItemHistory>
 *
 * @method SkyblockUniqueItemHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkyblockUniqueItemHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkyblockUniqueItemHistory[]    findAll()
 * @method SkyblockUniqueItemHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkyblockUniqueItemHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkyblockUniqueItemHistory::class);
    }

    public function save(SkyblockUniqueItemHistory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SkyblockUniqueItemHistory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SkyblockUniqueItemHistory[] Returns an array of SkyblockUniqueItemHistory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SkyblockUniqueItemHistory
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
