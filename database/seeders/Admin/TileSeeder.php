<?php

namespace Database\Seeders\Admin;

use Vng\EvaCore\Enums\TileEnum;
use Vng\EvaCore\Models\Tile;
use Exception;
use Illuminate\Database\Seeder;

/**
 * Eva Werklandschapstegel - EW
 */
class TileSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->getData() as $tileData) {
            $tileKeys = TileEnum::keys();
            if (!in_array($tileData['key'], $tileKeys)) {
                throw new Exception('Invalid tile key found in key data');
            }

            Tile::withoutEvents(function () use ($tileData) {
                Tile::query()->updateOrCreate([
                    'key' => $tileData['key']
                ], [
                    'code' => $tileData['code'],
                    'name' => $tileData['name'],
                    'sub_title' => $tileData['sub_title'],
                    'excerpt' => $tileData['excerpt'],
                    'description' => $this->cleanWhitespaces($tileData['description']),
                    'list' => $this->cleanWhitespaces($tileData['list']),
                    'crisis_description' => $this->cleanWhitespaces($tileData['description']),
                    'crisis_services' => $this->cleanWhitespaces($tileData['crisis_services']),
                    'position' => $tileData['position'],
                ]);
            });
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
                'code' => 'EW01',
                'name' => 'Melding',
                'sub_title' => 'Start',
                'excerpt' => "Iemand die zijn baan, opdracht of bedrijf (bij ZZP) dreigt te verliezen of inmiddels verloren heeft, meldt zich met het verzoek tot ondersteuning naar scholing of werk.",
                'description' => "
                    <p>
                        Een klant meldt zich bij de gemeente met het verzoek tot ondersteuning naar participatie of 
                        werk. Vaak gaat deze aanvraag gepaard met een aanvraag voor een inkomensondersteuning.
                    </p>
                    <p>
                        Persoonlijk contact is hierbij essentieel. Klanten komen met een hulpvraag en hebben hiervoor 
                        vaak al een drempel moeten overwinnen.
                    </p>
                    <p>
                        Het is daarom van belang dat de toegang laagdrempelig is en de professional een open houding 
                        heeft. De professional noteert basisgegevens eenmalig en registreert deze voor meervoudig 
                        gebruik. Naast de direct voorliggende vraag is de professional ook alert op andere (acute) 
                        problemen en signalen die voorkomen bij kwetsbare groepen. Bijvoorbeeld laaggeletterden of
                        jongeren met beperkingen die de overstap maken van school naar werk. Bij dergelijke signalen 
                        neemt de professional actie of draagt de klant warm over naar het juiste loket. De professional 
                        is zorgvuldig en concreet, zodat de klant zich begrepen en goed geholpen voelt.
                    </p>
                    ",
                'list' => "
                    <ul>
                        <li>
                            Houding
                            <ul>
                                <li>
                                    Vertrouwenwekkend
                                </li>
                                <li>
                                    Open
                                </li>
                            </ul>
                        </li>
                        <li>
                            Alert
                            <ul>
                                <li>
                                    Kennis van kwetsbare groepen
                                </li>
                                <li>
                                    Signaleert laaggeletterdheid
                                </li>
                                <li>
                                    Signaleert licht verstandelijke beperking
                                </li>
                            </ul>
                        </li>
                        <li>
                            Concreet
                            <ul>
                                <li>
                                    Warme overdracht
                                </li>
                            </ul>
                        </li>
                        <li>
                            Stem het taalniveau af op de inwoner
                        </li>
                    </ul>
                ",
                'crisis_description' => "
                    <p>
                        Persoonlijk contact is hierbij essentieel. Daarom is
                        het van belang dat het een laagdrempelige toegang
                        is, waar professionals een open houding hebben.
                    </p>
                    <p>
                        Zij noteren basisgegevens. De professionals zijn,
                        naast de direct voorliggende vraag, ook alert op
                        andere (acute) problemen en op kwetsbare groepen.
                    </p>
                    <p>
                        Bij dergelijke signalering onderneemt de professional
                        actie en/of verwijst door naar het juiste loket. In
                        geval iemand (mogelijk) tot de doelgroep
                        Banenafspraak behoort wordt hij/zij direct
                        doorverwezen naar het aanspreekpunt tijdelijke
                        impuls banenafspraak binnen het RMT.
                    </p>
                    <p>
                        De professional is zorgvuldig en concreet, zodat de
                        werkzoekende zich begrepen en goed geholpen
                        voelt.
                    </p>
                    <p>
                        Check op banenafspraak:
                        Dit kan lastig zijn, wanneer je twijfelt dan altijd direct
                        het aanspreekpunt banenafspraak binnen het RMT
                        raadplegen.
                    </p>",
                'crisis_services' => "
                    <p>
                        Bij het eerste contact vindt weging plaats of betrokkene
                        gebruik kan maken van de dienstverlening van het RMT.
                    </p>
                    <p>
                        <u>Harde criteria:</u> Voor iedereen geldt: na 12.03.2020
                        (bedreigd) werkloos en geen weging voor mensen uit de
                        Banenafspraak.
                    </p>
                    <p>
                        <u>Wegingscriteria (eerst verantwoordelijke bepaalt):</u>
                        <ul>
                            <li>Opleidingsniveau, leeftijd</li>
                            <li>Taalvaardigheid</li>
                            <li>Aantal jaar werkzaam in laatste funcie, arbeidsverleden</li>
                            <li>Afstand tot nieuw gewenst werk</li>
                            <li>Geringe kans op werkhervatting binnen één jaar</li>
                        </ul>
                    </p>
                    <p>
                        <i>Sociale partner: Van Werk naar Werk (VWNW)</i>
                        Uitvraag, opvang (emoXes) registratie in (eigen)
                        systeem. Check of betrokkene onder doelgroep
                        banenafspraak valt. Zo ja doorverwijzen naar
                        aanspreekpunt binnen RMT
                    </p>
                    <p>
                        <i>UWV: WW</i>
                        Uitvraag, opvang (emoties) registratie in (eigen)
                        systeem. Check op doelgroep banenafspraak.
                    </p>
                    <p>
                        <i>Gemeente: ZZP/ondernemer/NUG zonder werk/Bijstandsgerechtigde</i>
                        Uitvraag, opvang (emoties) registratie in (eigen) systeem
                    </p>
                ",
                'key' => 'melding',
                'position' => [
                    'x' => 0,
                    'y' => 1,
                ],
            ],
            [
                'code' => 'EW02',
                'name' => 'Diagnostiek',
                'sub_title' => 'Opstellen klantbeeld',
                'excerpt' => "Situatie bespreken, klantbeeld vormen en hulpvraag verhelderen. Op basis van dit gesprek samen tot een plan van aanpak met wat nodig is om weer duurzaam aan het werk te gaan. Melding en diagnostiek kunnen in één gesprek.",
                'description' => "
                    <p>
                        Bij de intake gaat de professional dieper in op de hulpvraag van de klant. De professional 
                        verdiept zich in en maakt een analyse van de informatie die al bekend is en bespreekt de huidige 
                        situatie met de klant op de verschillende levensgebieden, mogelijkheden, belemmeringen, 
                        motivatie, drijfveren, werk- en opleidingsachtergrond. Daarna stelt de professional een (eerste)
                        klantbeeld op.
                    </p>
                    <p>
                        Op basis van één of meer (1 tot 4) gesprekken wordt het klantbeeld gevormd en zet de 
                        professional instrumenten in voor de klant die bijvoorbeeld het vertrouwen in eigen versterken
                        en daarmee perspectief bieden op (arbeids)participatie. Het klantbeeld is niet statisch, maar
                        continu in ontwikkeling door bijvoorbeeld nieuwe informatie, gebeurtenissen in het leven van de 
                        klant en/of door de inzet van instrumenten. Na de inzet van een instrument evalueert de 
                        professional samen met de klant wat het effect is op het klantbeeld en past deze aan. Vervolgens
                        wordt op basis van het nieuwe klantbeeld bepaald wat een volgend passend instrument is.
                    </p>",
                'list' => "
                    <ul>
                        <li>
                            Houding: Vertrouwenwekkend
                            <ul>
                                <li>
                                    Hulpvraag klant verhelderen, eventueel met gevalideerde diagnose-instrumenten
                                    <ul>
                                        <li>
                                            Motivatie en onderliggende factoren, perspectief, persoonskenmerken, affiniteiten
                                        </li>
                                        <li>
                                            Vaardigheden (basisvaardigheden, werknemersvaardigheden, vakvaardigheden, ervaring, opleiding, etc.)
                                        </li>
                                        <li>
                                            Belemmeringen (fysiek, mental of praktisch)
                                        </li>
                                        <li>
                                            Het beeld dat de klant van zichzelf heeft
                                        </li>
                                        <li>
                                            De urgentie van de belemmering(en)
                                        </li>
                                        <li>
                                            Snelheid van verwacht resultaat
                                        </li>
                                        <li>
                                            Eenvoudige/haalbare doelen en interventies
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>
                            Klantbeeld vormen
                            <ul>
                                <li>
                                    Plan van aanpak opstellen en perspectief. Kijk daarvoor naar de situatie en levensgebieden
                                </li>
                                <li>
                                    Klantprofiel aanpassen na inzetten van interventies/instrumenten: hebben deze het verwachte effect?
                                </li>
                            </ul>
                        </li>
                    </ul>
                ",
                'crisis_description' => "
                    <p>
                        Bij de diagnostiek wordt de hulpvraag van werkzoekende
                        verhelderd door een brede uitvraag.
                    </p>
                    <p>
                        De professional verdiept zich en bespreekt met de
                        werkzoekende zijn/haar huidige situatie, werk- en
                        opleidings-achtergrond, maar ook motivatie, drijfveren
                        competenties en evt belemmeringen. Tevens bespreken
                        zij de (ontwikkel-)wensen, haalbaarheid en wat concreet
                        nodig is om weer duurzaam aan het werk te gaan.
                    </p>
                    <p>
                        Bovenstaande wordt vastgelegd in een plan van aanpak
                        en interventies worden ingezet om te komen tot een
                        realistisch perspectief op arbeidsmarkt.
                    </p>
                    <p>
                        Na inzet van een interventie, evalueren professional en
                        werkzoekende het effect en wordt bepaald of en welke
                        volgende interventie passend is.
                    </p>",
                'crisis_services' => "
                    <p>
                        <u>Benodigd: </u>
                        <ul>
                            <li>
                                Diagnose tools (waaronder taaltoets, ZRM matrix:
                                <a href='https://www.zelfredzaamheidmatrix.nl/'>https://www.zelfredzaamheidmatrix.nl/</a>)
                            </li>
                            <li>
                                Menukaart met contactadressen om interventies in te kunnen zetten
                            </li>
                            <li>
                                Sociale kaart met contactadressen om door
                                te kunnen verwijzen/overleg.
                                Denk aan bijvoorbeeld schuldhulpverlening,
                                huisarts, sociaal team, GGD, heroriëntatie,
                                leerwerkloket
                            </li>
                        </ul>
                    </p>
                ",
                'key' => 'diagnostiek',
                'position' => [
                    'x' => 1,
                    'y' => 1,
                ],
            ],
            [
                'code' => 'EW03',
                'name' => 'Maatschappelijk fit',
                'sub_title' => 'Meedoen, in balans zijn en participeren',
                'excerpt' => "De basis op orde en meedoen in de maatschappij",
                'description' => "
                    <p>
                        Om naar vermogen mee te kunnen doen in de maatschappij, moeten er een aantal leefgebieden in de
                        basis op orde zijn. De Zelfredzaamheidmatrix (ZRM) geeft een overzicht van de leefgebieden die
                        kunnen worden uitgevraagd. De diagnostische informatie is de basis voor verdiepende gesprekken.
                        Hierbij is er aandacht voor de basisvaardigheden, want deze zijn noodzakelijk om maatschappelijk 
                        mee te kunnen doen. Als het nodig is, wordt integrale samenwerking gezocht in het brede sociaal 
                        domein en met andere lokale maatschappelijke partners.
                    <p>
                        Maatschappelijk fit kan een opstap zijn naar betaald werk, maar betaald werk is niet voor 
                        iedereen haalbaar. Dan is participatie het hoogst haalbare. Bijdragen naar vermogen vereist 
                        zorgvuldig bepalen wat haalbaar is.
                    </p>",
                'list' => "
                    <ul>
                        <li>
                            Stabiliseren van urgente problemen en belemmeringen
                        </li>
                        <li>
                            Kort en snel hulp bieden om praktische belemmeringen op te lossen. Dat wekt vertrouwen en versterkt de participatie
                        </li>
                        <li>
                            Basisvaardigheden (taal, rekenen, sociale vaardigheden en digitale vaardigheden)
                        </li>
                        <li>
                            Financiële situatie (schuldhulp, voedselbank, voorzieningencheck) stresssensitieve begeleiding
                        </li>
                        <li>
                            Inzet van participatie-instrumenten
                            <ul>
                                <li>
                                    Vrijwilligerswerk
                                </li>
                                <li>
                                    Dagbesteding
                                </li>
                                <li>
                                    Mantelzorg
                                </li>
                            </ul>
                        </li>
                        <li>
                            Gebruik het perspectief van de inwoner om een eerste stap te doen in de richting van maatschappelijk fit
                        </li>
                        <li>
                            Blijf alert op het mogelijke ontwikkelperspectief
                        </li>
                    </ul>
                ",
                'crisis_description' => "
                    <p>
                        Om naar vermogen mee te kunnen doen in de
                        maatschappij, zijn er een aantal leefgebieden die in
                        de basis op orde moeten zijn. De ZRM gee? een
                        overzicht van deze leefgebieden die kunnen worden
                        uitgevraagd.
                    </p>
                    <p>
                        De diagnostische informatie vormt hiervoor de basis.
                        Hierbij is er aandacht voor de basisvaardigheden
                        (taal, rekenen en digi vaardigheden) deze zijn
                        noodzakelijk om maatschappelijk mee te kunnen
                        doen. Maar er is ook aandacht voor de financiën,
                        mentale gezondheid en praktische belemmeringen.
                        Als het nodig is, wordt integrale samenwerking
                        gezocht in het brede sociaal domein en met andere
                        lokale maatschappelijke partners.
                    </p>
                    ",
                'crisis_services' => "
                    <p>
                        Houd rekening met de situatie waar zij uit vandaan
                        komen. Nog aan het werk, recent werkloos of een
                        ondernemer die al lange tijd in stress en financiële nood
                        zit alvorens hij/zij om hulp vraagt. Voor deze mensen is
                        het belangrijk dat professionele hulp met een groot
                        netwerk en instrumentatrium binnen handbereik is.
                    </p>
                    <p>
                        <u>Benodigd: </u>
                        <ul>
                            <li>
                                Sociale kaart met contactadressen om door te
                                kunnen verwijzen/overleg.
                                Denk aan bijvoorbeeld schuldhulpverlening,
                                zelfstandigenloket, wijkteams, 113,
                            </li>
                            <li>
                                Om naar vermogen mee te kunnen doen in de
                                maatschappij, zijn er een aantal leefgebieden die in
                                de basis op orde moeten zijn. De Zelfredzaamheidmatrix
                                geeft een overzicht van deze leefgebieden die
                                kunnen worden uitgevraagd.<br>
                                <a href='https://www.zelfredzaamheidmatrix.nl/'>https://www.zelfredzaamheidmatrix.nl/</a>
                            </li>
                        </ul>
                    </p>
                ",
                'key' => 'maatschappelijk_fit',
                'position' => [
                    'x' => 2,
                    'y' => 0,
                ],
            ],
            [
                'code' => 'EW04',
                'name' => 'Werk fit',
                'sub_title' => 'klaar voor werk',
                'excerpt' => "Het doel van Werk-fit is de werkzoekende te versterken om betaald werk of een opleiding te kunnen aanvaarden en behouden/afmaken. Belangrijk is het versterken van vertrouwen in eigen kunnen.",
                'description' => "
                    <p>
                        Het doel van werk fit is klanten te versterken om betaald werk of een opleiding te kunnen doen.
                    </p>
                    <p>
                        Op basis van het klantbeeld wordt gewerkt aan verschillende vaardigheden, aan het verder 
                        ontwikkelen van talenten en aan het omgaan met/oplossen van belemmeringen. Dit gebeurt vanuit
                        een eerstvolgende haalbare stap in de ontwikkeling die de klant kan maken en kan parallel lopen
                        met de tegels Oriënteren en Opleiden (leerwerktrajecten). Belangrijk is dat de klant deze stap
                        succesvol behaalt, wat bijdraagt aan het versterken van het zelfvertrouwen. Waar mogelijk wordt 
                        het leren van de verschillende vaardigheden geïntegreerd met (onbetaald) werk.
                    </p>
                    <p>
                        Voortdurend wordt het klantbeeld uitgebreid, aangepast en vastgelegd.
                    </p>",
                'list' => "
                    <ul>
                        <li>
                            Blijvend werken aan perspectief, dat is de motor
                        </li>
                        <li>
                            Werken aan vaardigheden
                            <ul>
                                <li>
                                    Werknemersvaardigheden
                                </li>
                                <li>
                                    Vakvaardigheden
                                </li>
                                <li>
                                    Sollicitatievaardigheden
                                </li>
                                <li>
                                    Presentatievaardigheden
                                </li>
                            </ul>
                        </li>
                        <li>
                            Werken aan motivatie
                            <ul>
                                <li>
                                    Vertrouwen in eigen kunnen vergroten
                                </li>
                                <li>
                                    Inzet van eigen netwerk door persoon zelf
                                </li>
                                <li>
                                    Het belang van werk
                                </li>
                            </ul>
                        </li>
                        <li>
                            Werken aan volharding
                            <ul>
                                <li>
                                    Probleemoplossend vermogen vergroten
                                </li>
                                <li>
                                    Werk met leerdoelen in plaats van prestatiedoelen
                                </li>
                            </ul>
                        </li>
                        <li>
                            Werken aan belemmeringen
                            <ul>
                                <li>
                                    Jobcoaching
                                </li>
                                <li>
                                    Mentale en fysieke gezondheid
                                </li>
                                <li>
                                    Vervoersproblemen
                                </li>
                                <li>
                                    Opvang voor zorgtaken
                                </li>
                            </ul>
                        </li>
                    </ul>
                ",
                'crisis_description' => "
                    <p>
                        Het doel van Werk-fit is werkzoekenden te versterken om
                        betaald werk of een opleiding te kunnen aanvaarden en
                        behouden/afmaken.
                    </p>
                    <p>
                        Op basis van de diagnostische informatie, wordt gewerkt
                        aan: werknemers-, vak-presentatie,
                        sollicitatievaardigheden en het omgaan met/oplossen van
                        belemmeringen. Dat kan parallel lopen met
                        dienstverleningstegels Oriënteren en Opleiden (werkleertrajecten).
                    </p>
                    <p>
                        Dit gebeurt vanuit een perspectief op de eerstvolgende
                        haalbare stap die de persoon kan maken. Belangrijk is het
                        versterken van het zelfvertrouwen. Waar mogelijk wordt
                        het leren van de verschillende vaardigheden geïntegreerd
                        met (onbetaald) werk.
                    </p>
                    <p>
                        Voortdurend wordt de diagnostisch informatie uitgebreid,
                        aangepast en vastgelegd.
                    </p>",
                'crisis_services' => "
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    <u>Versterken arbeidsmarktpositie</u>
                                    De persoon wordt ondersteund om
                                    belemmeringen richting
                                    werkhervatting weg te nemen
                                </td>
                                <td>
                                    € 3.280,- ex btw
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <u>Sollicitatievaardigheden</u>
                                    De persoon leert effectief te
                                    solliciteren
                                </td>
                                <td>
                                    € 2.080,- ex btw
                                </td>
                            </tr>
                            <tr>
                                <td colspan='2'>
                                    <u>Dienstverlening Werkfitbehoud voor doelgroep Banenafspraak</u>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Dienstverlening om de doelgroep
                                    banenafspraak werkfit te houden
                                </td>
                                <td>
                                    Zie regeling
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Werkfitplekken bij
                                    gemeenten/sociale
                                    ontwikkelbedrijven voor doelgroep
                                    banenafspraak . Max 9 mnd, na 3
                                    mnd verlenging
                                </td>
                                <td>
                                    per drie maanden: € 1250,-.
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Jobcoaching ten behoeve van
                                    werkfitbehoud of werk-naar-werkbegeleiding
                                </td>
                                <td>
                                    Zie regeling, max
                                    3000,- meerkosten
                                </td>
                            </tr>
                        </tbody>
                    </table>
                ",
                'key' => 'werk_fit',
                'position' => [
                    'x' => 2,
                    'y' => 1,
                ],
            ],
            [
                'code' => 'EW05',
                'name' => 'Oriëntatie',
                'sub_title' => 'Op de arbeidsmarkt',
                'excerpt' => "Met de werkzoekende wordt onderzocht wat een passende loopbaan is en wat de kansen zijn op de hedendaagse arbeidsmarkt.",
                'description' => "
                    <p>
                        Zowel de arbeidsmarkt als klanten ontwikkelen zich. Bij deze tegel onderzoekt de professional 
                        samen met de klant wat een passende (loop)baan is en wat de kansen zijn op de huidige 
                        arbeidsmarkt. Dit kan door middel van testen, stages, netwerkgesprekken, oefeningen, huiswerk
                        (opgaven) en gebeurt vaak stapsgewijs. De uitkomst biedt een richting en perspectief om werk of
                        een opleiding te vinden en wordt daarmee onderdeel van het klantbeeld.
                    </p>
                    ",
                'list' => "
                    <ul>
                        <li>
                            Concretiseren van het perspectief
                            <ul>
                                <li>
                                    Testen (competenties, capaciteiten en leervermogen, affiniteiten, beroepen)
                                </li>
                                <li>
                                    Mogelijkheden van functies en bedrijven zoeken
                                </li>
                                <li>
                                    Inzicht in (groei)mogelijkheden die een functie/bedrijf kan bieden
                                </li>
                                <li>
                                    Begeleidingsgesprekken en benadrukken dat een baan een stap kan zijn naar een volgende baan
                                </li>
                                <li>
                                    Werkervaringsplekken/stages om het werk en de werkomgeving te ervaren
                                </li>
                            </ul>
                        </li>
                    </ul>
                ",
                'crisis_description' => "
                    <p>
                        Zowel de arbeidsmarkt als werkzoekenden
                        ontwikkelen zich.
                    </p>
                    <p>
                        In deze stap wordt met de werkzoekende onderzocht
                        wat een passende loopbaan voor hem/haar is en wat
                        de kansen zijn op de hedendaagse arbeidsmarkt. Dit
                        kan door middel van testen, stages,
                        netwerkgesprekken en vaak stapsgewijs.
                    </p>
                    <p>
                        Het start met een passende functie, vervolgens wordt
                        verkend wat er nodig is om daar te komen.
                        Solliciteren, opleiding, leerwerktraject of een eigen
                        onderneming. Ook wordt verkend welke
                        mogelijkheden de regio hiervoor heeft.
                    </p>
                    <p>
                        De uitkomst biedt een richting en perspectief om werk
                        of een opleiding te vinden.
                    </p>",
                'crisis_services' => "
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    <u>Arbeidsmarkt oriëntatie en loopbaanbegeleiding</u>
                                    De persoon heeft inzicht in zijn
                                    kwaliteiten en vaardigheden en
                                    daarbij passende kansrijke
                                    beroepsrichting waar
                                    mogelijkheden tot werkhervatting
                                    zijn.
                                </td>
                                <td>
                                    € 3.280,- ex btw
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p>
                        Perspectief betekent opleiding, werk in loondienst
                        of een eigen onderneming. Het kan ook betekenen
                        dat de ondernemer in nood, coaching nodig heeft
                        en zijn eigen bedrijf weer levensvatbaar te maken.
                    </p>
                    <p>
                        <u>Benodigd: </u>
                        <ul>
                            <li>
                                Overzicht met in te zetten interventies als:
                                <ul>
                                    <li>
                                        Loopbaantesten
                                    </li>
                                    <li>
                                        Oriëntatie carrousels
                                    </li>
                                    <li>
                                        Leerwerktrajecten, opleidingen, stages
                                    </li>
                                    <li>
                                        Loopbaan coaching
                                    </li>
                                    <li>
                                        Ondernemers coaching
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </p>
                ",
                'key' => 'orientatie',
                'position' => [
                    'x' => 2,
                    'y' => 2,
                ],
            ],
            [
                'code' => 'EW06',
                'name' => 'Opleiden',
                'sub_title' => 'Kwalificeren',
                'excerpt' => "Een ‘leven lang leren’ is onvermijdelijk geworden door de continue veranderende arbeidsmarkt. Om aan het werk te komen en blijven is (bij-)scholing noodzakelijk.",
                'description' => "
                    <p>
                        Taken en functies veranderen door de continu veranderende arbeidsmarkt. Hierdoor is een
                        ‘leven lang leren’ noodzakelijk geworden. Om aan het werk te komen en blijven is (bij)scholing
                        nodig.
                    </p>
                    <p>
                        Opleiden kan in verschillende vormen:
                        • Diplomeren: het behalen van een mbo-beroepsopleiding via BBL of BOL, hbo of universiteit.
                        • Certificeren: het halen van een certificaat, door het behalen van een vastgesteld deel opleiding gekoppeld aan een kwalificatiedossier. Dit is altijd in samenwerking met het bedrijfsleven.
                        • Praktijkverklaring: erkennen van vaardigheden doordat deze zijn aangetoond in de praktijk.
                        • Leerwerktraject: werken en leren combineren bij een sociaal ontwikkelbedrijf.
                    </p>
                    ",
                'list' => "
                    <ul>
                        <li>
                            Certificaat (VCA, heftruck, etc)
                        </li>
                        <li>
                            Diploma’s (MBO, aangepast MBO, BBL, BOL)
                        </li>
                        <li>
                            Leerwerktrajecten
                        </li>
                        <li>
                            Praktijkleren in het MBO (praktijkverklaring)
                        </li>
                        <li>
                            Open Badges en Edubadges
                        </li>
                    </ul>
                ",
                'crisis_description' => "
                    <p>
                        Opleiden kan in verschillende vormen:
                        <ul>
                            <li>
                                <u>Diplomeren</u>; het behalen van een MBO-diploma via BBL, BOL, of
                                derde leerweg óf het behalen van een HBO of universitair diploma.
                            </li>
                            <li>
                                <u>Certificeren</u>; het halen van een mbo-certificaat, op basis een onderdeel van een
                                mbo-opleiding, waaraan de Minister van OCW een certificaat heeft verbonden. Dit leert de
                                kandidaat door het uitvoeren van een aantal werkprocessen in het leerbedrijf, aangevuld
                                met bijbehorende lessen en een examen. Of het behalen van een branchecertificaat.
                            </li>
                            <li>
                                <u>Praktijkverklaring</u>; Dit betreft praktijkleren op maat, waarbij in de praktijk van
                                het leerbedrijf delen (werkprocessen) uit mbo-opleidingen worden geleerd op basis van de
                                mogelijkheden van de kandidaat en het bedrijf.
                            </li>
                            <li>
                                Voor opleiden via het mbo is altijd een stage of leerbaan in
                                <a href='https://www.s-bb.nl/bedrijven/erkenning/leerbedrijf-worden'>
                                    een erkend leerbedrijf
                                </a>
                                nodig. Vind in
                                <a href='http://www.leerbanenmarkt.nl/'>
                                    Leerbanenmarkt
                                </a>
                                openstaande leerbanen.
                            </li>
                        </ul>
                    </p>
                    <p>
                        Extra voorwaarden
                        <a href='https://zoek.officielebekendmakingen.nl/stcrt-2021-15327.html#d17e1174'>
                            subsidiemaatregel Praktijkleren in het MBO:
                        </a>
                        <ul>
                            <li>Doelgroep (bedreigd) werkloos vanaf 12-3-2020</li>
                            <li>Voldoende budget (reserveringsnummer aanvragen)</li>
                            <li>Duur opleiding is max 9 mnd</li>
                            <li>Drie overeenkomsten: Praktijk-, onderwijs- en plaatsingsovereenkomst</li>
                        </ul>
                    </p>
                    <p>
                        <a href='https://www.s-bb.nl/sites/sbb/files/uploads/factsheetmeerwaardevanpraktijklereninhetmbo.pdf'>
                            Meerwaarde opleiden via het mbo
                        </a><br>
                        <a href='https://www.s-bb.nl/sites/sbb/files/uploads/sbb-infographic-opleiden-medewerkers-mbo-design-v5-interactief.pdf'>
                            Informatie voor werkgevers over opleiden via het mbo
                        </a><br>
                        <a href='https://onderwijsenexaminering.nl/app/uploads/Handreiking-derde-leerweg-2020.pdf'>
                            Handreiking derde leerweg
                        </a><br>
                        <a href='https://www.s-bb.nl/samenwerking/sbb-helpt-u-verder/financiele-ondersteuning'>
                            Subsidiemogelijkheden
                        </a>
                    </p>
                    ",
                'crisis_services' => "
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    Mbo-opleiding gericht op het behalen van een praktijkverklaring
                                </td>
                                <td>
                                    Zie regeling € 750,– ex btw
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Mbo-opleidinggericht op het behalen van een certificaat
                                </td>
                                <td>
                                    Zie regeling € 1.750,– ex btw
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Mbo-opleiding gericht op het behalen van een diploma
                                </td>
                                <td>
                                    Zie regeling € 2.050,– ex btw
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    De persoon heeft cognitieve en praktische vaardigheden geleerd gericht op de
                                    uitoefening van een beroep of functie, dan wel werkzaamheden in de uitoefening van
                                    een bedrijf of in zelfstandige uitoefening van beroep en die leidt tot een door het
                                    Ministerie van Onderwijs, Cultuur en Wetenschap of branche/sector erkend
                                    <u>certificaat of diploma</u>.
                                </td>
                                <td>
                                    Scholing naar een beroep of functie: € 5.000,–, inclusief btw
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    De scholing bestaat uit het systematisch
                                    verwerven van kennis dan wel vaardigheden
                                    volgens een vooraf vastgesteld programma,
                                    waarbij de verworven kennis en
                                    vaardigheden worden getoetst.
                                </td>
                                <td>
                                    Functiegerichte vaardigheidstraining € 1750,- ex btw
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Begeleiding bij scholing: De persoon kan
                                    met extra begeleiding tijdens zijn
                                    scholingsperiode – naast die van het
                                    opleidingsinstituut –de scholing succesvol
                                    afronden.
                                </td>
                                <td>
                                    € 3.920,- ex btw
                                </td>
                            </tr>
                        </tbody>
                    </table>
                ",
                'key' => 'opleiden',
                'position' => [
                    'x' => 3,
                    'y' => 2,
                ],
            ],
            [
                'code' => 'EW07',
                'name' => 'Bemiddeling',
                'sub_title' => 'Kandidaten en vacatures koppelen',
                'excerpt' => "Het koppelen van kandidaten aan vacatures of werkgevers. Belangrijk is om in affiniteiten, competenties en leervermogen te denken.",
                'description' => "
                    <p>
                        De professional weegt het geheel aan diagnostische informatie, affiniteiten en competenties van 
                        de klant en koppelt deze aan vacatures en/of werkgevers. Vacatures kunnen al vacante plekken 
                        zijn, maar ook toekomstige vacatures. Toekomstige vacatures bieden meer kansen voor de doelgroep
                        van de participatiewet, omdat er voorbereidingstijd is. Toekomstige vacatures kunnen ontstaan
                        door groei van een bedrijf, aanpassing van bedrijfsprocessen en verwacht verloop door onder
                        andere pensionering en opleiding van het huidige personeel. Denk hierbij ook aan de details
                        zoals de match tussen persoonlijke aspecten/affiniteiten, werkomstandigheden en cultuur van het
                        bedrijf. Bij hetzelfde beroep kan de functie in het ene bedrijf anders zijn dan in het andere.
                    </p>
                    <p>
                        Belangrijk is om in affiniteiten, talenten, competenties en leervermogen te denken in plaats van
                        in behaalde opleidingen en werkervaring. Het is daarom belangrijk dat werkgevers vacatures ook
                        in competenties beschrijven.
                    </p>
                    ",
                'list' => "
                    <ul>
                        <li>
                            Matching op basis van competenties
                        </li>
                        <li>
                            Kandidaat kan zich presenteren in verschillende omstandigheden
                            <ul>
                                <li>
                                    Speeddates
                                </li>
                                <li>
                                    Verkennend gesprek
                                </li>
                                <li>
                                    Sollicitatiegesprek
                                </li>
                                <li>
                                    Ondersteunende begeleiding tijdens gesprek
                                </li>
                            </ul>
                        </li>
                        <li>
                            Werkgevers Service Punt (WSP)
                            <ul>
                                <li>
                                    Wederzijdse aansluiting tussen coaching en activiteiten WSP
                                </li>
                                <li>
                                    Relatieopbouw met private partijen
                                </li>
                                <li>
                                    Vraaggericht werken door het aanbieden van passende kandidaten
                                </li>
                                <li>
                                    Aanbodgericht werken door goede relaties en nazorg aan werkgevers
                                </li>
                            </ul>
                        </li>
                    </ul>
                ",
                'crisis_description' => "
                    <p>
                        Het geheel aan diagnostische informatie, affiniteiten en de competenties die zijn eigen gemaakt,
                        worden gewogen en gekoppeld aan vacatures en/of werkgevers. Bij de combinatie van werken en
                        leren biedt de werkgever <a href='https://www.leerbanenmarkt.nl/'>een leerbaan</a> aan.
                    </p>
                    <p>
                        Vacatures kunnen vacante plekken zijn, maar ook toekomstige. Toekomstige vacatures bieden meer
                        kans voor de doelgroep omdat er voorbereidingstijd is om in te leren. Toekomstige vacatures
                        kunnen ontstaan door groei van een bedrijf, aanpassing van bedrijfsprocessen en verwacht verloop
                        door o.a. pensionering, opleiding van huidig personeel. Voor de doelgroep banenafspraak kunnen
                        ook functies worden gecreëerd middels functie creactie.
                    </p>
                    <p>
                        Denk hierbij ook aan de details zoals de match tussen persoonlijke aspecten/affiniteiten,
                        werkomstandigheden en cultuur van het bedrijf. Bij hetzelfde beroep kan de functie in het ene
                        bedrijf anders zijn dan in het andere.
                    </p>
                    <p>
                        Belangrijk is om in affiniteiten, competenties en leervermogen te denken i.p.v. behaalde
                        opleidingen en werkervaring.
                    </p>",
                'crisis_services' => "
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    <u>Matching</u>
                                    De persoon wordt in contact gebracht met werkgevers met vraag naar personeel, door
                                    vacatures te zoeken en aan te bieden.
                                </td>
                                <td>
                                    € 3.200,- ex btw
                                </td>
                            </tr>
                        </tbody>
                    </table>
                ",
                'key' => 'bemiddeling',
                'position' => [
                    'x' => 3,
                    'y' => 1,
                ],
            ],
            [
                'code' => 'EW08',
                'name' => 'Plaatsing',
                'sub_title' => 'Werkelijk aan de slag',
                'excerpt' => "Het maken van plaatsingsafspraken met de werkgever en werknemer en aanvragen van ondersteunende voorzieningen om de kans op een duurzame match te vergroten.",
                'description' => "
                    <p>
                        De professional bespreekt op basis van de mogelijkheden van de klant en de plaatsingsafspraken
                        met de werkgever welke ondersteunde voorzieningen zorgen voor de beste kans van slagen op een
                        duurzame match. De professional heeft hierin een informerende, adviserende en bemiddelende rol.
                        De professional bespreekt daarbij de valkuilen en afbreekrisico’s met de werkgever en de klant.
                        Zij bespreken wat nodig is om de plaatsing duurzaam te laten slagen en maken afspraken over de
                        nazorg.
                    </p>
                    <p>
                        Belangrijk aandachtspunt is om de klant zoveel mogelijk te betrekken bij de afspraken.
                    </p>",
                'list' => "
                    <ul>
                        <li>
                            Praktijkroute
                        </li>
                        <li>
                            Loonkostensubsidie (LKS)
                        </li>
                        <li>
                            Proefplaatsing
                        </li>
                        <li>
                            Werkplekaanpassing
                        </li>
                        <li>
                            Jobcoaching
                        </li>
                        <li>
                            Welke strategie inzetten als het tegenzit
                        </li>
                    </ul>
                ",
                'crisis_description' => "
                    <p>
                        Er is sprake van een match. In deze stap wordt op basis
                        van de plaatsingsafspraken met de werkgever en
                        werknemer ondersteunende voorzieningen
                        aangevraagd voor een zo groot mogelijke kans van
                        slagen op een duurzame match.
                    </p>
                    <p>
                        De professional heeft hierin een informerende,
                        adviserende en bemiddelende rol.
                    </p>
                    <p>
                        De professional bespreekt de valkuilen en
                        afbreekrisico’s met de werkgever en de werknemer. Zij
                        bespreken wat nodig is om de plaatsing te laten slagen
                        en maken afspraken over de nazorg.
                    </p>
                    <p>
                        Belangrijk aandachtspunt is om de werknemer zoveel
                        mogelijk te betrekken bij de afspraken.
                    </p>
                    <p>
                        <u>Veel voorkomende instrumenten zijn:</u>
                        <ul>
                            <li>Proefplaatsing</li>
                            <li>Loonkostensubsidie (LKS)</li>
                            <li>Werkplekaanpassing</li>
                            <li>Jobcoaching</li>
                            <li>Welke strategie als het tegenzit </li>
                        </ul>
                    </p>",
                'crisis_services' => "
                    <table>
                        <tbody>
                            <tr>
                                <td>
                                    <u>Maatwerkbudget</u>
                                    Bedoeld voor additionele
                                    kosten die bij de inzet van
                                    dienstverlening gericht op
                                    werkhervatting worden
                                    gemaakt. (w.o. tegemoetkoming
                                    reiskosten).<br>
                                    Om een gerichte inzet te borgen
                                    vraagt de inzet van dit budget
                                    om gezamenlijke afstemming
                                    tussen de partijen in het
                                    regionale mobiliteitsteam.
                                </td>
                                <td>
                                    € 1000,- ex btw
                                </td>
                            </tr>
                        </tbody>
                    </table>
                ",
                'key' => 'plaatsing',
                'position' => [
                    'x' => 4,
                    'y' => 1,
                ],
            ],
            [
                'code' => 'EW09',
                'name' => 'Nazorg',
                'sub_title' => 'Voor duurzame plaatsing',
                'excerpt' => "Om de kans te vergroten dat de werknemer aan het werk blijft, is het van belang dat er nazorg wordt geboden als dat nodig is.",
                'description' => "
                    <p>
                        Om de kans te vergroten dat de klant aan het werk blijft, is het van belang dat er nazorg wordt 
                        geboden als dat nodig is. Dit kan gaan om ondersteuning aan de klant, de werkgever en/of 
                        collega’s. De duur, intensiteit en inhoud zijn afhankelijk van de behoefte. Dit kan variëren van 
                        monitoring door nabellen tot intensieve structurele begeleiding.
                    </p>
                    <p>
                        Sommigen gemeenten doen dit zelf, anderen kopen jobcoaching en/of andere ondersteuning in.
                    </p>
                    <p>
                        Voor werkgevers is de sleutel voor duurzame plaatsingen continuïteit, één aanspreekpunt, weinig 
                        wisselingen in de contactpersoon en snelle (re)actie in de nazorg.
                    </p>
                    <p>
                        Nazorg betekent ook tijdig handelen wanneer de plaatsing niet succesvol is, op zijn einde loopt
                        of wanneer de klant de werkplek ontgroeit.
                    </p>",
                'list' => "
                    <ul>
                        <li>
                            Afspraken over de duur
                        </li>
                        <li>
                            Bevorderen van de duurzame inzetbaarheid (DI)
                            <ul>
                                <li>
                                    Jobcoaching
                                </li>
                                <li>
                                    Ondersteuning werkgever
                                </li>
                                <li>
                                    Instrumenten DI
                                </li>
                            </ul>
                        </li>
                        <li>
                            Wees bereikbaar voor de werkgever, zorg voor één aanspreekpunt, reageer snel en met actie.
                        </li>
                        <li>
                            Plaats door als de werkplek niet geschikt blijkt
                        </li>
                    </ul>
                ",
                'crisis_description' => "
                    <p>
                        Dit kan gaan om ondersteuning aan de werknemer, de
                        werkgever en/of collega’s. De duur, intensiteit en inhoud
                        zijn arankelijk van de behoeWe. Dit kan variëren van
                        monitoring door nabellen tot intensieve structurele
                        begeleiding. Sommigen doen dit zelf, anderen kopen jobcoaching en/of andere ondersteuning in.
                    </p>
                    <p>
                        Voor werkgevers is continuïteit, één aanspreekpunt, weinig
                        wisselingen in de contactpersoon en snelle (re)actie in de
                        nazorg, de sleutel voor duurzame plaatsingen. Nazorg
                        betekent ook tijdig handelen wanneer de plaatsing niet
                        geschikt is, op zijn einde loopt of wanneer de werknemer
                        zijn werkplek ontgroeit.
                    </p>
                    <p>
                        <u>Veel voorkomende instrumenten zijn:</u>
                        <ul>
                            <li>Periodiek telefonisch/persoonlijk contact met werkgever en client</li>
                            <li>Jobcoaching</li>
                            <li>Jobcarving</li>
                            <li>Strategie als het tegenzit</li>
                        </ul>
                    </p>
                    ",
                'crisis_services' => "
                    <ul>
                        <li>
                            Spreek meerdere momenten af met
                            werkgever en client om voortgang te
                            bespreken. Bijv een week na plaatsing en
                            een maand na plaatsing. Reageer direct
                            indien nodig
                        </li>
                        <li>
                            Wees bereikbaar voor de werkgever,
                        </li>
                        <li>
                            Zorg voor één aanspreekpunt, reageer snel en met actie.
                        </li>
                        <li>
                            Plaats de werknemer door wanneer de
                            werkplek niet geschikt blijkt
                        </li>
                        <li>
                            Onderzoek de redenen waarom een
                            werkplek niet geschikt is
                        </li>
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
