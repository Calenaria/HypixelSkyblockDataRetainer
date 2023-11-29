<?php

namespace App\MessageHandler;

use App\Entity\EventLog;
use App\Message\EventLogMessage;
use App\Services\HypixelSkyblockService;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

class EventMessageHandler {
    private HypixelSkyblockService $hss;
    private LoggerInterface $logger;
    private EntityManagerInterface $em;
    public function __construct(HypixelSkyblockService $hss, LoggerInterface $logger, EntityManagerInterface $em) {
        $this->hss = $hss;
        $this->logger = $logger;
        $this->em = $em;
    }

    #[AsMessageHandler]
    public function handleEvent(EventLogMessage $message) {
        if(empty($eventLog = $this->em->getRepository(EventLog::class)->findOneBy(['eventUuid' => $message->getEventUuid()]))) {
            $eventLog = new EventLog();
            $eventLog->setEventStartDate(new DateTime());
            $eventLog->setEventMessage($message->getEventMessage());
            $eventLog->setStatus($message->getStatus());
            $eventLog->setName($message->getEventName());
            $eventLog->setEventUuid($message->getEventUuid());
            $this->em->persist($eventLog);
            $this->em->flush();
            return;
        }

        switch($message->getStatus()) {
            case EventLogMessage::EVENT_STATUS_FAILED:
            case EventLogMessage::EVENT_STATUS_SUCCESS:
                $eventLog->setEventEndDate(new DateTime());
                $eventLog->setEventMessage($message->getEventMessage());
                $eventLog->setStatus($message->getStatus());
                break;
            default:
                
        }
        $this->em->flush();
    }
}