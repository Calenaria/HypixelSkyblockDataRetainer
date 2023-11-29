<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'user',
    description: 'Manages users.',
)]
class UserManageCommand extends Command
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher) {
        parent::__construct();
        $this->em = $em;
        $this->passwordHasher = $passwordHasher;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('action', InputArgument::REQUIRED, 'Argument description')
            ->addOption('email', null, InputOption::VALUE_OPTIONAL, 'Option description')
            ->addOption('password', null, InputOption::VALUE_OPTIONAL, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $action = $input->getArgument('action');
        
        switch ($action) {
            case 'create':
                if(empty($email = $input->getOption('email'))) {
                    $io->error('E-Mail required.');
                }
                if(empty($password = $input->getOption('password'))) {
                    $io->error('Password required.');
                }
                if(!empty($user = $this->em->getRepository(User::class)->findOneBy(['email' => $email])))
                    break;
                $user = new User();
                $user->setEmail($email);

                $hashedPassword = $this->passwordHasher->hashPassword($user, $password);

                $user->setPassword($hashedPassword);
                $user->setRoles(['ROLE_ADMIN']);
                $this->em->persist($user);
                $this->em->flush();
                $io->success("User created.");
                break;
            default:
                break;
        }

        return Command::SUCCESS;
    }
}
