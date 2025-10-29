<?php

namespace Mkwat\Places\Repository\DataSources;

use RuntimeException;

class LocalJsonSource
{
    private string $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function load(): array
    {
        if (!is_file($this->path)) {
            throw new RuntimeException("Seed data not found at {$this->path}");
        }
        $json = file_get_contents($this->path);
        $data = json_decode($json, true);
        if (!is_array($data)) {
            throw new RuntimeException('Invalid JSON in seed data.');
        }
        return $data;
    }
}
