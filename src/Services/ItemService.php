<?php

namespace App\Services;

use App\Entity\SkyblockAuctionItem;
use App\Entity\SkyblockItem;
use App\Entity\SkyblockUniqueItem;
use App\Entity\SkyblockUniqueItemHistory;
use App\Repository\SkyblockUniqueItemRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class ItemService {

    private EntityManagerInterface $em;
    private MinecraftService $mcs;

    const ENABLE_NAME_RESOLVER = true;

    public function __construct(EntityManagerInterface $em, MinecraftService $mcs) {
        $this->em = $em;
        $this->mcs = $mcs;
    }

    public function registerUniqueItem($item): bool {
        /**
         * @var SkyblockUniqueItemRepository $repo
         */
        $repo = $this->em->getRepository(SkyblockUniqueItem::class);
        $itemRepo = $this->em->getRepository(SkyblockItem::class);

        $uniqueItem = $repo->findOneBy(['uuid' => $item['item_uuid']]);

        $associatedItem = $itemRepo->findOneBy(['name' => $item['item_name']]);

        $value = 0;
        if(($item['starting_bid'] ?? 0) > ($item['highest_bid_amount'] ?? 0)) {
            $value = $item['starting_bid'];
        } else {
            $value = $item['highest_bid_amount'];
        }

        $owner = $item['auctioneer'];
        if(self::ENABLE_NAME_RESOLVER) {
            $owner = $this->mcs->resolveUuid($item['auctioneer']);
            if(empty($owner)) {
                $owner = $item['auctioneer'];
            }
        }


        if(empty($uniqueItem)) {
            $uniqueItem = new SkyblockUniqueItem();
            $uniqueItem->setFirstSeen(new DateTimeImmutable());
            $uniqueItem->setUuid($item['item_uuid']);
            $uniqueItem->setPlainItemName($item['item_name'] ?? '');

            if(!empty($uniqueItem))
                $uniqueItem->setItem($associatedItem);

            $itemHistory = new SkyblockUniqueItemHistory();
            $itemHistory->setObserved($uniqueItem->getFirstSeen());
            $itemHistory->setOwnerUuid($owner);
            $itemHistory->setValue($value);
            $this->em->persist($itemHistory);
            $uniqueItem->addSkyblockUniqueItemHistory($itemHistory);
            $this->em->persist($uniqueItem);
            $this->em->flush();
        } else {
            /**
             * @var SkyblockUniqueItemHistory $latestHistory
             */
            $latestHistory = $uniqueItem->getSkyblockUniqueItemHistories()->last();
            if($latestHistory->getOwnerUuid() !== $owner) {
                $itemHistory = new SkyblockUniqueItemHistory();
                $itemHistory->setObserved(new DateTimeImmutable());
                $itemHistory->setOwnerUuid($owner);
                $itemHistory->setValue($value);
                $this->em->persist($itemHistory);
                $uniqueItem->addSkyblockUniqueItemHistory($itemHistory);
                $this->em->flush();
            }
        }

        return true;
    }
}