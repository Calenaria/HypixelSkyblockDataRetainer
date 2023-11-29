<?php

namespace App\Repository;

use App\Entity\SkyblockItemSubcategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SkyblockItemSubcategory>
 *
 * @method SkyblockItemSubcategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkyblockItemSubcategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkyblockItemSubcategory[]    findAll()
 * @method SkyblockItemSubcategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkyblockItemSubcategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkyblockItemSubcategory::class);
    }

    public function save(SkyblockItemSubcategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SkyblockItemSubcategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SkyblockItemSubcategory[] Returns an array of SkyblockItemSubcategory objects
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

//    public function findOneBySomeField($value): ?SkyblockItemSubcategory
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
