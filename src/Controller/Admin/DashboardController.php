<?php

namespace App\Controller\Admin;

use App\Entity\ChangeLogEntry;
use App\Entity\CurrentPriceRecord;
use App\Entity\EventLog;
use App\Entity\SkyblockAuctionItem;
use App\Entity\SkyblockItem;
use App\Entity\SkyblockItemTag;
use App\Entity\SkyblockItemCategory;
use App\Entity\SkyblockItemCompostable;
use App\Entity\SkyblockSetting;
use App\Entity\SkyblockSkill;
use App\Entity\SkyblockUniqueItem;
use App\Entity\User;
use App\Repository\SkyblockAuctionItemRepository;
use App\Repository\SkyblockItemRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private SkyblockItemRepository $skyblockItemRepository;
    private SkyblockAuctionItemRepository $skyblockAuctionItemRepository;

    public function __construct(SkyblockItemRepository $skyblockItemRepository, SkyblockAuctionItemRepository $skyblockAuctionItemRepository) {
        $this->skyblockItemRepository = $skyblockItemRepository;
        $this->skyblockAuctionItemRepository = $skyblockAuctionItemRepository;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(SkyblockItemCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Main');
    }

    public function configureMenuItems(): iterable
    {
        $itemCount = $this->skyblockItemRepository->count([]);
        $auctionCount = $this->skyblockAuctionItemRepository->count([]);
        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-home'),
            MenuItem::section('System'),
            MenuItem::linkToCrud('Users', '', User::class),
            MenuItem::linkToCrud('Changelog', '', ChangeLogEntry::class),
            MenuItem::linkToCrud('Events', '', EventLog::class),
            MenuItem::section('Skyblock - Items'),
            MenuItem::linkToCrud('Items', '', SkyblockItem::class)->setBadge($itemCount),
            MenuItem::linkToCrud('Unique Items', '', SkyblockUniqueItem::class),
            MenuItem::linkToCrud('Categories', '', SkyblockItemCategory::class),
            MenuItem::linkToCrud('Tags', '', SkyblockItemTag::class),
            MenuItem::linkToCrud('Compostables', '', SkyblockItemCompostable::class),
            MenuItem::section('Skyblock - Bazaar'),
            MenuItem::linkToCrud('Items', '', CurrentPriceRecord::class),
            MenuItem::section('Skyblock - Auction House'),
            MenuItem::linkToCrud('Items', '', SkyblockAuctionItem::class)->setBadge($auctionCount),
            MenuItem::section('Skyblock - Meta'),
            MenuItem::linkToCrud('Skills', '', SkyblockSkill::class),
            MenuItem::linkToCrud('Settings', '', SkyblockSetting::class)
        ];
    }
}
