<?php

namespace Vng\EvaCore\ElasticResources\FormerStructure;

use Vng\EvaCore\ElasticResources\DownloadResource;
use Vng\EvaCore\ElasticResources\ElasticResource;
use Vng\EvaCore\ElasticResources\LinkResource;
use Vng\EvaCore\ElasticResources\VideoResource;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\Former\Gemeente;
use Vng\EvaCore\Models\Former\Regio;

class InstrumentResource extends ElasticResource
{
    public function toArray()
    {
        $owner = null;
        switch ($this->owner_type) {
            case 'App\Models\Regio':
                $owner = Regio::find($this->owner_id);
            break;
            case 'App\Models\Gemeente':
                $owner = Gemeente::find($this->owner_id);
            break;
            case 'App\Models\Environment':
                $owner = Environment::find($this->owner_id);
            break;
        }

        return [
            'uuid' => $this->uuid,
            'did' => $this->did,

            'name' => $this->naam,
            'is_active' => (bool) $this->status,
            'is_nationally_available' => (bool) $this->regios->contains(function($regio) {
                return $regio->naam = 'Landelijk';
            }),

            // descriptions
            'short_description' => $this->abstract,
            'description' => $this->beschrijving,
            'application_instructions' => $this->aanmeldinstructie,
            'conditions' => $this->voorwaarden,
            'distinctive_approach' => $this->onderscheidende_aanpak,
            'cooperation_partners' => $this->samenwerkingspartners,

            // right sidebar
            'aim' => $this->doel,
            'verwijzingen' => $this->verwijzingen,

            // info section
            'costs' => $this->kosten,
            'costs_unit' => $this->kosten_eenheid,
            'duration' => $this->duur,
            'duration_unit' => $this->eenheid_duur,
            'op_locatie' => $this->op_locatie,

            // ...
            'contact_persoon_intern' => [
                'name' => $this->ci_naam,
                'phone' => $this->ci_telefoon,
                'email' => $this->ci_email,
            ],
            'contact_persoon_extern' => [
                'name' => $this->ce_naam,
                'phone' => $this->ce_telefoon,
                'email' => $this->ce_email,
            ],
            'address' => $this->ol_straat ? [
                'straatnaam' => $this->ol_straat,
                'huisnummer' => $this->ol_huisnummer,
                'postcode' => $this->ol_postcode,
                'woonplaats' => $this->ol_plaats,
            ] : null,

            // other
            'import_mark' => $this->import_mark,

            // relations
            'owner' => OwnerResource::one($owner),
            'providers' => AanbiederResource::many(collect([$this->aanbieder])),

            'regions' => RegioResource::many($this->regios),
            'townships' => GemeenteResource::many($this->gemeentes),

            'tiles' => TegelResource::many($this->tegels),
            'themes' => OmschrijvingResource::many($this->thema),
            'target_groups' => OmschrijvingResource::many($this->doelgroep),

            'links' => LinkResource::many($this->links),
            'videos' => VideoResource::many($this->videos),
            'downloads' => DownloadResource::many($this->downloads),
        ];
    }
}
