<?php

namespace Vng\EvaCore\Commands;

use Vng\EvaCore\Models\Contact;
use Vng\EvaCore\Models\Region;
use Illuminate\Console\Command;

class ImportRegions extends Command
{
    protected $signature = 'eva:import-regions';
    protected $description = 'Import township data from static data set';

    public function handle(): int
    {
        $this->getOutput()->writeln('importing regions');
        $this->output->writeln('');

        foreach ($this->getRegioData() as $regionData)
        {
            $this->getOutput()->write('.');
//            $this->getOutput()->write('- ' . $regionData['naam']);
            $region = Region::factory()->create([
                'name' => $regionData['naam'],
                'description' => $regionData['beschrijving'] ?? null,
                'cooperation_partners' => $regionData['deelnemende_partijen'] ?? null
            ]);

            if (isset($regionData['contact_persoon'])) {
                Contact::factory()->create([
                    'name' => $regionData['contact_persoon'],
                    'phone' => $regionData['contact_telefoon'] ?? null,
                    'email' => $regionData['contact_email'] ?? null,
                    'type' => null,
                    'contactable_id' => $region->id,
                    'contactable_type' => Region::class,
                ]);
            }
        }

        $this->output->writeln('');
        $this->getOutput()->writeln('importing regions finished!');
        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }

    private function getRegioData() {
        return [
            [
                'naam' => 'Groningen',
            ],
            [
                'naam' => 'Friesland',
            ],
            [
                'naam' => 'Noord-Holland (Noord)',
                'beschrijving' => 'Er zijn veel werkzoekenden die zelf geen werk kunnen vinden. Het WSP helpt u deze mensen aan het werk. Dat zijn bijvoorbeeld jongeren, 50-plussers, statushouders en mensen met een arbeidsbeperking. Net als ieder ander willen zij aan het werk. Werk is een manier om bij te dragen aan de maatschappij, het biedt een sociale omgeving, het zorgt voor zelfvertrouwen en zelfstandigheid. Wij helpen werkgevers om deze werkzoekenden in dienst te nemen.',
                'deelnemende partijen' => 'Binnen de arbeidsmarktregio Noord-Holland Noord werken de 18 gemeenten, UWV en SW-bedrijven samen. De werkgeversdienstverlening van deze partijen vormen het WSP Noord-Holland Noord.',
                'contact_persoon' => 'Ronald Koenen',
                'contact_email'  => 'ronaldkoenen@rpa-nhn.nl',
            ],
            [
                'naam' => 'Drenthe',
            ],
            [
                'naam' => 'Zwolle',
                'beschrijving' => 'Regio Zwolle zet Dennis in om de professional in- en overzicht te geven en de werkgever tijdig, efficiënt en helder te adviseren over werkgeversregelingen. Ook zijn alle noodmaatregelingen die van toepassing zijn tijdens de coronacrisis voor ondernemers en Zpp’ers verwerkt in Dennis. Dennis heeft als doel dat professionals in Regio Zwolle ondernemers, ZZP-ers en potentiële werknemers kunnen voorzien van alle informatie die beschikbaar is en om adequaat door te kunnen verwijzen. Let op! Dennis is nog in ontwikkeling. Nog niet alle functionaliteiten zijn daarom beschikbaar. Dennis is voor intern gebruik, delen met externe partijen is daarom (nog) niet wenselijk.',
                'deelnemende partijen' => 'Binnen de arbeidsmarktregio Zwolle werken de 13 gemeenten, UWV en SW-bedrijven samen. De werkgeversdienstverlening van deze partijen vormen het WSP Zwolle.',
                'contact_persoon' => 'Gerlinde Scheper',
                'contact_email'  => 'g.scheper@zwolle.nl',
            ],
            [
                'naam' => 'Flevoland'
            ],
            [
                'naam' => 'Zaanstreek / Waterland'
            ],
            [
                'naam' => 'Zuid-Kennemerland en IJmond',
            ],
            [
                'naam' => 'Twente',
            ],
            [
                'naam' => 'Groot Amsterdam',
            ],
            [
                'naam' => 'Stedendriehoek en Noordwest Veluwe',
            ],
            [
                'naam' => 'Gooi- en Vechtstreek',
            ],
            [
                'naam' => 'Holland Rijnland',
            ],
            [
                'naam' => 'Midden-Utrecht',
            ],
            [
                'naam' => 'Amersfoort',
            ],
            [
                'naam' => 'Food Valley',
            ],
            [
                'naam' => 'Achterhoek',
            ],
            [
                'naam' => 'Zuid-Holland Centraal',
            ],
            [
                'naam' => 'Midden-Holland',
            ],
            [
                'naam' => 'Haaglanden',
            ],
            [
                'naam' => 'Midden-Gelderland',
                'contact_persoon' => 'Monique van Ingen',
                'contact_email'  => 'm.vaningen@wsp-mg.nl',
            ],
            [
                'naam' => 'Rijnmond',
            ],
            [
                'naam' => 'Rivierenland',
            ],
            [
                'naam' => 'Gorinchem',
            ],
            [
                'naam' => 'Rijk van Nijmegen',
            ],
            [
                'naam' => 'Drechtsteden',
            ],
            [
                'naam' => 'Noordoost-Brabant',
            ],
            [
                'naam' => 'West-Brabant',
            ],
            [
                'naam' => 'Zeeland',
            ],
            [
                'naam' => 'Midden-Brabant',
            ],
            [
                'naam' => 'Noord-Limburg',
            ],
            [
                'naam' => 'Helmond-De Peel',
            ],
            [
                'naam' => 'Zuidoost-Brabant',
            ],
            [
                'naam' => 'Midden-Limburg',
            ],
            [
                'naam' => 'Zuid-Limburg',
            ]
        ];
    }
}
