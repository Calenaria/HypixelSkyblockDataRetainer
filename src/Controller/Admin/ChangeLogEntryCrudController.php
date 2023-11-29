<?php

namespace App\Controller\Admin;

use App\Entity\ChangeLogEntry;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ChangeLogEntryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ChangeLogEntry::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyonIndex(),
            TextField::new('version'),
            DateField::new('changeLogDate'),
            TextEditorField::new('changeLogText')
        ];
    }
}
