<?php

namespace Mkwat\Places;

use Mkwat\Places\Repository\PlacesRepository;
use Mkwat\Places\Repository\DataSources\LocalJsonSource;

class CameroonPlaces
{
    private PlacesRepository $repo;

    public function __construct(?PlacesRepository $repo = null)
    {
        if ($repo) {
            $this->repo = $repo;
        } else {
            $dataPath = __DIR__ . '/../data/places.json';
            $this->repo = new PlacesRepository(new LocalJsonSource($dataPath));
        }
    }

    public static function makeDefault(): self
    {
        return new self();
    }

    public function country(): ?Models\Place
    {
        return $this->repo->country();
    }

    public function regions(): array
    {
        return $this->repo->regions();
    }

    public function divisions(?string $regionCode = null): array
    {
        return $this->repo->divisions($regionCode);
    }

    public function subdivisions(?string $divisionCode = null): array
    {
        return $this->repo->subdivisions($divisionCode);
    }

    public function localities(?string $parentCode = null): array
    {
        return $this->repo->localities($parentCode);
    }

    public function findByCode(string $code): ?Models\Place
    {
        return $this->repo->findByCode($code);
    }

    public function search(string $query, ?string $level = null): array
    {
        return $this->repo->search($query, $level);
    }
}
