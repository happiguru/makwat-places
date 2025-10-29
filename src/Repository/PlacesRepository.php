<?php

namespace Mkwat\Places\Repository;

use Mkwat\Places\Models\Place;

class PlacesRepository
{
    /** @var array<string, Place> */
    private array $byCode = [];

    /** @var array<string, Place[]> */
    private array $byLevel = [];

    /** @var array<string, Place[]> */
    private array $byParent = [];

    public function __construct($source)
    {
        $rows = $source->load();
        foreach ($rows as $row) {
            $place = Place::fromArray($row);
            $this->byCode[$place->code] = $place;
            $this->byLevel[$place->level][] = $place;
            if ($place->parent) {
                $this->byParent[$place->parent][] = $place;
            }
        }
    }

    public function country(): ?Place
    {
        $countries = $this->byLevel['country'] ?? [];
        return $countries[0] ?? null;
    }

    /**
     * @return Place[]
     */
    public function regions(): array
    {
        return $this->byLevel['region'] ?? [];
    }

    /**
     * @return Place[]
     */
    public function divisions(?string $regionCode = null): array
    {
        if ($regionCode) {
            return $this->byParent[$regionCode] ?? [];
        }
        return $this->byLevel['division'] ?? [];
    }

    /**
     * @return Place[]
     */
    public function subdivisions(?string $divisionCode = null): array
    {
        if ($divisionCode) {
            return $this->byParent[$divisionCode] ?? [];
        }
        return $this->byLevel['subdivision'] ?? [];
    }

    /**
     * @return Place[]
     */
    public function localities(?string $parentCode = null): array
    {
        if ($parentCode) {
            return $this->byParent[$parentCode] ?? [];
        }
        return $this->byLevel['locality'] ?? [];
    }

    public function findByCode(string $code): ?Place
    {
        return $this->byCode[$code] ?? null;
    }

    /**
     * @return Place[]
     */
    public function search(string $query, ?string $level = null): array
    {
        $q = mb_strtolower($query);
        $haystack = $level ? ($this->byLevel[$level] ?? []) : array_values($this->byCode);
        return array_values(array_filter($haystack, function (Place $p) use ($q) {
            return strpos(mb_strtolower($p->name), $q) !== false;
        }));
    }
}
