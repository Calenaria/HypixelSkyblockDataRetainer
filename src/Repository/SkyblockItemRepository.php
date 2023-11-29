<?php

namespace App\Repository;

use App\Entity\SkyblockItem;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<SkyblockItem>
 *
 * @method SkyblockItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method SkyblockItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method SkyblockItem[]    findAll()
 * @method SkyblockItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SkyblockItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SkyblockItem::class);
    }

    public function save(SkyblockItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SkyblockItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByOneTag(string $tag) {
        $sql = "SELECT skyblock_item.id, skyblock_item_tag.id, skyblock_item.product_id, skyblock_item_tag.name
        FROM skyblock_item 
        JOIN skyblock_item_skyblock_item_tag
            ON skyblock_item.id = skyblock_item_skyblock_item_tag.skyblock_item_id 
        JOIN skyblock_item_tag 
            ON skyblock_item_tag.id = skyblock_item_skyblock_item_tag.skyblock_item_tag_id
        WHERE skyblock_item_tag.name = '$tag';";
        $result = $this->getEntityManager()->getConnection()->executeQuery($sql);
        return $result->fetchAll();
        //$queryBuilder = new QueryBuilder($this->getEntityManager()->getConnection());
        //return $this->createQueryBuilder('item')
        //->select('skyblock_item.id, skyblock_item_tag.id, skyblock_item.product_id, skyblock_item_tag.name')
        //->from('skyblock_item', 'a')
        //->join('skyblock_item_skyblock_item_tag', 'c', 'on' ,'a.id = c.skyblock_item_id')
        //->join('skyblock_item_tag', 'b', 'on', 'b.id = c.skyblock_item_tag_id')
        //->where('b.name = :desired_tag')
        //->setParameter('desired_tag', $tag)
        //->getQuery()
        //->getResult()
        //;
    }
}
