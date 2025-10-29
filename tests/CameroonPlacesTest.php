    public function testDivisionsForRegion()
    {
        $places = new CameroonPlaces();
        $divisions = $places->divisions('CM-SW');
        $this->assertIsArray($divisions);
        $divisionNames = array_map(fn($d) => $d->name, $divisions);
        $this->assertContains('Fako', $divisionNames);
    }

    public function testSubdivisionsForDivision()
    {
        $places = new CameroonPlaces();
        $subdivisions = $places->subdivisions('CM-SW-FA');
        $this->assertIsArray($subdivisions);
        $subdivisionNames = array_map(fn($s) => $s->name, $subdivisions);
        $this->assertContains('Buea', $subdivisionNames);
    }

    public function testLocalitiesForParent()
    {
        $places = new CameroonPlaces();
        $localities = $places->localities('CM-SW-FA');
        $this->assertIsArray($localities);
        $localityNames = array_map(fn($l) => $l->name, $localities);
        $this->assertContains('Buea', $localityNames);
    }
<?php
use PHPUnit\Framework\TestCase;
use Mkwat\Places\CameroonPlaces;

class CameroonPlacesTest extends TestCase
{
    public function testCanInstantiateCameroonPlaces()
    {
        $places = new CameroonPlaces();
        $this->assertInstanceOf(CameroonPlaces::class, $places);
    }

    public function testCountryIsCameroon()
    {
        $places = new CameroonPlaces();
        $country = $places->country();
        $this->assertNotNull($country);
        $this->assertEquals('Cameroon', $country->name);
        $this->assertEquals('CM', $country->code);
    }

    public function testRegionsCount()
    {
        $places = new CameroonPlaces();
        $regions = $places->regions();
        $this->assertIsArray($regions);
        $this->assertGreaterThanOrEqual(10, count($regions));
        $regionNames = array_map(fn($r) => $r->name, $regions);
        $this->assertContains('Centre', $regionNames);
        $this->assertContains('North-West', $regionNames);
    }

    public function testFindByCodeReturnsCorrectPlace()
    {
        $places = new CameroonPlaces();
        $region = $places->findByCode('CM-NW');
        $this->assertNotNull($region);
        $this->assertEquals('North-West', $region->name);
        $this->assertEquals('region', $region->level);
    }

    public function testSearchReturnsResults()
    {
        $places = new CameroonPlaces();
        $results = $places->search('Buea');
        $this->assertIsArray($results);
        $this->assertNotEmpty($results);
        $names = array_map(fn($p) => $p->name, $results);
        $this->assertContains('Buea', $names);
    }
}
