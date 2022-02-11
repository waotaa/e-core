<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Vng\EvaCore\Models\Contact;
use Vng\EvaCore\Models\Instrument;

class InstrumentModelTest extends TestCase
{
    /** @test */
    public function testCreation(): void
    {
        $instrument = Instrument::factory()->create();
        $this->assertInstanceOf(Instrument::class, $instrument);
    }

    /** @test */
    public function testAttachingAContact(): void
    {
        /** @var Contact $contact */
        $contact = Contact::factory()->create();

        /** @var Instrument $instrument */
        $instrument = Instrument::factory()->create();
        $instrument->contacts()->attach($contact);

        $instrument = $instrument->fresh();

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertInstanceOf(Instrument::class, $instrument);
        $this->assertContains($contact->id, $instrument->contacts->pluck('id'));
    }
}
