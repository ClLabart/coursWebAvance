<?php

namespace App\Command;

use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'CreateType',
    description: 'Create all the types in the db',
)]
class CreateTypeCommand extends Command
{
    private EntityManagerInterface $em;
    private HttpClientInterface $client;

    public function __construct(EntityManagerInterface $em, HttpClientInterface $client)
    {
        $this->client = $client;
        $this->em = $em;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->recursiveImport($output, "https://pokeapi.co/api/v2/type");

        $this->em->flush();

        $output->writeln('Success !');

        return Command::SUCCESS;
    }
    
    protected function recursiveImport(OutputInterface $output, $url) {
        $response = $this->client->request(
            'GET',
            $url
        );

        $types = json_decode($response->getContent());

        foreach($types->results as $type) {
            $typeEntity = new Type();
            $typeEntity->setName($type->name);
            $this->em->persist($typeEntity);
            $output->writeln(ucfirst($typeEntity->getName()) . ' created !');
        }
    }
}
