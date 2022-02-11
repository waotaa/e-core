<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Vng\EvaCore\Models\Contact;
use Vng\EvaCore\Models\Instrument;

class ContactModelTest extends TestCase
{
    /** @test */
    public function testCreation(): void
    {
        $contact = Contact::factory()->create();
        $this->assertInstanceOf(Contact::class, $contact);
    }

    /** @test */
    public function testAttachingAnInstrument(): void
    {
        /** @var Instrument $instrument */
        $instrument = Instrument::factory()->create();

        /** @var Contact $contact */
        $contact = Contact::factory()->create();
        $contact->instruments()->attach($instrument);

        $contact = $contact->fresh();

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertInstanceOf(Instrument::class, $instrument);
        $this->assertContains($instrument->id, $contact->instruments->pluck('id'));
    }
}
