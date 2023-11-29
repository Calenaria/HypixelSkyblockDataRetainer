<?php

namespace App\Repository;

use App\Entity\SkyblockItemTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SkyblockItemTag>
 *
 * @method SkyblockItemTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkyblockItemTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkyblockItemTag[]    findAll()
 * @method SkyblockItemTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkyblockItemTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkyblockItemTag::class);
    }

    public function save(SkyblockItemTag $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SkyblockItemTag $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SkyblockItemTag[] Returns an array of SkyblockItemTag objects
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

//    public function findOneBySomeField($value): ?SkyblockItemTag
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
