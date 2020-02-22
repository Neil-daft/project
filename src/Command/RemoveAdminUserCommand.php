<?php

namespace App\Command;

use App\Domain\CommandArgument;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RemoveAdminUserCommand extends Command
{
    protected static $defaultName = 'app:delete:admin-user';

    /** @var EntityManagerHelper */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Deletes an admin user');
        $this->setHelp('This command deletes an admin user with the provided email address');
        $this->addArgument(
            CommandArgument::EMAIL_ADDRESS,
            InputArgument::REQUIRED,
            'The admin email address');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $formatter = $this->getHelper('formatter');
        $io = new SymfonyStyle($input, $output);
        $io->title(sprintf('Deleting Admin user now with email %s', $input->getArgument(CommandArgument::EMAIL_ADDRESS)));

        try {
            $repository = $this->em->getRepository(User::class);
            $user= $repository->findOneBy(['email' => $input->getArgument(CommandArgument::EMAIL_ADDRESS)]);
            $this->em->remove($user);
            $this->em->flush();
            $io->success('Success! The admin user has been deleted');
            return 1;
        } catch (Exception $e) {
            $errorMessages = ['Error!', $e->getMessage()];
            $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
            $output->writeln($formattedBlock);
            return 0;
        }
    }
}