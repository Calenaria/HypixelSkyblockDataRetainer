<?php

namespace App\Controller\Admin;

use App\Entity\SkyblockUniqueItem;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\Query\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;

class SkyblockUniqueItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SkyblockUniqueItem::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            //->add(NumericFilter::new('historyEntries'))
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnIndex()
                ->setDisabled(),
            DateField::new('firstSeen')
                ->setDisabled(),
            TextField::new('uuid')
                ->setDisabled(),
            TextField::new('currentOwner')
                ->setDisabled(),
            CollectionField::new('skyblockUniqueItemHistories')
                ->hideOnIndex()
                ->setDisabled(),
            TextField::new('plainItemName')
                ->setDisabled(),
            AssociationField::new('item')
                ->hideOnIndex()
                ->setDisabled(),
            NumberField::new('averageValue')
                ->setDisabled(),
            NumberField::new('historyEntries')
                ->setDisabled(),
        ];
    }

}
