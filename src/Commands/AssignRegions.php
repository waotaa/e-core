<?php

namespace Vng\EvaCore\Commands;

use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\Township;
use Illuminate\Console\Command;

class AssignRegions extends Command
{
    protected $signature = 'eva:assign-regions';
    protected $description = 'Assign a region to every township';

    public function handle(): int
    {
        $this->getOutput()->writeln('assigning regions');

        $this->output->writeln('');
        foreach ($this->getRegioData() as $regionData)
        {
            $this->output->write('.');

            /** @var Region $region */
            $region = Region::query()->where('name', $regionData['naam'])->firstOrFail();
            $this->associateTownships($region, $regionData['gemeenten']);
        }

        $regionLessTownships = Township::query()->whereNull('region_id')->get();

        if($regionLessTownships->count() > 0) {
            $this->output->warning('Townships without region found [' . $regionLessTownships->count() . ']');
            foreach ($regionLessTownships as $township) {
                $this->output->writeln('* ' . $township->name);
            }
        }

        $this->output->writeln('');
        $this->getOutput()->writeln('assigning regions finished!');
        $this->output->writeln('');
        $this->output->writeln('');
        return 0;
    }

    private function associateTownships(Region $region, array $townshipNames)
    {
        $townships = Township::query()->whereIn('name', $townshipNames);
        $townships->each(function(Township $township) use ($region) {
            $township->region()->associate($region);
            $township->save();
        });
    }

