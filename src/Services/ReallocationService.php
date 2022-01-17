<?php


namespace Vng\EvaCore\Services;

use Vng\EvaCore\Models\ClientCharacteristic;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\Neighbourhood;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\TargetGroup;
use Vng\EvaCore\Models\Tile;
use Vng\EvaCore\Models\Township;
use Vng\EvaCore\Traits\HasOwner;
use Vng\EvaCore\Traits\IsOwner;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ReallocationService
{
    public static function transferOwnership(Model $currentOwner, Model $newOwner)
    {
        static::testOwnerModel($currentOwner, 'currentOwner');
        static::testOwnerModel($newOwner, 'newOwner');
        $currentOwner->ownedInstruments->each(
            fn (Instrument $instrument) => static::changeModelOwner($instrument, $newOwner)
        );
        $currentOwner->ownedProviders->each(
            fn (Provider $provider) => static::changeModelOwner($provider, $newOwner)
        );
    }

    public static function changeModelOwner(Model $ownedModel, Model $newOwner)
    {
        static::testOwnedModel($ownedModel, 'ownedModel');
        static::testOwnerModel($newOwner, 'newOwner');
        $ownedModel->owner()->associate($newOwner);
        $ownedModel->save();
    }

    public static function copyOwnedItems(Model $currentOwner, Model $newOwner)
    {
        static::testOwnerModel($currentOwner, 'currentOwner');
        static::testOwnerModel($newOwner, 'newOwner');
        $currentOwner->ownedInstruments->each(
            fn (Instrument $instrument) => static::copyInstrumentIfNotExists($instrument, $newOwner)
        );
        $currentOwner->ownedProviders->each(
            fn (Provider $provider) => static::copyProviderIfNotExists($provider, $newOwner)
        );
    }

    private static function copyInstrumentIfNotExists(Instrument $instrument, Model $newOwner): Instrument
    {
        /** @var Instrument|null $existing */
        $existing = Instrument::query()->where([
            'name' => $instrument->name,
            'owner_id' => $newOwner->id,
            'owner_type' => get_class($newOwner)
        ])->first();
        if (!is_null($existing)) {
            return $existing;
        }

        $newInstrument = $instrument->replicate();
        $newInstrument->unsetRelations();
        DB::transaction(function() use ($instrument, $newInstrument, $newOwner) {
            $currentOwner = $instrument->owner;
            $newInstrument->owner()->associate($newOwner);
            $newInstrument->save();

            foreach ($instrument->providers as $provider) {
                if ($provider->isOwner($currentOwner)) {
                    $provider = static::copyProviderIfNotExists($provider, $newOwner);
                }
                $instrument->providers()->attach($provider->id);
            }

            $address = $instrument->address;
            if (!is_null($address)) {
                $newInstrument->address()->save($address->replicate());
            }

            foreach ($instrument->contacts as $contact) {
                $newInstrument->contacts()->save($contact->replicate());
            }

            $instrument->tiles->each(fn (Tile $tile) => $newInstrument->tiles()->attach($tile));
            $instrument->clientCharacteristics->each(fn (ClientCharacteristic $clientCharacteristic) => $newInstrument->clientCharacteristics()->attach($clientCharacteristic));
            $instrument->targetGroups->each(fn (TargetGroup $targetGroup) => $newInstrument->targetGroups()->attach($targetGroup));

            $instrument->availableRegions->each(fn (Region $region) => $newInstrument->availableRegions()->attach($region));
            $instrument->availableTownships->each(fn (Township $township) => $newInstrument->availableTownships()->attach($township));
            $instrument->availableNeighbourhoods->each(fn (Neighbourhood $neighbourhood) => $newInstrument->availableNeighbourhoods()->attach($neighbourhood));

            foreach ($instrument->links as $link) {
                $newInstrument->links()->save($link->replicate());
            }
            foreach ($instrument->videos as $video) {
                $newInstrument->videos()->save($video->replicate());
            }
        });

        return $newInstrument->fresh();
    }

    private static function copyProviderIfNotExists(Provider $provider, Model $newOwner): ?Provider
    {
        /** @var Provider|null $existing */
        $existing = Provider::query()->where([
            'name' => $provider->name,
            'owner_id' => $newOwner->id,
            'owner_type' => get_class($newOwner)
        ])->first();
        if (!is_null($existing)) {
            return $existing;
        }

        $newProvider = $provider->replicate(['uuid']);
        $newProvider->unsetRelations();
        DB::transaction(function() use ($provider, $newProvider, $newOwner) {
            $currentOwner = $provider->owner;
            $newProvider->owner()->associate($newOwner);
            $newProvider->save();

            foreach ($provider->instruments as $instrument) {
                if ($instrument->isOwner($currentOwner)) {
                    $instrument = static::copyInstrumentIfNotExists($instrument, $newOwner);
                }
                $provider->instruments()->attach($instrument->id);
            }

            $address = $provider->address;
            if (!is_null($address)) {
                $newProvider->address()->save($address->replicate());
            }

            $contact = $provider->contact;
            if (!is_null($contact)) {
                $newProvider->contact()->save($contact->replicate());
            }
        });
        return $newProvider->fresh();
    }

    private static function modelHasIsOwnerTrait(Model $model)
    {
        return in_array(IsOwner::class, class_uses(get_class($model)));
    }

    private static function testOwnerModel(Model $model, string $parameterName = null)
    {
        if (!static::modelHasIsOwnerTrait($model)) {
            $message = 'Invalid "IsOwner" model given';
            if (!is_null($parameterName)) {
                $message .= 'for ' . $parameterName . ' parameter';
            }
            throw new Exception($message);
        }
    }

    private static function modelHasHasOwnerTrait(Model $model)
    {
        return in_array(HasOwner::class, class_uses(get_class($model)));
    }

    private static function testOwnedModel(Model $model, string $parameterName = null)
    {
        if (!static::modelHasHasOwnerTrait($model)) {
            $message = 'Invalid "HasOwner" model given';
            if (!is_null($parameterName)) {
                $message .= 'for ' . $parameterName . ' parameter';
            }
            throw new Exception($message);
        }
    }
}
