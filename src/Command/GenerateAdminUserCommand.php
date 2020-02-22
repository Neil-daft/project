<?php
declare(strict_types=1);

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

class GenerateAdminUserCommand extends Command
{
    protected static $defaultName = 'app:create:admin-user';

    /** @var EntityManagerHelper */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Creates a new admin user');
        $this->setHelp('This command creates a new admin user with the provided credentials');
        $this->addArgument(
            CommandArgument::EMAIL_ADDRESS,
            InputArgument::REQUIRED,
            'The admin email address');
        $this->addArgument(
            'password',
            InputArgument::REQUIRED,
            'The admins password');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument(CommandArgument::EMAIL_ADDRESS);
        $password = $input->getArgument('password');
        $formatter = $this->getHelper('formatter');
        $io = new SymfonyStyle($input, $output);
        $io->title(sprintf('Creating Admin user now with username %s', $input->getArgument(CommandArgument::EMAIL_ADDRESS)));

        try {
            $user = new User();
            $user->setEmail($email);
            $user->setPlainPassword($password);
            $user->setRoles(['ROLE_ADMIN']);

            $this->em->persist($user);
            $this->em->flush();
            $io->success('Success! The admin user has been created');
            return 1;
        } catch (Exception $e) {
            $errorMessages = ['Error!', $e->getMessage()];
            $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
            $output->writeln($formattedBlock);
            return 0;
        }
    }
}