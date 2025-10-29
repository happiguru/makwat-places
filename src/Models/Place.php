<?php

namespace Mkwat\Places\Models;

class Place
{
    public string $code;     // unique code per place
    public string $name;     // display name
    public string $level;    // country|region|division|subdivision|locality
    public ?string $parent;  // parent code
    public array $meta;      // arbitrary extra data

    public function __construct(string $code, string $name, string $level, ?string $parent = null, array $meta = [])
    {
        $this->code = $code;
        $this->name = $name;
        $this->level = $level;
        $this->parent = $parent;
        $this->meta = $meta;
    }

    public static function fromArray(array $row): self
    {
        return new self(
            $row['code'],
            $row['name'],
            $row['level'],
            $row['parent'] ?? null,
            $row['meta'] ?? []
        );
    }

    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'level' => $this->level,
            'parent' => $this->parent,
            'meta' => $this->meta,
        ];
    }
}
