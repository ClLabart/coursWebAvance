<?php

namespace App\Command;

use App\Entity\Color;
use App\Entity\Habitat;
use App\Entity\Pokemon;
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
    name: 'CreatePokemon',
    description: 'Create all the pokemons recursively',
)]
class CreatePokemonCommand extends Command
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
        $offset = 120;
        $limit = 20;
        $this->recursiveImport($output, "https://pokeapi.co/api/v2/pokemon?offset=$offset&limit=$limit", $offset, $limit);

        $this->em->flush();

        $output->writeln('Success !');

        return Command::SUCCESS;
    }
    
    protected function recursiveImport(OutputInterface $output, $url, $offset, $limit) {
        $response = $this->client->request(
            'GET',
            $url
        );

        $pokemons = json_decode($response->getContent());

        if (count($pokemons->results) === 0) {
            return "OK";
        }

        foreach($pokemons->results as $pokemon) {
            $pokemonEntity = new Pokemon();
            $pokemonEntity->setName($pokemon->name);

            $response2 = $this->client->request(
                'GET',
                $pokemon->url
            );
            
            $details = json_decode($response2->getContent());
            
            $pokemonEntity->setWeight($details->weight);
            $pokemonEntity->setFrontDefault($details->sprites->front_default);
            $pokemonEntity->setFrontShiny($details->sprites->front_shiny);
            $pokemonEntity->setFrontFemale($details->sprites->front_female);
            $pokemonEntity->setFrontShinyFemale($details->sprites->front_shiny_female);
            $pokemonEntity->setBackDefault($details->sprites->back_default);
            $pokemonEntity->setBackShiny($details->sprites->back_shiny);
            $pokemonEntity->setBackFemale($details->sprites->back_female);
            $pokemonEntity->setBackShinyFemale($details->sprites->back_shiny_female);
            $pokemonEntity->setOfficialArtworkFrontDefault($details->sprites->front_default);
            $pokemonEntity->setOfficialArtworkFrontShiny($details->sprites->front_shiny);
            
            foreach($details->types as $type) {
                $typeEntity = $this->em->getRepository(Type::class)->findOneByName($type->type->name);
                $pokemonEntity->addType($typeEntity);
            }

            $response3 = $this->client->request(
                'GET',
                "https://pokeapi.co/api/v2/pokemon-species/" . substr($pokemon->url, 34)
            );
            
            $species = json_decode($response3->getContent());

            $habitatEntity = $this->em->getRepository(Habitat::class)->findOneByName($species->habitat->name);
            $pokemonEntity->setHabitat($habitatEntity);

            $colorEntity = $this->em->getRepository(Color::class)->findOneByName($species->color->name);
            $pokemonEntity->setColor($colorEntity);
            
            $this->em->persist($pokemonEntity);
            $output->writeln(ucfirst($pokemonEntity->getName()) . ' created !');
        }

        $offset += $limit;
        $this->recursiveImport($output, "https://pokeapi.co/api/v2/pokemon?offset=$offset&limit=$limit", $offset, $limit);
    }
}
