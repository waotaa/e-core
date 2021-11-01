<?php

namespace Database\Seeders\Environments;

use Vng\EvaCore\Models\Contact;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\NewsItem;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\Township;
use Illuminate\Database\Seeder;

class RsdDeLiemersSeeder extends Seeder
{
    public function run(): void
    {
        /** @var Partnership $partnership */
        $partnership = Partnership::query()->firstOrCreate([
            'name' => 'RSD de Liemers',
        ]);

        $townships = Township::query()->whereIn('name', [
            'Duiven',
            'Westervoort',
            'Zevenaar',
        ])->get();
        $partnership->townships()->sync($townships->pluck('id'));

        /** @var Environment $environment */
        $environment = Environment::query()->create([
            'name' => 'RSD de Liemers',
            'color_primary' => '#6f3274',
            'color_secondary' => '#eC6255',
            'featured_association_id' => $partnership->id,
            'featured_association_type' => get_class($partnership)
        ]);

        Contact::query()->create([
            'name' => 'Amanda Bruns',
            'phone' => '06 22441093',
            'email' => 'a.bruns@rsddeliemers.nl',
            'contactable_id' => $environment->id,
            'contactable_type' => Environment::class,
        ]);

        $this->addNewsItems($environment);
    }



    private function addNewsItems(Environment $environment)
    {
        NewsItem::factory()->create([
            'environment_id' => $environment->id,
            'title' => 'Nieuws',
            'sub_title' => 'Start nieuwe vitaliteitsgroep ‘op eigen kracht’',
            'body' => '<p>Het succesvolle vitaliteitsprogramma start weer over 4 weken. Heb jij kandidaten die beter in
            hun vel willen zitten, meer regie willen krijgen op hun leven en aan hun toekomstplan willen werken?
            <strong>Meld aan!</strong></p>',
        ]);
        NewsItem::factory()->create([
            'environment_id' => $environment->id,
            'title' => 'Nieuw instrument',
            'sub_title' => 'Jobhunting 2.0 door AanZ',
            'body' => '<p>Mensgerichte, resultaatgerichte en aanbodgerichte jobhunting. Door de kandidaat goed te leren
            kennen en ‘de kandidaat bij de hand’ te nemen en richting lokale werkgevers te stappen.</p>',
        ]);
        NewsItem::factory()->create([
            'environment_id' => $environment->id,
            'title' => 'Succesverhaal',
            'sub_title' => 'weer 2 mensen aan het werk via Meesterwerk',
            'body' => '<p>Na een proefplaatsing van twee maanden, hebben twee statushouders een jaar contract van 40 uur
            per week gekregen bij het bedrijf Promens. Zij gaan aan de slag als assemblage medewerker. Beide heren zijn
            erg blij met de kans die zij krijgen.</p>',
        ]);
        NewsItem::factory()->create([
            'environment_id' => $environment->id,
            'title' => 'Let op:',
            'sub_title' => 'nieuwe tarieven',
            'body' => '<p>De tarieven voor de inkoop van medische onderzoeken zijn gewijzigd. Bekijk de nieuwe tarieven
            om de juiste hoogte aan verplichtingen op te boeken.</p>',
        ]);
    }
}
