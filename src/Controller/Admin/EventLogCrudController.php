<?php

namespace App\Controller\Admin;

use App\Entity\EventLog;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EventLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EventLog::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort([
            'eventStartDate' => 'DESC',
        ]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('name'),
            DateTimeField::new('eventStartDate'),
            DateTimeField::new('eventEndDate'),
            TextField::new('status'),
            TextField::new('eventUuid')
        ];
    }
}
