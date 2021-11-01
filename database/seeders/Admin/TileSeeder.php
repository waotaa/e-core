<?php

namespace Database\Seeders\Admin;

use Vng\EvaCore\Enums\TileEnum;
use Vng\EvaCore\Models\Tile;
use Exception;
use Illuminate\Database\Seeder;

class TileSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->getData() as $tileData) {
            $tileKeys = TileEnum::keys();
            if (!in_array($tileData['key'], $tileKeys)) {
                throw new Exception('Invalid tile key found in key data');
            }

            Tile::query()->updateOrCreate([
                'key' => $tileData['key']
            ],[
                'name' => $tileData['name'],
                'sub_title' => $tileData['sub_title'],
                'description' => $this->cleanWhitespaces($tileData['description']),
                'list' => $this->cleanWhitespaces($tileData['list']),
                'position' => $tileData['position'],
            ]);
        }
    }

    private function cleanWhitespaces($input) {
        // remove new lines
        $input = str_replace(PHP_EOL, ' ', $input);
        // multiple whitespaces reduced to one
        return preg_replace("/\s+/", ' ', $input);
    }

    private function getData() {
        return [
            [
                'name' => 'Melding',
                'sub_title' => 'Start',
                'description' => "
                    <p>
                        Een inwoner meldt zich bij de gemeente met het verzoek
                        tot ondersteuning naar participatie of werk. Vaak gaat
                        deze aanvraag gepaard met een aanvraag voor een
                        inkomensondersteuning.
                    </p>
                    <p>
                        Persoonlijk contact is hierbij essentieel, inwoners komen
                        met een hulpvraag en hebben hier vaak al een drempel
                        voor moeten overwinnen.
                    </p>
                    <p>
                        Daarom is het van belang dat het een laagdrempelige
                        toegang is, waar professionals een open houding
                        hebben. Zij noteren basisgegevens eenmalig en geven
                        die door. De professionals zijn, naast de direct
                        voorliggende vraag, ook alert op andere (acute)
                        problemen en op kwetsbare groepen. Bijvoorbeeld
                        laaggeletterden of jongeren met beperkingen die de
                        overstap maken van school naar werk.
                    </p>
                    <p>
                        Bij dergelijke signalering onderneemt de professional
                        actie en/of verwijst warm door naar het juiste loket. De
                        professional is zorgvuldig en concreet, zodat de inwoner
                        zich begrepen en goed geholpen voelt.
                    </p>",
                'list' => "
                    <ul>
                        <li>
                            Houding
                            <ul>
                                <li>Vertrouwenwekkend</li>
                                <li>Open</li>
                            </ul>
                        </li>
                        <li>
                            Alert
                            <ul>
                                <li>Kennis van kwetsbare groepen</li>
                                <li>Signaleert laaggeletterdheid</li>
                                <li>Signaleert licht verstandelijke beperking</li>
                            </ul>
                        </li>
                        <li>
                            Concreet
                            <ul>
                                <li>Warme overdracht</li>
                            </ul>
                        </li>
                        <li>Taalniveau is afgestemd op de inwoner</li>
                    </ul>
                ",
                'key' => 'melding',
                'position' => [
                    'x' => 0,
                    'y' => 1,
                ],
            ], [
                'name' => 'Diagnostiek',
                'sub_title' => 'Opstellen klantbeeld',
                'description' => "
                    <p>
                        Bij de intake wordt de hulpvraag van de inwoner verhelderd
                        door een brede uitvraag. De professional verdiept zich in de
                        informatie die reeds bekend is en bespreekt met de inwoner
                        zijn/haar huidige situatie op de verschillende
                        levensgebieden, mogelijkheden, belemmeringen, motivatie,
                        drijfveren, werk- en opleidingsachtergrond en stelt een
                        (eerste) klantbeeld op. Op basis van dit gesprek(ken) en het
                        klantbeeld, worden interventies ingezet die het vertrouwen
                        in eigen kunnen versterken en daarmee perspectief bieden
                        op (arbeids)participatie.
                    </p>
                    <p>
                        Het kantbeeld is niet statisch, maar continu in ontwikkeling
                        door nieuwe informatie, life events en/of inzet van
                        instrumenten. Na inzet van een interventie, evalueren
                        professionals wat het effect is op het klantbeeld en passen
                        deze aan. Vervolgens wordt op basis van het nieuwe
                        klantbeeld bepaald wat een volgende passende interventie
                        is.
                    </p>",
                'list' => "
                    <ul>
                        <li>
                            Houding: Vertrouwenwekkend
                            <ul>
                                <li>
                                    Hulpvraag verhelderen door uitvraag levensgebieden en persoonskenmerken,
                                    al dan niet met behulp van gevalideerde diagnose instrumenten en daarmee
                                    <ul>
                                        <li>Motivatie en onderliggende factoren, Perspectief, persoonskenmerken, affiniteiten</li>
                                        <li>Vaardigheden (basis- werknemer-, vak-, ervaring, opleiding etc.)</li>
                                        <li>Belemmeringen (fysiek, mentaal, praktisch)</li>
                                        <li>Beeld dat de klant van zichzelf heeft</li>
                                        <li>De urgentie van de belemmering(en)</li>
                                        <li>Snelheid van verwacht resultaat</li>
                                        <li>Eenvoudige / haalbare doelen en interventies</li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>
                            Klantbeeld vormen bestaande uit tenminste:
                            <ul>
                                <li>Opstellen plan van aanpak en perspectief, weeg daarvoor de situatie en levensgebieden op</li>
                                <li>Klantprofiel aanpassen n.a.v. interventies/instrumenten: hebben deze het verwachtte effect?</li>
                            </ul>
                        </li>
                    </ul>
                ",
                'key' => 'diagnostiek',
                'position' => [
                    'x' => 1,
                    'y' => 1,
                ],
            ], [
                'name' => 'Maatschappelijk fit',
                'sub_title' => 'Meedoen, in balans zijn en participeren',
                'description' => "
                    <p>
                        Om naar vermogen mee te kunnen doen in de
                        maatschappij, zijn er een aantal leefgebieden die in de
                        basis op orde moeten zijn. De Zelfredzaamheid-matrix
                        geeft een overzicht van deze leefgebieden die kunnen
                        worden uitgevraagd.
                    </p>
                    <p>
                        De diagnostische informatie is de basis voor de
                        (1 tot 4) verdiepende gesprekken. Hierbij is er aandacht
                        voor de basisvaardigheden, deze zijn noodzakelijk om
                        maatschappelijk mee te kunnen doen. Indien nodig wordt
                        integrale samenwerking gezocht in het brede sociaal
                        domein en met andere lokale maatschappelijke partners.
                    </p>
                    <p>
                        Maatschappelijk fit kan een opstap zijn naar betaald werk,
                        maar betaald werk is niet voor iedereen haalbaar. Voor hen
                        is participatie het hoogst haalbare. Bijdragen naar
                        vermogen vereist zorgvuldig bepalen wat haalbaar is.
                    </p>",
                'list' => "
                    <ul>
                        <li>
                            Stabiliseren van urgente problemen en belemmeringen
                        </li>
                        <li>
                            Kort en snel hulp bieden om praktische belemmeringen op te lossen. Dat wekt vertrouwen en versterkt participatie
                        </li>
                        <li>
                            Basisvaardigheden (taal, rekenen, sociale vaardigheden en digitale vaardigheden)
                        </li>
                        <li>
                            Financiële situatie (schuldhulp, voedselbank, voorzieningen check) stress sensitieve begeleiding
                        </li>
                        <li>
                            Inzet van participatie-instrumenten
                            <ul>
                                <li>Vrijwilligerswerk</li>
                                <li>Dagbesteding</li>
                                <li>Mantelzorg</li>
                            </ul>
                        </li>
                        <li>Gebruik het perspectief van de inwoner om een eerste stap te doen in die richting</li>
                        <li>Blijf alert op het mogelijke ontwikkelperspectief</li>
                    </ul>
                ",
                'key' => 'maatschappelijk_fit',
                'position' => [
                    'x' => 2,
                    'y' => 0,
                ],
            ], [
                'name' => 'Werk fit',
                'sub_title' => 'klaar voor werk',
                'description' => "
                    <p>
                        Het doel van Werk-fit is werkzoekenden te versterken om
                        betaald werk of een opleiding te kunnen aanvaarden en
                        behouden/afmaken.
                    </p>
                    <p>
                        Op basis van de diagnostische informatie, wordt gewerkt
                        aan: werknemers-, vak-presentatie en
                        sollicitatievaardigheden en het omgaan met/oplossen
                        van belemmeringen. Dat kan parallel lopen met
                        dienstverleningstegels Oriënteren en Opleiden (werk-
                        leertrajecten
                    </p>
                    <p>
                        Dit gebeurt vanuit een perspectief op de eerstvolgende
                        haalbare stap die de persoon kan maken. Belangrijk is het
                        versterken van vertrouwen in eigen kunnen. Waar
                        mogelijk wordt het leren van de verschillende
                        vaardigheden geïntegreerd met (onbetaald) werk.
                    </p>
                    <p>
                        Voortdurend wordt de diagnostisch informatie
                        uitgebreid, aangepast en vastgelegd.
                    </p>",
                'list' => "
                    <ul>
                        <li>
                            Blijvend werken aan perspectief
                            <ul>
                                <li>Het perspectief is de motor</li>
                            </ul>
                        </li>
                        <li>
                            Werken aan vaardigheden
                            <ul>
                                <li>Werknemersvaardigheden</li>
                                <li>Vakvaardigheden</li>
                                <li>Sollicitatie vaardigheden</li>
                                <li>Presentatievaardigheden</li>
                            </ul>
                        </li>
                        <li>
                            Werken aan motivatie
                            <ul>
                                <li>Vertrouwen in eigen kunnen</li>
                                <li>Inzet van eigen netwerk door persoon zelf</li>
                                <li>Het belang van werk</li>
                            </ul>
                        </li>
                        <li>
                            Werken aan volharding
                            <ul>
                                <li>Probleemoplossend vermogen vergroten</li>
                                <li>Werk met leerdoelen ipv prestatie doelen</li>
                            </ul>
                        </li>
                        <li>
                            Werken aan belemmeringen
                            <ul>
                                <li>Job coaching</li>
                                <li>Mentale en fysieke gezondheid</li>
                                <li>Vervoersproblemen</li>
                                <li>Opvang voor zorgtaken</li>
                            </ul>
                        </li>
                    </ul>
                ",
                'key' => 'werk_fit',
                'position' => [
                    'x' => 2,
                    'y' => 1,
                ],
            ], [
                'name' => 'Oriëntatie',
                'sub_title' => 'Op de arbeidsmarkt',
                'description' => "
                    <p>
                        Zowel de arbeidsmarkt als werkzoekenden ontwikkelen
                        zich.
                    </p>
                    <p>
                        In deze stap wordt met de werkzoekende onderzocht wat
                        een passende loopbaan voor hem/haar is en wat de
                        kansen zijn op de hedendaagse arbeidsmarkt. Dit kan
                        door testen, stages, netwerkgesprekken en vaak
                        stapsgewijs.
                    </p>
                    <p>
                        De uitkomst biedt een richting en perspectief om werk of
                        een opleiding te vinden en wordt zo onderdeel van de
                        diagnostische informatie.
                    </p>",
                'list' => "
                    <ul>
                        <li>
                            Concretiseren van het perspectief
                            <ul>
                                <li>Testen (competenties, capaciteiten en leervermogen, affiniteiten, beroepen)</li>
                                <li>Mogelijkheden van functies en bedrijven zoeken</li>
                                <li>Inzicht in (groei)mogelijkheden die een functie/bedrijf kan bieden</li>
                                <li>Begeleidingsgesprekken, benadruk dat een baan een stap is/kan zijn naar een volgende baan</li>
                                <li>Werkervaringsplekken/stages om het werk en de omgeving te ervaren</li>
                            </ul>
                        </li>
                    </ul>
                ",
                'key' => 'orientatie',
                'position' => [
                    'x' => 2,
                    'y' => 2,
                ],
            ], [
                'name' => 'Opleiden',
                'sub_title' => 'Kwalificeren',
                'description' => "
                    <p>
                        Taken en functies veranderen door de continue
                        veranderende arbeidsmarkt. Hierdoor is een ‘leven lang
                        leren’ onvermijdelijk geworden. Om aan het werk te komen
                        en blijven is scholing nodig.
                    </p>
                    <p>
                        Opleiden kan in verschillende vormen, waaronder:
                        - Diplomeren; het behalen van een MBO beroepsopleiding
                        via BBL of BOL, HBO of universiteit.<br>
                        - Certificeren; het halen van een certificaat, door het
                        behalen van een vastgesteld deel opleiding gekoppeld aan
                        een kwalificatiedossier. Dit is altijd in samenwerking met
                        het bedrijfsleven.<br>
                        - Praktijkverklaring; Erkennen van vaardigheden doordat
                        deze zijn aangetoond in de praktijk.
                    </p>
                    <p>
                        Ook is het mogelijk om werken en leren te combineren in
                        een leerwerktraject bij een ontwikkelbedrijf.
                    </p>",
                'list' => "
                    <ul>
                        <li>Certificaat (VCA, heftruck etc)</li>
                        <li>Diploma’s (MBO, aangepast MBO, BBL, BOL)</li>
                        <li>Leerwerktrajecten</li>
                        <li>Praktijk leren in het MBO (praktijkverklaring)</li>
                        <li>Open- en Edu- badges</li>
                    </ul>
                ",
                'key' => 'opleiden',
                'position' => [
                    'x' => 3,
                    'y' => 2,
                ],
            ], [
                'name' => 'Bemiddeling',
                'sub_title' => 'Kandidaten en vacatures koppelen',
                'description' => "
                    <p>
                        Het geheel aan diagnostische informatie, affiniteiten en de
                        competenties die zijn eigen gemaakt, worden gewogen en
                        gekoppeld aan vacatures en/of werkgevers.
                    </p>
                    <p>
                        Vacatures kunnen vacante plekken zijn, maar ook
                        toekomstige vacatures. Toekomstige vacatures bieden
                        meer kans voor de doelgroep omdat er voorbereidingstijd
                        is. Deze vacatures kunnen ontstaan door groei van een
                        bedrijf, aanpassing van bedrijfsprocessen en verwacht
                        verloop door o.a. pensionering, opleiding van huidig
                        personeel.
                    </p>
                    <p>
                        Denk hierbij ook aan de details zoals de match tussen
                        persoonlijke aspecten/affiniteiten, werkomstandigheden
                        en cultuur van het bedrijf. Bij hetzelfde beroep kan de
                        functie in het ene bedrijf anders zijn dan in het andere.
                    </p>
                    <p>
                        Belangrijk is om in affiniteiten, competenties en
                        leervermogen te denken i.p.v. behaalde opleidingen en
                        werkervaring. Het is daarom belangrijk dat werkgevers
                        vacatures ook in competenties beschrijven.
                    </p>",
                'list' => "
                    <ul>
                        <li>Matching op basis van competenties</li>
                        <li>
                            Kandidaat kan zich presenteren in verschillende omstandigheden
                            <ul>
                                <li>Speeddates</li>
                                <li>Verkennend gesprek</li>
                                <li>Sollicitatiegesprek</li>
                                <li>Ondersteunende begeleiding tijdens gesprek</li>
                            </ul>
                        </li>
                        <li>
                            Werkgevers Service Punt (WSP)
                            <ul>
                                <li>Wederzijdse aansluiting tussen coaching en activiteiten WSP</li>
                                <li>Relatie opbouw met private partijen</li>
                                <li>Vraaggericht werken door het aanbieden van passende kandidaten</li>
                                <li>Aanbodgericht werken obv goede relaties en nazorg aan werkgevers</li>
                            </ul>
                        </li>
                    </ul>
                ",
                'key' => 'bemiddeling',
                'position' => [
                    'x' => 3,
                    'y' => 1,
                ],
            ], [
                'name' => 'Plaatsing',
                'sub_title' => 'Werkelijk aan de slag',
                'description' => "
                    <p>
                        In deze stap wordt op basis van de plaatsingsafspraken met
                        de werkgever en werknemer ondersteunende
                        voorzieningen aangevraagd voor een zo groot mogelijke
                        kans van slagen op een duurzame match.
                    </p>
                    <p>
                        De professional heeft hierin een informerende,
                        adviserende en bemiddelende rol.
                    </p>
                    <p>
                        De professional bespreekt de valkuilen en afbreekrisico’s
                        met de werkgever en de werknemer. Zij bespreken wat
                        nodig is om de plaatsing te laten slagen en maken
                        afspraken over de nazorg.
                    </p>
                    <p>
                        Belangrijk aandachtspunt is om de werknemer zoveel
                        mogelijk te betrekken bij de afspraken.
                    </p>",
                'list' => "
                    <ul>
                        <li>Praktijkroute</li>
                        <li>Loonkostensubsidie (LKS)</li>
                        <li>Proefplaatsing</li>
                        <li>Werkplekaanpassing</li>
                        <li>Jobcoaching</li>
                        <li>Welke strategie als het tegenzit</li>
                    </ul>
                ",
                'key' => 'plaatsing',
                'position' => [
                    'x' => 4,
                    'y' => 1,
                ],
            ], [
                'name' => 'Nazorg',
                'sub_title' => 'Voor duurzame plaatsing',
                'description' => "
                    <p>
                        Om de kans te vergroten dat de werknemer aan het werk
                        blijft, is het van belang dat er nazorg wordt geboden als dat
                        nodig is. Dit kan gaan om ondersteuning aan de werknemer,
                        de werkgever en/of collega’s. De duur, intensiteit en inhoud
                        zijn afhankelijk van de behoefte. Dit kan variëren van
                        monitoring door nabellen tot intensieve structurele
                        begeleiding.
                    </p>
                    <p>
                        Sommigen gemeenten doen dit zelf, anderen kopen job-
                        coaching en/of andere ondersteuning in.
                    </p>
                    <p>
                        Voor werkgevers is continuïteit, één aanspreekpunt, weinig
                        wisselingen in de contactpersoon en snelle (re)actie in de
                        nazorg, de sleutel voor duurzame plaatsingen.
                    </p>
                    <p>
                        Nazorg betekent ook tijdig handelen wanneer de plaatsing
                        niet geschikt is, op zijn einde loopt of wanneer de
                        werknemer zijn werkplek ontgroeit.
                    </p>",
                'list' => "
                    <ul>
                        <li>Afspraken over de duur</li>
                        <li>
                            Bevorderen duurzame inzetbaarheid (DI)
                            <ul>
                                <li>Job-coaching</li>
                                <li>Ondersteuning werkgever</li>
                                <li>Instrumenten DI</li>
                            </ul>
                        </li>
                        <li>Wees bereikbaar voor de werkgever, zorg voor één aanspreekpunt, reageer snel en met actie.</li>
                        <li>Plaats door wanneer de werkplek niet geschikt blijkt</li>
                    </ul>
                ",
                'key' => 'nazorg',
                'position' => [
                    'x' => 4,
                    'y' => 2,
                ],
            ]
        ];
    }
}
