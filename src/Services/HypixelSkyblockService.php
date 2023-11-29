<?php

namespace App\Services;

use App\Entity\SkyblockItem;
use Psr\Log\LoggerInterface;
use App\Services\ItemService;
use App\Api\HypixelSkyblockClient;
use App\Entity\EventLog;
use App\Services\MinecraftService;
use App\Entity\SkyblockAuctionItem;
use App\Entity\SkyblockItemCategory;
use App\Message\EventLogMessage;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class HypixelSkyblockService {
    
    const ENDPOINT_PLAYER = 'player';
    const ENDPOINT_KEY = 'key';
    const ENDPOINT_SKYBLOCK_BAZAAR = 'skyblock/bazaar';
    const ENDPOINT_ITEMS = 'resources/skyblock/items';
    const ENDPOINT_SKILLS = 'resources/skyblock/skills';
    const ENDPOINT_COLLECTIONS = 'resources/skyblock/collections';
    const ENDPOINT_NEWS = 'skyblock/news';
    const ENDPOINT_AUCTION_HOUSE = 'skyblock/auctions';
    const ENDPOINT_AUCTION_HOUSE_ENDED = 'skyblock/autions_ended';
    
    private HypixelSkyblockClient $client;
    private EntityManagerInterface $em;
    private LoggerInterface $logger;
    private ItemService $is;
    private MinecraftService $mcs;
    private MessageBusInterface $mb;

    public function __construct(MessageBusInterface $mb, EntityManagerInterface $em, LoggerInterface $logger, ItemService $is, MinecraftService $mcs) {
        $this->client = new HypixelSkyblockClient();
        $this->is = $is;
        $this->em = $em;
        $this->logger = $logger;
        $this->mcs = $mcs;
        $this->mb = $mb;
    }

    public function getKeyInformation(): array {
        return $this->client->retrieve(self::ENDPOINT_KEY, []);
    }

    public function getBazaarInformation(): array {
        return $this->client->retrieve(self::ENDPOINT_SKYBLOCK_BAZAAR, []);
    }

    public function getSpecificBazaarInformation(string $itemName): array {
        return $this->getBazaarInformation()['products'][$itemName]['quick_status'];
    }

    public function getAllProductIds(): array {
        return array_keys($this->getBazaarInformation()['products']);
    }

    public function getAllItems(): array {
        return $this->client->retrieve(self::ENDPOINT_ITEMS)['items'];
    }

    public function getSkills(): array {
        return $this->client->retrieve(self::ENDPOINT_SKILLS);
    }

    public function getCollections(): array {
        return $this->client->retrieve(self::ENDPOINT_SKILLS);
    }

    public function getNews(): array {
        return $this->client->retrieve(self::ENDPOINT_NEWS);
    }

    public function getCurrentAuctions(int $page = 0): array {
        return $this->client->retrieve(self::ENDPOINT_AUCTION_HOUSE, ['page' => $page]);
    }

    public function updateAuctions(): bool {
        $page = 0;
        $totalPages = 0;
        $totalAuctions = 0;
        $secondsTimeout = new DateTime();
        $secondsTimeout->modify("+600 seconds");

        /**
         * @var SkyblockAuctionItemRepository $repo
         */
        $repo = $this->em->getRepository(SkyblockAuctionItem::class);

        if(!empty($items = $repo->findAll())) {
            foreach($items as $item) {
                $this->em->remove($item);
            }
        }

        while(!empty($batch = $this->getCurrentAuctions($page))) {
            if($secondsTimeout < (new DateTime())) {
                return true;
            }

            if($totalPages === 0) {
                $totalPages = $batch['totalPages'];
                $totalAuctions = $batch['totalAuctions'];
            }

            $page += 1;
            
            foreach($batch['auctions'] as $key => $item) {
                if(!empty($repo->findOneBy(["uuid" => $item['uuid']])))
                    continue;

                $autionHouseItem = new SkyblockAuctionItem();
                $autionHouseItem->setAuctioneer($this->mcs->resolveUuid($item['auctioneer'])); //$autionHouseItem->setAuctioneer($item['auctioneer']);
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

    public function updateItems(): bool {
        $items = $this->getAllItems();

        foreach($items as $item) {
            $this->logger->debug($item['id']);
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
                $entity->setLore($item['description'] ?? "");
            }

            if((strval($entity->getCategory() ?? 'NONE')) !== ($item['category'] ?? 'NONE')) {
                if(empty($categoryEntity = $this->em->getRepository(SkyblockItemCategory::class)->findOneBy(['name' => $item['category'] ?? 'NONE']))) {
                    $categoryEntity = new SkyblockItemCategory();
                }
    
                $categoryEntity->setName($item['category'] ?? 'NONE');
                $this->em->persist($categoryEntity);
                $this->em->flush();

                $entity->setCategory($categoryEntity);
            }

            $this->em->flush();
        }
        
        return true;
    }
}