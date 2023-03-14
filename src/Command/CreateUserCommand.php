<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(
    name: 'app:create-user',
    description: 'Create a new user'
)]
class CreateUserCommand extends Command
{
    private EntityManagerInterface $em;
    private UserPasswordHasherInterface $hasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $hasher)
    {
        $this->em = $em;
        $this->hasher = $hasher;
        parent::__construct();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $questionLogin = new Question("Username ? ");
        $questionPassword = new Question("Password ? ");
        $questionPassword->setHidden(true);
        $questionPassword->setHiddenFallback(false);

        $questionLastName = new Question("Last name ? ");
        $questionFirstName = new Question("First name ? ");

        $login = $helper->ask($input, $output, $questionLogin);
        $password = $helper->ask($input, $output, $questionPassword);
        $lastname = $helper->ask($input, $output, $questionLastName);
        $firstname = $helper->ask($input, $output, $questionFirstName);

        $output->writeln('Username : ' . $login);
        $output->writeln('Password : ' . $password);
        $output->writeln('Last name : ' . $lastname);
        $output->writeln('First name : ' . $firstname);
        
        $users = $this->em->getRepository(User::class)->findAll();
        if($users) {
            $output->writeln(count($users) . ' user(s) in DB.');
            // return Command::FAILURE;
        }

        $user = new User();
        $user->setEmail($login);
        $user->setPassword($password);
        $user->setFirstName($firstname);
        $user->setLastName($lastname);

        $hash = $this->hasher->hashPassword($user, $user->getPassword());

        $user->setPassword($hash);

        $this->em->persist($user);
        $this->em->flush();

        $output->writeln('Success !');
        return Command::SUCCESS;
    }

    // protected function configure(): void
    // {
    //     $this
    //         // ...
    //         ->addArgument('password', $this->requirePassword ? InputArgument::REQUIRED : InputArgument::OPTIONAL, 'User password')
    //         ->setHelp('Cette commande vous permet de créer un nouvel utilisateur...')
    //     ;
    // }
}