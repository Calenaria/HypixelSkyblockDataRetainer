<?php

namespace App\Controller\Admin;

use App\Entity\SkyblockItem;
use Psr\Log\LoggerInterface;
use App\Entity\SkyblockAuctionItem;
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
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SkyblockAuctionItemCrudController extends AbstractCrudController
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
        return SkyblockAuctionItem::class;
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets 
            ->addJsFile("helper/rarity_style_injector.js")
        ;
    }

    public function configureFilters(Filters $filters): Filters {
        return $filters
            ->add('bin')
            ->add('itemName')
            ->add(ChoiceFilter::new('rarity')->setChoices(SkyblockItem::RARITY_CHOICES))
            ->add('startingBid')
            ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPaginatorPageSize(100);
    }

    public function configureActions(Actions $actions): Actions {
        $refreshFn = Action::new("updateAuctions", 'Update')
            ->linkToCrudAction('updateAuctions')
            ->createAsGlobalAction();
        
        return $actions
            ->add(Crud::PAGE_INDEX, $refreshFn);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnIndex()
                ->setDisabled(),
            TextFIeld::new('itemName')
                ->setDisabled(),
            ChoiceField::new('rarity')
                ->setChoices(SkyblockItem::RARITY_CHOICES)
                ->renderAsBadges()
                ->setDisabled()
                ->hideWhenUpdating(),
            NumberField::new('startingBid')
                ->setDisabled(),
            BooleanField::new('bin')
                ->renderAsSwitch(false)
                ->setDisabled(),
            TextField::new('auctioneer')
                ->setDisabled(),
            TextField::new('uuid')
                ->onlyOnForms()
                ->setDisabled(),
            TextField::new('itemUuid')
                ->onlyOnForms()
                ->setDisabled(),
        ];
    }

    public function updateAuctions() {
        $this->logger->info('ADMIN made item update request');
        try {
            $this->mb->dispatch(new SkyblockUpdateMessage(SkyblockUpdateMessage::UPDATE_AUCTION_HOUSE));
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
