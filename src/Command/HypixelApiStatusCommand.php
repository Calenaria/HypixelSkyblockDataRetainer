<?php

namespace App\Command;

use App\Services\HypixelSkyblockService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'hypixel:api:status',
    description: 'Check status of API key in use.',
)]
class HypixelApiStatusCommand extends Command
{
    private HypixelSkyblockService $hss;

    public function __construct(HypixelSkyblockService $hss)
    {
        parent::__construct();
        $this->hss = $hss;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $status = $this->hss->getKeyInformation();

        $io->writeln(json_encode($status));

        return Command::SUCCESS;
    }
}
