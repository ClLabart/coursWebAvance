<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\PokemonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => 'getCollection']),
        new Get(normalizationContext: ['groups' => 'get']),
    ],
    paginationItemsPerPage: 20
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial', 'id' => 'exact'])]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['get', 'getCollection'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['get', 'getCollection'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['get'])]
    private ?int $weight = null;

    #[ORM\ManyToMany(targetEntity: Type::class, inversedBy: 'pokemons')]
    #[Groups(['get'])]
    private Collection $types;

    #[ORM\ManyToOne(inversedBy: 'pokemons')]
    #[Groups(['get'])]
    private ?Habitat $habitat = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['get', 'getCollection'])]
    private ?string $front_default = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['get', 'getCollection'])]
    private ?string $front_shiny = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['get', 'getCollection'])]
    private ?string $front_female = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['get', 'getCollection'])]
    private ?string $front_shiny_female = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['get', 'getCollection'])]
    private ?string $back_default = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['get', 'getCollection'])]
    private ?string $back_shiny = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['get', 'getCollection'])]
    private ?string $back_female = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['get', 'getCollection'])]
    private ?string $back_shiny_female = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['get', 'getCollection'])]
    private ?string $official_artwork_front_default = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['get', 'getCollection'])]
    private ?string $official_artwork_front_shiny = null;

    #[ORM\ManyToOne(inversedBy: 'pokemons')]
    #[Groups(['get', 'getCollection'])]
    private ?Color $color = null;

    public function __construct()
    {
        $this->types = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return Collection<int, Type>
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types->add($type);
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        $this->types->removeElement($type);

        return $this;
    }

    public function getHabitat(): ?Habitat
    {
        return $this->habitat;
    }

    public function setHabitat(?Habitat $habitat): self
    {
        $this->habitat = $habitat;

        return $this;
    }

    public function getFrontDefault(): ?string
    {
        return $this->front_default;
    }

    public function setFrontDefault(?string $front_default): self
    {
        $this->front_default = $front_default;

        return $this;
    }

    public function getFrontShiny(): ?string
    {
        return $this->front_shiny;
    }

    public function setFrontShiny(?string $front_shiny): self
    {
        $this->front_shiny = $front_shiny;

        return $this;
    }

    public function getFrontFemale(): ?string
    {
        return $this->front_female;
    }

    public function setFrontFemale(?string $front_female): self
    {
        $this->front_female = $front_female;

        return $this;
    }

    public function getFrontShinyFemale(): ?string
    {
        return $this->front_shiny_female;
    }

    public function setFrontShinyFemale(?string $front_shiny_female): self
    {
        $this->front_shiny_female = $front_shiny_female;

        return $this;
    }

    public function getBackDefault(): ?string
    {
        return $this->back_default;
    }

    public function setBackDefault(?string $back_default): self
    {
        $this->back_default = $back_default;

        return $this;
    }

    public function getBackShiny(): ?string
    {
        return $this->back_shiny;
    }

    public function setBackShiny(?string $back_shiny): self
    {
        $this->back_shiny = $back_shiny;

        return $this;
    }

    public function getBackFemale(): ?string
    {
        return $this->back_female;
    }

    public function setBackFemale(?string $back_female): self
    {
        $this->back_female = $back_female;

        return $this;
    }

    public function getBackShinyFemale(): ?string
    {
        return $this->back_shiny_female;
    }

    public function setBackShinyFemale(?string $back_shiny_female): self
    {
        $this->back_shiny_female = $back_shiny_female;

        return $this;
    }

    public function getOfficialArtworkFrontDefault(): ?string
    {
        return $this->official_artwork_front_default;
    }

    public function setOfficialArtworkFrontDefault(?string $official_artwork_front_default): self
    {
        $this->official_artwork_front_default = $official_artwork_front_default;

        return $this;
    }

    public function getOfficialArtworkFrontShiny(): ?string
    {
        return $this->official_artwork_front_shiny;
    }

    public function setOfficialArtworkFrontShiny(string $official_artwork_front_shiny): self
    {
        $this->official_artwork_front_shiny = $official_artwork_front_shiny;

        return $this;
    }

    public function getColor(): ?Color
    {
        return $this->color;
    }

    public function setColor(?Color $color): self
    {
        $this->color = $color;

        return $this;
    }
}
