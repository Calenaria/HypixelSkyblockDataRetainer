<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use App\Entity\CurrentPriceRecord;
use App\Message\SkyblockUpdateMessage;
use App\Services\HypixelSkyblockService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Helper command to load data in a periodic time frame 
 */
#[AsCommand(
    name: 'hypixel:start',
    description: 'Runs scheduled tasks',
)]
class TaskRunnerCommand extends Command
{
    private EntityManagerInterface $em;
    private HypixelSkyblockService $hss;
    private LoggerInterface $logger;
    private MessageBusInterface $mb;

    const REFRESH_INTERVAL = 3600;

    public function __construct(MessageBusInterface $mb, EntityManagerInterface $em, HypixelSkyblockService $hss, LoggerInterface $logger) {
        parent::__construct();
        $this->em = $em;
        $this->hss = $hss;
        $this->logger = $logger;
        $this->mb = $mb;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    /**
     * Gathers item data every hour
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $lastRunDateTime = strtotime("this hour") - (strtotime("this hour") % 3600); // 3600 = every hour
        $nextRunDateTime = strtotime("next hour") - (strtotime("next hour") % 3600); // 3600 = every hour

        $this->logger->info("Last run: " . date("d-m-y H:i:s", $lastRunDateTime));
        $this->logger->info("Current time: " . date("d-m-y H:i:s"));
        $this->logger->info("Next run: " . date("d-m-y H:i:s", $nextRunDateTime));
        $this->logger->info("Time left to next run: " . ($nextRunDateTime - time()));

        while(True) {
            if((time() % 900) === 0) {
                $this->logger->info("Time left to next run: " . ($nextRunDateTime - time()) . " seconds");
            }

            if(time() > $nextRunDateTime) {
                $this->logger->info("Executing refresh.");
                if(!$this->gatherBazaarData($nextRunDateTime)) {
                    return Command::FAILURE;
                }
                if(!$this->refreshAuctionHouseData($nextRunDateTime)) {
                    return Command::FAILURE;    
                }
                return Command::SUCCESS;
                $lastRunDateTime = $nextRunDateTime;
                $nextRunDateTime = strtotime("next hour") - (strtotime("next hour") % 3600);    
                $this->logger->info("Next run: " . date("d-m-y H:i:s", $nextRunDateTime));
            }
            sleep(1);
        }

        return Command::SUCCESS;
    }

    private function refreshAuctionHouseData(int $currentTime): bool {
        $this->mb->dispatch(new SkyblockUpdateMessage(SkyblockUpdateMessage::UPDATE_AUCTION_HOUSE));
        return true;
    }

    private function gatherBazaarData(int $nextRunDateTime): bool {
        $bazaarData = $this->hss->getBazaarInformation();

        if($bazaarData['success'] === false) {
            return false;
        }

        $this->mb->dispatch(new SkyblockUpdateMessage(SkyblockUpdateMessage::UPDATE_ITEMS_ALL));
        
        foreach ($bazaarData['products'] as $productData) {
            $quickData = $productData['quick_status'];

            $dataRecord = new CurrentPriceRecord();

            $dataRecord->setProductId($quickData['productId']);
            $dataRecord->setRecordDate($nextRunDateTime);

            /** Buy data */
            $dataRecord->setBuyPrice($quickData['buyPrice']);
            $dataRecord->setBuyVolume($quickData['buyVolume']);
            $dataRecord->setBuyMovingWeek($quickData['buyMovingWeek']);
            $dataRecord->setBuyOrders($quickData['buyOrders']);
            
            /** Sell data */
            $dataRecord->setSellPrice($quickData['sellPrice']);
            $dataRecord->setSellVolume($quickData['sellVolume']);
            $dataRecord->setSellMovingWeek($quickData['sellMovingWeek']);
            $dataRecord->setSellOrders($quickData['sellOrders']);

            $this->em->persist($dataRecord);
            $this->em->flush();

        }

        return true;
    }
}
