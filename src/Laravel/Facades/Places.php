<?php

namespace Mkwat\Places\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use Mkwat\Places\CameroonPlaces;

class Places extends Facade
{
    protected static function getFacadeAccessor()
    {
        return CameroonPlaces::class;
    }
}
