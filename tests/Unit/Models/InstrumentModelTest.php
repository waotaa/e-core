<?php


namespace Unit\Models;

use Tests\TestCase;
use Vng\EvaCore\Models\Area;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Township;

class InstrumentModelTest extends TestCase
{
    public Instrument $instrument;

    protected function setUp(): void
    {
        parent::setUp();

        $this->instrument = new Instrument();
    }

    /** @test */
    public function check_simple_areas(): void
    {
        $areas = $this->createSimpleAreas();
        $region = $areas['A'];

        $this->assertNotNull($region->area);
        $this->assertNotNull($areas['A1']->area);
        $this->assertNotNull($areas['A2']->area);
        $this->assertContains('A1', $region->townships->map(fn (Township $t) => $t->name));
        $this->assertContains('A2', $region->townships->map(fn (Township $t) => $t->name));
    }

    /** @test */
    public function check_available_areas_with_no_owner(): void
    {
        $this->createSimpleAreas();
        $instrument = Instrument::factory()->nationallyAvailable()->create();

        $availableAreas = $instrument->availableAreas->map(fn(Area $a) => $a->area->name);
        // All areas are true available areas
        $this->assertContains('A', $availableAreas);
        $this->assertContains('A1', $availableAreas);
        $this->assertContains('A2', $availableAreas);

        $containingAvailableAreas = $instrument->containingAvailableAreas->map(fn(Area $a) => $a->area->name);
        // All areas are contained within the areas
        $this->assertContains('A', $containingAvailableAreas);
        $this->assertContains('A1', $containingAvailableAreas);
        $this->assertContains('A2', $containingAvailableAreas);

        $availableAreasLocatedIn = $instrument->availableAreasLocatedIn->map(fn(Area $a) => $a->area->name);
        // All areas can be located within all the areas combined
        $this->assertContains('A', $availableAreasLocatedIn);
        $this->assertContains('A1', $availableAreasLocatedIn);
        $this->assertContains('A2', $availableAreasLocatedIn);

        $totalAvailableAreas = $instrument->totalAvailableAreas->map(fn(Area $a) => $a->area->name);
        // All areas are available itself and through located in or containing
        $this->assertContains('A', $totalAvailableAreas);
        $this->assertContains('A1', $totalAvailableAreas);
        $this->assertContains('A2', $totalAvailableAreas);
    }

    /** @test */
    public function check_available_areas_from_region_owner(): void
    {
        $areas = $this->createSimpleAreas();
        $region = $areas['A'];
        $instrument = Instrument::factory()->forRegion($region)->create();

        $availableAreas = $instrument->availableAreas->map(fn(Area $a) => $a->area->name);
        // The region is the only true available area
        $this->assertContains('A', $availableAreas);
        $this->assertNotContains('A1', $availableAreas);
        $this->assertNotContains('A2', $availableAreas);

        $containingAvailableAreas = $instrument->containingAvailableAreas->map(fn(Area $a) => $a->area->name);
        // The region contains both townships
        $this->assertContains('A', $containingAvailableAreas);
        $this->assertContains('A1', $containingAvailableAreas);
        $this->assertContains('A2', $containingAvailableAreas);

        $availableAreasLocatedIn = $instrument->availableAreasLocatedIn->map(fn(Area $a) => $a->area->name);
        // The region is only fully located in itself
        $this->assertContains('A', $availableAreasLocatedIn);
        $this->assertNotContains('A1', $availableAreasLocatedIn);
        $this->assertNotContains('A2', $availableAreasLocatedIn);

        $totalAvailableAreas = $instrument->totalAvailableAreas->map(fn(Area $a) => $a->area->name);
        // The region A1 is available itself and through region A it containing townships
        $this->assertContains('A', $totalAvailableAreas);
        $this->assertContains('A1', $totalAvailableAreas);
        $this->assertContains('A2', $totalAvailableAreas);
    }

