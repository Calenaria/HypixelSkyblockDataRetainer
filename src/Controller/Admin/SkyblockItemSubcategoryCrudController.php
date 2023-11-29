<?php

namespace App\Controller\Admin;

use App\Entity\SkyblockItemSubcategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SkyblockItemSubcategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SkyblockItemSubcategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle(Crud::PAGE_INDEX, "Skyblock - Item Sub-Categories");
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnIndex(),
            TextField::new('name'),
            AssociationField::new('superCategoryId')
                ->setLabel('Super-Category'),
        ];
    }
    
}
