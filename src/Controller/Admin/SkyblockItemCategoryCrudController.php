<?php

namespace App\Controller\Admin;

use App\Entity\SkyblockItemCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SkyblockItemCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SkyblockItemCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle(Crud::PAGE_INDEX, "Skyblock - Item Categories");
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
