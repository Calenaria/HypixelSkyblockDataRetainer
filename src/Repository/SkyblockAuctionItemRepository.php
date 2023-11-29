<?php

namespace App\Repository;

use App\Entity\SkyblockAuctionItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SkyblockAuctionItem>
 *
 * @method SkyblockAuctionItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkyblockAuctionItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkyblockAuctionItem[]    findAll()
 * @method SkyblockAuctionItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkyblockAuctionItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkyblockAuctionItem::class);
    }

    public function save(SkyblockAuctionItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SkyblockAuctionItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return SkyblockAuctionItem[] Returns an array of SkyblockAuctionItem objects
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

//    public function findOneBySomeField($value): ?SkyblockAuctionItem
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
