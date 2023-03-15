<?php

namespace App\Command;

use App\Entity\Color;
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
    name: 'CreateColor',
    description: 'Create all the colors in the db',
)]
class CreateColorCommand extends Command
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
        $this->recursiveImport($output, "https://pokeapi.co/api/v2/pokemon-color");

        $this->em->flush();

        $output->writeln('Success !');

        return Command::SUCCESS;
    }

    protected function recursiveImport(OutputInterface $output, $url) {
        $response = $this->client->request(
            'GET',
            $url
        );

        $colors = json_decode($response->getContent());

        foreach($colors->results as $color) {
            $colorEntity = new Color();
            $colorEntity->setName($color->name);
            $this->em->persist($colorEntity);
            $output->writeln(ucfirst($colorEntity->getName()) . ' created !');
        }
    }
}
