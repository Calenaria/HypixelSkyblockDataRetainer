<?php

namespace App\Repository;

use App\Entity\SkyblockItemCompostable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SkyblockItemCompostable>
 *
 * @method SkyblockItemCompostable|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkyblockItemCompostable|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkyblockItemCompostable[]    findAll()
 * @method SkyblockItemCompostable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkyblockItemCompostableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkyblockItemCompostable::class);
    }

    public function save(SkyblockItemCompostable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SkyblockItemCompostable $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
