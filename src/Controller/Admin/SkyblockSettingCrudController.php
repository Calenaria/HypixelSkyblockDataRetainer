<?php

namespace App\Controller\Admin;

use App\Entity\SkyblockSetting;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SkyblockSettingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SkyblockSetting::class;
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
