<?php

namespace App\Controller\Admin;

use App\Entity\CurrentPriceRecord;
use DateTime;
use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CurrentPriceRecordCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CurrentPriceRecord::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE);
    }

    public function configureCrud(Crud $crud): Crud
    {

        return $crud->setPageTitle(Crud::PAGE_INDEX, "Skyblock - Bazaar");
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('productId')
            ->add('sellPrice')
            ->add('buyPrice')
            ->add('sellVolume')
            ->add('buyVolume');
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('productId'),
            NumberField::new('sellPrice'),
            NumberField::new('sellVolume'),
            NumberField::new('buyPrice'),
            NumberField::new('buyVolume'),
            TextField::new('formattedRecordDate')->setDisabled()
        ];
    }
    
}
