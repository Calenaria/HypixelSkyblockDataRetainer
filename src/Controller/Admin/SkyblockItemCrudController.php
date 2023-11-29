<?php

namespace App\Controller\Admin;

use App\Entity\SkyblockItem;
use Psr\Log\LoggerInterface;
use App\Message\SkyblockUpdateMessage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Messenger\MessageBusInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SkyblockItemCrudController extends AbstractCrudController
{
    private MessageBusInterface $mb;
    private LoggerInterface $logger;
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(
        MessageBusInterface $mb,
        LoggerInterface $logger,
        AdminUrlGenerator $adminUrlGenerator
    ) {
        $this->logger = $logger;
        $this->mb = $mb;
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return SkyblockItem::class;
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets 
            ->addJsFile("helper/rarity_style_injector.js")
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, "Skyblock - Items")
            ->setPaginatorPageSize(100)
        ;
    }

    public function configureActions(Actions $actions): Actions {
        $updateItems = Action::new('updateItems', 'Update')
            ->linkToCrudAction('updateItems')
            ->createAsGlobalAction();

        return $actions
            ->add(Crud::PAGE_INDEX, $updateItems);
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('productId')
            ->add('material')
            ->add(NumericFilter::new('value'))
            ->add('name')
            ->add(ChoiceFilter::new('rarity')->setChoices(SkyblockItem::RARITY_CHOICES))
            ->add('category')
            ->add('skill')
            ->add('tags');
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $rarity = ChoiceField::new('rarity')
        ->setChoices(SkyblockItem::RARITY_CHOICES);

        $rarity->renderAsBadges([
        ]);

        return [
            IdField::new('id')
                ->onlyOnIndex(),
            TextField::new('productId'),
            TextField::new('name'),
            TextField::new('material'),
            $rarity,
            NumberField::new('value'),
            AssociationField::new('skill', 'Skill')->setRequired(false),
            AssociationField::new('tags', "Tags"),
            AssociationField::new('category', "Category")->setRequired(false),
            TextareaField::new('lore', "Lore")
                ->setDisabled()
                ->hideOnIndex(),
        ];
    }

    public function updateItems(AdminContext $context) {
        $this->logger->info('ADMIN made item update request');
        try {
            $this->mb->dispatch(new SkyblockUpdateMessage(SkyblockUpdateMessage::UPDATE_ITEMS_ALL));
            $this->addFlash('success', "Request to update all items sent successfully.");
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            $this->addFlash('error', "Failed to request update, reason: " . $exception->getMessage());
        }

        $url = $this->adminUrlGenerator
        ->setController(self::class)
        ->setAction(Action::INDEX)
        ->generateUrl();

        return $this->redirect($url);
    }
}
