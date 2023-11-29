<?php

namespace App\Repository;

use App\Entity\SkyblockUniqueItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SkyblockUniqueItem>
 *
 * @method SkyblockUniqueItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkyblockUniqueItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkyblockUniqueItem[]    findAll()
 * @method SkyblockUniqueItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkyblockUniqueItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkyblockUniqueItem::class);
    }

    public function save(SkyblockUniqueItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SkyblockUniqueItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SkyblockUniqueItem[] Returns an array of SkyblockUniqueItem objects
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

//    public function findOneBySomeField($value): ?SkyblockUniqueItem
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
