<?php

namespace App\Controller\Admin;

use App\Entity\SkyblockItemTag;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SkyblockItemTagCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SkyblockItemTag::class;
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