    private function getRegioData() {
        return [
            [
                'naam' => 'Groningen',
                'gemeenten' => [
                    'Aa en Hunze',
                    'Appingedam',
                    'Assen',
//                    'Bedum',
//                    'Bellingwedde',
//                    'Ten Boer',
                    'Delfzijl',
//                    'Eemsmond',
                    'Het Hogeland',
                    'Groningen',
                    'Grootegast',
//                    'Haren',
//                    'Hoogezand-Sappemeer',
//                    'Leek',
                    'Loppersum',
                    'Midden-Groningen',
//                    'De Marne',
//                    'Marum',
//                    'Menterwolde',
                    'Noordenveld',
                    'Oldambt',
                    'Pekela',
                    'Stadskanaal',
//                    'Slochteren',
                    'Tynaarlo',
                    'Veendam',
//                    'Vlagtwedde',
                    'Westerkwartier',
                    'Westerwolde'
//                    'Winsum',
//                    'Zuidhorn',
                ]
            ],
            [
                'naam' => 'Friesland',
                'gemeenten' => [
                    'Achtkarspelen',
                    'Ameland',
                    'Dantumadiel',
//                    'Dongeradeel',
//                    'Ferwerderadiel',
//                    'Franekeradeel',
//                    'De Friese Meren',
                    'De Fryske Marren',
                    'Harlingen',
                    'Heerenveen',
//                    'Het Bildt',
//                    'Kollumerland en Nieuwkruisland',
                    'Leeuwarden',
                    'Noardeast-Fryslân',
//                    'Leeuwarderadeel',
//                    'Littenseradiel',
//                    'Menameradiel',
                    'Ooststellingwerf',
                    'Opsterland',
                    'Schiermonnikoog',
                    'Smallingerland',
                    'Súdwest-Fryslân',
                    'Terschelling',
                    'Tytsjerksteradiel',
                    'Vlieland',
                    'Waadhoeke',
                    'Weststellingwerf',
                ]
            ],
            [
                'naam' => 'Noord-Holland (Noord)',
                'gemeenten' => [
                    'Alkmaar',
                    'Bergen (NH)',
                    'Castricum',
                    'Den Helder',
                    'Drechterland',
                    'Enkhuizen',
                    'Heerhugowaard',
                    'Heiloo',
                    'Hollands Kroon',
                    'Hoorn',
                    'Koggenland',
                    'Langedijk',
                    'Medemblik',
                    'Opmeer',
                    'Schagen',
                    'Stede Broec',
                    'Texel',
                    'Uitgeest',
                ]
            ],
            [
                'naam' => 'Drenthe',
                'gemeenten' => [
                    'Borger-Odoorn',
                    'Coevorden',
                    'De Wolden',
                    'Emmen',
                    'Hoogeveen',
                    'Midden-Drenthe',
                ]
            ],
            [
                'naam' => 'Zwolle',
                'gemeenten' => [
                    'Dalfsen',
                    'Hardenberg',
                    'Hattem',
                    'Kampen',
//                    'Meppel Oldebroek', >> In document not in API
                    'Meppel',
                    'Oldebroek',
                    'Ommen',
                    'Raalte',
                    'Staphorst',
                    'Steenwijkerland',
                    'Westerveld',
                    'Zwartewaterland',
                    'Zwolle',
                ]
            ],
            [
                'naam' => 'Flevoland',
                'gemeenten' => [
                    'Almere',
                    'Dronten',
                    'Lelystad',
                    'Noordoostpolder',
                    'Urk',
                ]
            ],
            [
                'naam' => 'Zaanstreek / Waterland',
                'gemeenten' => [
                    'Beemster',
                    'Edam-Volendam',
                    'Landsmeer',
                    'Oostzaan',
                    'Purmerend',
                    'Waterland',
                    'Wormerland',
                    'Zaanstad',
                ]
            ],
            [
                'naam' => 'Zuid-Kennemerland en IJmond',
                'gemeenten' => [
                    'Beverwijk',
                    'Bloemendaal',
                    'Haarlem',
//                    'Haarlemmerliede en Spaarnwoude',
                    'Heemskerk',
                    'Heemstede',
                    'Velsen',
                    'Zandvoort',
                ]
            ],
            [
                'naam' => 'Twente',
                'gemeenten' => [
                    'Almelo',
                    'Borne',
                    'Dinkelland',
                    'Enschede',
                    'Haaksbergen',
                    'Hellendoorn',
//                    'Hengelo',
                    'Hengelo (O)',
                    'Hof van Twente',
                    'Losser',
                    'Oldenzaal',
                    'Rijssen-Holten',
                    'Tubbergen',
                    'Twenterand',
                    'Wierden',
                ]
            ],
            [
                'naam' => 'Groot Amsterdam',
                'gemeenten' => [
                    'Aalsmeer',
                    'Amstelveen',
                    'Amsterdam',
                    'De Ronde Venen',
                    'Diemen',
                    'Haarlemmermeer',
                    'Ouder-Amstel',
                    'Uithoorn',
                ]
            ],
            [
                'naam' => 'Stedendriehoek en Noordwest Veluwe',
                'gemeenten' => [
                    'Apeldoorn',
                    'Brummen',
                    'Deventer',
                    'Elburg',
                    'Epe',
                    'Ermelo',
                    'Harderwijk',
                    'Heerde',
                    'Lochem',
                    'Nunspeet',
                    'Olst-Wijhe',
                    'Putten',
                    'Voorst',
                    'Zeewolde',
                    'Zutphen',
                ]
            ],
            [
                'naam' => 'Gooi- en Vechtstreek',
                'gemeenten' => [
                    'Blaricum',
//                    'Bussum',
                    'Eemnes',
                    'Gooise Meren',
                    'Hilversum',
                    'Huizen',
                    'Laren',
//                    'Muiden',
//                    'Naarden',
                    'Weesp',
                    'Wijdemeren',
                ]
            ],
            [
                'naam' => 'Holland Rijnland',
                'gemeenten' => [
                    'Alphen aan den Rijn',
                    'Hillegom',
                    'Kaag en Braassem',
                    'Katwijk',
                    'Leiden',
                    'Leiderdorp',
                    'Lisse',
                    'Nieuwkoop',
//                    'Noordwijkerhout',
                    'Noordwijk',
                    'Oegstgeest',
                    'Teylingen',
                    'Zoeterwoude',
                ]
            ],
            [
                'naam' => 'Midden-Utrecht',
                'gemeenten' => [
                    'Bunnik',
                    'De Bilt',
                    'Houten',
                    'IJsselstein',
                    'Lopik',
                    'Montfoort',
                    'Nieuwegein',
                    'Oudewater',
                    'Stichtse Vecht',
                    'Utrecht',
                    'Utrechtse Heuvelrug',
//                    'Vianen',
                    'Vijfheerenlanden',
                    'Wijk bij Duurstede',
                    'Woerden',
                    'Zeist',
                ]
            ],
            [
                'naam' => 'Amersfoort',
                'gemeenten' => [
                    'Amersfoort',
                    'Baarn',
                    'Bunschoten',
                    'Leusden',
                    'Nijkerk',
                    'Soest',
                    'Woudenberg',
                ]
            ],
            [
                'naam' => 'Food Valley',
                'gemeenten' => [
                    'Barneveld',
                    'Ede',
                    'Renswoude',
                    'Rhenen',
                    'Scherpenzeel',
                    'Veenendaal',
                    'Wageningen',
                ]
            ],
            [
                'naam' => 'Achterhoek',
                'gemeenten' => [
                    'Aalten',
                    'Berkelland',
                    'Bronckhorst',
                    'Doetinchem',
                    'Montferland',
                    'Oost Gelre',
                    'Oude IJsselstreek',
                    'Winterswijk',
                ]
            ],
            [
                'naam' => 'Zuid-Holland Centraal',
                'gemeenten' => [
                    'Lansingerland',
                    'Leidschendam-Voorburg',
                    'Pijnacker-Nootdorp',
                    'Voorschoten',
                    'Wassenaar',
                    'Zoetermeer',
                ]
            ],
            [
                'naam' => 'Midden-Holland',
                'gemeenten' => [
                    'Bodegraven-Reeuwijk',
//                    'Capelle aan den IJssel',
                    'Gouda',
                    'Krimpenerwaard',
//                    'Oudewater',
                    'Waddinxveen',
                ]
            ],
            [
                'naam' => 'Haaglanden',
                'gemeenten' => [
                    'Delft',
                    '\'s-Gravenhage',
//                    'Den Haag',
                    'Midden-Delfland',
                    'Rijswijk',
                    'Westland',
                ]
            ],
            [
                'naam' => 'Midden-Gelderland',
                'gemeenten' => [
                    'Arnhem',
                    'Doesburg',
                    'Duiven',
                    'Lingewaard',
                    'Overbetuwe',
                    'Renkum',
                    'Rheden',
//                    'Rijnwaarden',
                    'Rozendaal',
                    'Westervoort',
                    'Zevenaar',
                ]
            ],
            [
                'naam' => 'Rijnmond',
                'gemeenten' => [
                    'Albrandswaard',
                    'Barendrecht',
//                    'Binnenmaas',
                    'Brielle',
                    'Capelle aan den IJssel',
//                    'Cromstrijen',
                    'Goeree-Overflakkee',
                    'Hellevoetsluis',
                    'Hoeksche Waard',
//                    'Korendijk',
                    'Krimpen aan den IJssel',
                    'Maassluis',
                    'Nissewaard',
//                    'Oud-Beijerland',
                    'Ridderkerk',
                    'Rotterdam',
                    'Rotterdam Schiekade',
                    'Rotterdam Herenwaard',
                    'Schiedam',
//                    'Strijen',
                    'Vlaardingen',
                    'Westvoorne',
                    'Zuidplas',
                ]
            ],
            [
                'naam' => 'Rivierenland',
                'gemeenten' => [
                    'Buren',
                    'Culemborg',
//                    'Geldermalsen',
                    'Maasdriel',
                    'Neder-Betuwe',
//                    'Neerijnen',
                    'Tiel',
                    'West Betuwe',
                    'West Maas en Waal',
                    'Zaltbommel',
                ]
            ],
            [
                'naam' => 'Gorinchem',
                'gemeenten' => [
//                    'Giessenlanden',
                    'Gorinchem',
                    'Hardinxveld-Giessendam', // In API not in document
//                    'Leerdam',
//                    'Lingewaal',
                    'Molenlanden',
//                    'Molenwaard',
//                    'Vijfheerenlanden', >>>> Split up between 2 regions. Should change
//                    'Zederik',
                ]
            ],
            [
                'naam' => 'Rijk van Nijmegen',
                'gemeenten' => [
                    'Berg en Dal',
                    'Beuningen',
                    'Druten',
                    'Heumen',
                    'Mook en Middelaar',
                    'Nijmegen',
//                    'Ubbergen',
                    'Wijchen',
                ]
            ],
            [
                'naam' => 'Drechtsteden',
                'gemeenten' => [
                    'Alblasserdam',
                    'Dordrecht',
                    'Hendrik-Ido-Ambacht',
                    'Papendrecht',
                    'Sliedrecht',
                    'Zwijndrecht',
                ]
            ],
            [
                'naam' => 'Noordoost-Brabant',
                'gemeenten' => [
                    'Bernheze',
                    'Boekel',
                    'Boxmeer',
                    'Boxtel',
                    'Cuijk',
                    'Grave',
                    'Haaren',
                    '\'s-Hertogenbosch',
                    'Landerd',
                    'Meierijstad',
                    'Mill en Sint Hubert',
//                    'Schijndel',
                    'Oss',
                    'Sint Anthonis',
                    'Sint-Michielsgestel',
//                    'Sint-Oedenrode',
                    'Uden',
//                    'Veghel',
                    'Vught',
                ]
            ],
            [
                'naam' => 'West-Brabant',
                'gemeenten' => [
                    'Altena',
//                    'Aalburg',
                    'Bergen op Zoom',
                    'Breda',
                    'Drimmelen',
                    'Etten-Leur',
                    'Geertruidenberg',
                    'Halderberge',
                    'Moerdijk',
                    'Oosterhout',
                    'Roosendaal',
                    'Rucphen',
                    'Steenbergen',
//                    'Werkendam',
                    'Woensdrecht',
//                    'Woudrichem',
                    'Zundert',
                ]
            ],
            [
                'naam' => 'Zeeland',
                'gemeenten' => [
                    'Borsele',
                    'Goes',
                    'Hulst',
                    'Kapelle',
                    'Middelburg',
                    'Noord-Beveland',
                    'Reimerswaal',
                    'Schouwen-Duiveland',
                    'Sluis',
                    'Terneuzen',
                    'Tholen',
                    'Veere',
                    'Vlissingen',
                ]
            ],
            [
                'naam' => 'Midden-Brabant',
                'gemeenten' => [
                    'Alphen-Chaam',
                    'Baarle-Nassau',
                    'Dongen',
                    'Gilze en Rijen',
                    'Goirle',
                    'Heusden',
                    'Hilvarenbeek',
                    'Loon op Zand',
                    'Oisterwijk',
                    'Tilburg',
                    'Waalwijk',
                ]
            ],
            [
                'naam' => 'Noord-Limburg',
                'gemeenten' => [
                    'Beesel',
                    'Bergen (L)',
                    'Gennep',
                    'Horst aan de Maas',
                    'Peel en Maas',
                    'Venlo',
                    'Venray',
                ]
            ],
            [
                'naam' => 'Helmond-De Peel',
                'gemeenten' => [
                    'Asten',
                    'Deurne',
                    'Geldrop-Mierlo',
                    'Gemert-Bakel',
                    'Helmond',
                    'Laarbeek',
                    'Someren',
                ]
            ],
            [
                'naam' => 'Zuidoost-Brabant',
                'gemeenten' => [
                    'Bergeijk',
                    'Best',
                    'Bladel',
//                    'Cranendonck',
                    'Eersel',
                    'Eindhoven',
                    'Heeze-Leende',
//                    'Nuenen CA', >> In document not in API
                    'Nuenen, Gerwen en Nederwetten',
                    'Oirschot',
                    'Reusel-De Mierden',
                    'Son en Breugel',
                    'Valkenswaard',
                    'Veldhoven',
                    'Waalre',
                ]
            ],
            [
                'naam' => 'Midden-Limburg',
                'gemeenten' => [
                    'Cranendonck',
                    'Echt-Susteren',
                    'Leudal',
                    'Maasgouw',
                    'Nederweert',
                    'Roerdalen',
                    'Roermond',
                    'Weert',
                ]
            ],
            [
                'naam' => 'Zuid-Limburg',
                'gemeenten' => [
                    'Beek',
                    'Beekdaelen',
                    'Brunssum',
                    'Eijsden-Margraten',
                    'Gulpen-Wittem',
                    'Heerlen',
                    'Kerkrade',
                    'Landgraaf',
                    'Maastricht',
                    'Meerssen',
//                    'Nuth',
//                    'Onderbanken',
//                    'Schinnen',
                    'Simpelveld',
                    'Sittard-Geleen',
                    'Stein',
                    'Vaals',
                    'Valkenburg aan de Geul',
                    'Voerendaal',
                ]
            ]
        ];
    }
}
