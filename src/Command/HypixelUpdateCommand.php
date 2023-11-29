<?php

namespace App\Command;

use App\Entity\SkyblockItem;
use App\Entity\ItemSellPrice;
use App\Services\ItemService;
use App\Entity\SkyblockAuctionItem;
use App\Entity\SkyblockItemCategory;
use App\Services\HypixelSkyblockService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Console\Command\Command;
use App\Repository\SkyblockAuctionItemRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'hypixel:update',
    description: 'Manually updates data',
)]
class HypixelUpdateCommand extends Command
{
    private HypixelSkyblockService $hss;
    private EntityManagerInterface $em;
    private ItemService $is;

    public function __construct(HypixelSkyblockService $hss, EntityManagerInterface $em, ItemService $is)
    {
        parent::__construct();
        $this->hss = $hss;
        $this->em = $em;
        $this->is = $is;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('subject', InputArgument::REQUIRED, 'The subject to update')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $subject = $input->getArgument('subject');

        switch (strtolower($subject)) {
            case 'item_npc_prices':
                $this->updatePrices($output);
                break;
            case 'auction_house':
                $this->updateAuctionHouse($output);
                break;
            case 'item_categories':
                $this->updateCategories($output);
                break;
            case 'item_all':
                $this->updateItems($output);
                break;
            case 'compost_values':
                $this->updateCompostValues($output);
                break;
            default:
                $output->writeln("Subjects allowed: auction_house, item_npc_prices, item_categories, item_all, compost_values");
        }

        return Command::SUCCESS;
    }

    private function updateAuctionHouse(OutputInterface $output): bool {

        $page = 0;
        $totalPages = 0;
        $totalAuctions = 0;

        /**
         * @var SkyblockAuctionItemRepository $repo
         */
        $repo = $this->em->getRepository(SkyblockAuctionItem::class);

        if(!empty($items = $repo->findAll())) {
            foreach($items as $item) {
                $this->em->remove($item);
            }
        }

        while(!empty($batch = $this->hss->getCurrentAuctions($page))) {
            if($totalPages === 0) {
                $totalPages = $batch['totalPages'];
                $totalAuctions = $batch['totalAuctions'];
            }

            $output->writeln("Page $page out of $totalPages ($totalAuctions total auctions)");

            $page += 1;
            
            foreach($batch['auctions'] as $key => $item) {
                if(!empty($repo->findOneBy(["uuid" => $item['uuid']])))
                    continue;
                
                $autionHouseItem = new SkyblockAuctionItem();
                $autionHouseItem->setAuctioneer($item['auctioneer']);
                $autionHouseItem->setUuid($item['uuid']);
                $autionHouseItem->setItemName($item['item_name']);
                $autionHouseItem->setItemUuid($item['item_uuid'] ?? null);
                $autionHouseItem->setStartingBid($item['starting_bid']);
                $autionHouseItem->setBin($item['bin']);
                $autionHouseItem->setRarity($item['tier']);
                $this->em->persist($autionHouseItem);
                $this->em->flush();

                if(isset($item['item_uuid'])) {
                    $this->is->registerUniqueItem($item);
                }
            }
        }
        return true;
    }

    private function updateCompostValues(OutputInterface $output): bool {
        $crawler = new Crawler(file_get_contents("https://hypixel-skyblock.fandom.com/wiki/Compost"));

        // wikitable sortable jquery-tablesorter

        dd($crawler->filterXPath("/html/body/div[4]/div[3]/div[2]/main/div[3]/div[2]/div/table")->text());

        foreach($crawler->children() as $domElement) {
            dd($domElement);
        }
        
        return true;
    }

    private function updatePrices(OutputInterface $output): bool {
        $items = $this->hss->getAllItems();

        foreach($items['items'] as $item) {
            if(!isset($item['npc_sell_price']))
                continue;

            if(empty($entity = $this->em->getRepository(ItemSellPrice::class)->findOneBy(['productId' => $item['id']]))) {
                $entity = new ItemSellPrice();
                $output->writeln("Added '". $item['id'] ."' new item to NPC price list.");
            }

            $entity->setPrice($item['npc_sell_price']);
            $entity->setProductId($item['id']);
            $this->em->persist($entity);
            $this->em->flush();
        }

        return true;
    }

    private function updateItems(OutputInterface $output): bool {
        $items = $this->hss->getAllItems();

        foreach($items as $item) {
            /**
             * @var SkyblockItem $entity
             */
            if(empty($entity = $this->em->getRepository(SkyblockItem::class)->findOneBy(['productId' => $item['id']]))) {
                $entity = new SkyblockItem();
                $entity->setProductId($item['id']);
                $this->em->persist($entity);
            }

            if(($entity->getRarity() ?? '') !== ($item['tier'] ?? 'COMMON')) {
                $entity->setRarity(strtoupper($item['tier'] ?? 'COMMON'));
            }

            if(($entity->getMaterial() ?? '') !== ($item['material'] ?? '')) {
                $entity->setMaterial($item['material']);
            }

            if(($entity->getName() ?? '') !== ($item['name'] ?? '')) {
                $entity->setName($item['name']);
            }

            if(($entity->getValue() ?? -1) !== ($item['npc_sell_price'] ?? -2)) {
                $entity->setValue($item['npc_sell_price'] ?? 0);
            }

            if(($entity->getLore() ?? "") !== ($item['description'] ?? "")) {
                $output->writeln("Added lore " . ($item['description'] ?? "No lore"));
                $entity->setLore($item['description'] ?? "");
            }

            if((strval($entity->getCategory() ?? 'NONE')) !== ($item['category'] ?? 'NONE')) {
                if(empty($categoryEntity = $this->em->getRepository(SkyblockItemCategory::class)->findOneBy(['name' => $item['category'] ?? 'NONE']))) {
                    $categoryEntity = new SkyblockItemCategory();
                    $output->writeln("Added '". ($item['category'] ?? "NONE") ."' new category.");
                }
    
                $categoryEntity->setName($item['category'] ?? 'NONE');
                $this->em->persist($categoryEntity);
                $this->em->flush();

                $entity->setCategory($categoryEntity);
            }

            $this->em->flush();

            $output->writeln($entity->getProductId() . ": " . implode(",", array_keys($item)));

            //$output->writeln("Added or updated " . $entity->getProductId());
        }
        
        return true;
    }

    private function updateCategories(OutputInterface $output): bool {
        $items = $this->hss->getAllItems();

        foreach($items as $item) {
            if(!isset($item['category']))
                continue;

            if(empty($entity = $this->em->getRepository(SkyblockItemCategory::class)->findOneBy(['name' => $item['category']]))) {
                $entity = new SkyblockItemCategory();
                $output->writeln("Added '". $item['category'] ."' new category.");
            }

            $entity->setName($item['category']);
            $this->em->persist($entity);
            $this->em->flush();
        }

        return true;
    }
}
