<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\EventRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use App\Validator\UniqueEventName;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[UniqueEventName]
#[ApiResource(
    operations: [
        new GetCollection(
            openapiContext: [
                "summary" => "Récupérer la liste des événements",
                "description" => ""
            ],
            normalizationContext: [
                'groups' => ['event:read']
            ]
        ),
        new Post(
            openapiContext: [
                "summary" => "Créer un nouvel événement",
                "description" => "Une vérification est faite sur le nom de l'événement pour éviter les doublons"
            ],
            normalizationContext: [
                'groups' => ['event:read']
            ],
            denormalizationContext: [
                'groups' => ['event:write']
            ]
        ),
        new Delete(
            openapiContext: [
                "summary" => "Supprimer un nouvel événement",
                "description" => ""
            ],
        )
    ],
    paginationEnabled: false
)]
#[ApiFilter(OrderFilter::class,
    properties: [
        'startDate'
    ],
    arguments: [
        'orderParameterName' => 'order'
    ])
]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['event:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['event:read', 'event:write'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['event:read', 'event:write'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['event:read', 'event:write'])]
    private ?\DateTimeInterface $startDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }
}
