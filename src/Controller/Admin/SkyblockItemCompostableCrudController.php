<?php

namespace App\Controller\Admin;

use App\Entity\SkyblockItem;
use Doctrine\ORM\QueryBuilder;
use App\Entity\SkyblockItemCompostable;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SkyblockItemCompostableCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SkyblockItemCompostable::class;
    }

    public function configureActions(Actions $actions): Actions {
        
        return $actions; //->disable(Action::EDIT);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnIndex(),
            AssociationField::new('skyblockItem')
                ->setQueryBuilder(function (QueryBuilder $qb) {
                    return $qb->select('i')
                        ->from(SkyblockItem::class, 'i')
                        ->join('i.tags', 't')
                        ->where('t.name = :tag')
                        ->setParameter('tag', 'compostable');
                }),
            NumberField::new('value'),
        ];
    }
}
