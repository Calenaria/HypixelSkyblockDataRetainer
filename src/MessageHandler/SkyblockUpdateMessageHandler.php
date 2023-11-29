<?php

namespace App\MessageHandler;

use Psr\Log\LoggerInterface;
use App\Message\EventLogMessage;
use App\Message\SkyblockUpdateMessage;
use App\Services\HypixelSkyblockService;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

class SkyblockUpdateMessageHandler {
    private HypixelSkyblockService $hss;
    private LoggerInterface $logger;
    private MessageBusInterface $mb;
    public function __construct(HypixelSkyblockService $hss, LoggerInterface $logger, MessageBusInterface $mb) {
        $this->hss = $hss;
        $this->logger = $logger;
        $this->mb = $mb;
    }

    #[AsMessageHandler]
    public function updateSkyblockData(SkyblockUpdateMessage $message) {
        // event handling
        $eventId = uniqid();
        $this->mb->dispatch(new EventLogMessage('Updating ' . $message->getUpdateTarget() .' information', EventLogMessage::EVENT_STATUS_BUSY, $eventId, __FUNCTION__ . ": " . $message->getUpdateTarget()));

        $this->logger->info("Updating " . $message->getUpdateTarget());
        $successful = match (strtolower($message->getUpdateTarget())) {
            SkyblockUpdateMessage::UPDATE_ITEMS_ALL => $this->hss->updateItems(),
            SkyblockUpdateMessage::UPDATE_ITEMS_NPC_PRICES_ALL => null,
            SkyblockUpdateMessage::UPDATE_AUCTION_HOUSE => $this->hss->updateAuctions(),
            SkyblockUpdateMessage::UPDATE_ITEM_CATEGORIES => null,
            SkyblockUpdateMessage::UPDATE_COMPOST_VALUES => null,
            default => false
        };

        if($successful) {
            //send results and positive to event log
            $this->mb->dispatch(new EventLogMessage($message->getUpdateTarget() . ' was successful', EventLogMessage::EVENT_STATUS_SUCCESS, $eventId));
        } else {
            //send negative to event log
            $this->mb->dispatch(new EventLogMessage($message->getUpdateTarget() . ' failed', EventLogMessage::EVENT_STATUS_FAILED, $eventId));
        }
    }
}