    /** @test */
    public function check_available_areas_from_township_owner(): void
    {
        $simpleAreas = $this->createSimpleAreas();
        $township = $simpleAreas['A1'];
        $instrument = Instrument::factory()->forTownship($township)->create();

        $availableAreas = $instrument->availableAreas->map(fn(Area $a) => $a->area->name);
        // The township A1 is the only true available area
        $this->assertNotContains('A', $availableAreas);
        $this->assertContains('A1', $availableAreas);
        $this->assertNotContains('A2', $availableAreas);

        $containingAvailableAreas = $instrument->containingAvailableAreas->map(fn(Area $a) => $a->area->name);
        // The township A1 contains no other areas beside itself
        $this->assertNotContains('A', $containingAvailableAreas);
        $this->assertContains('A1', $containingAvailableAreas);
        $this->assertNotContains('A2', $containingAvailableAreas);

        $availableAreasLocatedIn = $instrument->availableAreasLocatedIn->map(fn(Area $a) => $a->area->name);
        // The township A1 is fully located in itself and in region A
        $this->assertContains('A', $availableAreasLocatedIn);
        $this->assertContains('A1', $availableAreasLocatedIn);
        $this->assertNotContains('A2', $availableAreasLocatedIn);

        $totalAvailableAreas = $instrument->totalAvailableAreas->map(fn(Area $a) => $a->area->name);
        // The township A1 is available itself and through region A
        $this->assertContains('A', $totalAvailableAreas);
        $this->assertContains('A1', $totalAvailableAreas);
        $this->assertNotContains('A2', $totalAvailableAreas);
    }

    /** @test */
    public function check_available_areas_with_specific_areas_input(): void
    {
        $simpleAreas = $this->createSimpleAreas();
        /** @var Instrument $instrument */
        $instrument = Instrument::factory()->forTownship($simpleAreas['A1'])->create();
        $instrument->areas()->attach($simpleAreas['A2']->area->id);

        $availableAreas = $instrument->availableAreas->map(fn(Area $a) => $a->area->name);
        // The township A2 is the only true available area
        $this->assertNotContains('A', $availableAreas);
        $this->assertNotContains('A1', $availableAreas);
        $this->assertContains('A2', $availableAreas);

        $containingAvailableAreas = $instrument->containingAvailableAreas->map(fn(Area $a) => $a->area->name);
        // The township A2 contains no other areas beside itself
        $this->assertNotContains('A', $containingAvailableAreas);
        $this->assertNotContains('A1', $containingAvailableAreas);
        $this->assertContains('A2', $containingAvailableAreas);

        $availableAreasLocatedIn = $instrument->availableAreasLocatedIn->map(fn(Area $a) => $a->area->name);
        // The township A2 is fully located in itself and in region A
        $this->assertContains('A', $availableAreasLocatedIn);
        $this->assertNotContains('A1', $availableAreasLocatedIn);
        $this->assertContains('A2', $availableAreasLocatedIn);

        $totalAvailableAreas = $instrument->totalAvailableAreas->map(fn(Area $a) => $a->area->name);
        // The township A2 is available itself and through region A
        $this->assertContains('A', $totalAvailableAreas);
        $this->assertNotContains('A1', $totalAvailableAreas);
        $this->assertContains('A2', $totalAvailableAreas);
    }

    private function createSimpleAreas()
    {
        /** @var Region $region */
        $region = Region::factory()->create([
            'name' => 'A'
        ]);
        Area::factory()->for($region, 'area')->create();

        $township1 = Township::factory()->forRegion($region)->create([
            'name' => 'A1'
        ]);
        Area::factory()->for($township1, 'area')->create();

        $township2 = Township::factory()->forRegion($region)->create([
            'name' => 'A2'
        ]);
        Area::factory()->for($township2, 'area')->create();

        return [
            'A' => $region->fresh(),
            'A1' => $township1->fresh(),
            'A2' => $township2->fresh()
        ];
    }
}

