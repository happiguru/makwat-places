# Makwat Places

A clean PHP library for Cameroon administrative places with optional Laravel integration.

- Levels: country, region, division, subdivision, locality
- API: `country()`, `regions()`, `divisions([regionCode])`, `subdivisions([divisionCode])`, `localities([parentCode])`, `findByCode(code)`, `search(query[, level])`
- Ships with a curated seed JSON. Pluggable data sources for future expansion.

---

## Table of Contents

- [Installation](#installation)
  - [Packagist](#packagist)
  - [Local Path Repository (Development)](#local-path-repository-development)
- [Data Source](#data-source)
- [Usage in Generic PHP](#usage-in-generic-php)
  - [Custom Data Path](#custom-data-path)
- [Laravel Integration](#laravel-integration)
  - [Publish Config](#publish-config)
  - [Using the Container](#using-the-container)
  - [Using the Facade](#using-the-facade)
  - [Controller Example](#controller-example)
  - [Blade Example](#blade-example)
  - [Validation Example](#validation-example)
- [Extending the Data](#extending-the-data)
- [CLI Updater (WIP)](#cli-updater-wip)
- [Performance Notes](#performance-notes)
- [Versioning and PHP Support](#versioning-and-php-support)
- [License](#license)

---

## Installation

### Packagist

```bash
composer require mkwat/makwat-places
```

### Local Path Repository (Development)

Add to your consuming project's `composer.json`:

```json
{
  "repositories": [
    { "type": "path", "url": "../Cameroon_Library", "options": { "symlink": true } }
  ],
  "require": {
    "mkwat/makwat-places": "*@dev"
  }
}
```

Then install/update:

```bash
composer update mkwat/makwat-places
```

---

## Data Source

- Default: local JSON at `data/places.json` bundled with the package.
- You can extend the JSON with your own entries (same schema) or later use a remote updater.

Schema per record:

```json
{
  "code": "CM-SW-FA-BU",
  "name": "Buea",
  "level": "subdivision",
  "parent": "CM-SW-FA",
  "meta": {"any": "extra"}
}
```

Levels: `country | region | division | subdivision | locality`. `parent` is the code of the immediate parent.

---

## Usage in Generic PHP

Minimal example:

```php
<?php
require __DIR__ . '/vendor/autoload.php';

use Mkwat\Places\CameroonPlaces;

$places = CameroonPlaces::makeDefault();

$country = $places->country();
$regions = $places->regions();
$divisionsInSW = $places->divisions('CM-SW');
$subdivisionsInFako = $places->subdivisions('CM-SW-FA');
$buea = $places->findByCode('CM-SW-FA-BU');
$search = $places->search('Limbe');

print_r([$country->toArray(), count($regions), count($divisionsInSW)]);
```

### Custom Data Path

```php
use Mkwat\Places\Repository\PlacesRepository;
use Mkwat\Places\Repository\DataSources\LocalJsonSource;
use Mkwat\Places\CameroonPlaces;

$repo = new PlacesRepository(new LocalJsonSource(__DIR__ . '/my_places.json'));
$places = new CameroonPlaces($repo);
```

---

## Laravel Integration

This package auto-discovers the service provider and facade in Laravel (5.5+).

### Publish Config

```bash
php artisan vendor:publish --tag=config --provider="Mkwat\\Places\\Laravel\\PlacesServiceProvider"
```

This publishes `config/makwat_places.php`:

```php
return [
    'data_path' => base_path('vendor/mkwat/makwat-places/data/places.json'),
];
```

### Using the Container

```php
use Mkwat\Places\CameroonPlaces;

$places = app(CameroonPlaces::class);
$regions = $places->regions();
```

### Using the Facade

```php
use Places;

$divisions = Places::divisions('CM-SW');
```

### Controller Example

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mkwat\Places\CameroonPlaces; // or use Places facade

class PlacesController extends Controller
{
    public function regions(CameroonPlaces $places)
    {
        return response()->json(array_map(fn($p) => $p->toArray(), $places->regions()));
    }

    public function divisions($regionCode, CameroonPlaces $places)
    {
        return response()->json(array_map(fn($p) => $p->toArray(), $places->divisions($regionCode)));
    }
}
```

### Blade Example

```php
@php($regions = Places::regions())
<select name="region">
  @foreach ($regions as $r)
    <option value="{{ $r->code }}">{{ $r->name }}</option>
  @endforeach
</select>
```

### Validation Example

```php
use Illuminate\Support\Arr;
use Places;

$regionCodes = array_map(fn($p) => $p->code, Places::regions());
$request->validate([
  'region' => ['required', 'in:' . implode(',', $regionCodes)],
]);
```

---

## Extending the Data

- Edit `data/places.json` and add entries following the schema. The repository will index them automatically.
- If you maintain your own copy outside `vendor/`, set `config('makwat_places.data_path')` in Laravel or instantiate `CameroonPlaces` with a custom repository in plain PHP.

---

## CLI Updater (WIP)

`bin/makwat-places-update` is a placeholder. Planned behavior:

- Query HumData CKAN API to resolve latest resource URLs
- Download CSV/GeoJSON
- Map fields to the internal schema and rebuild `data/places.json`

---

## Performance Notes

- The repository loads the JSON into memory and builds indexes by code, level, and parent.
- For very large locality lists, consider keeping localities in a separate file or implement lazy loading in a custom data source.

---

## Versioning and PHP Support

- PHP >= 7.4
- Tested with PHPUnit (you can add tests under `tests/`)

---

## License

MIT
