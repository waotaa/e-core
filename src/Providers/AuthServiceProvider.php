<?php

namespace Vng\EvaCore\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Permission\Models\Permission;
use Vng\EvaCore\Models\Address;
use Vng\EvaCore\Models\ClientCharacteristic;
use Vng\EvaCore\Models\Contact;
use Vng\EvaCore\Models\Download;
use Vng\EvaCore\Models\Environment;
use Vng\EvaCore\Models\GroupForm;
use Vng\EvaCore\Models\Implementation;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\InstrumentTracker;
use Vng\EvaCore\Models\InstrumentType;
use Vng\EvaCore\Models\Link;
use Vng\EvaCore\Models\LocalParty;
use Vng\EvaCore\Models\Location;
use Vng\EvaCore\Models\Manager;
use Vng\EvaCore\Models\NationalParty;
use Vng\EvaCore\Models\Neighbourhood;
use Vng\EvaCore\Models\NewsItem;
use Vng\EvaCore\Models\Organisation;
use Vng\EvaCore\Models\Partnership;
use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Models\Rating;
use Vng\EvaCore\Models\Region;
use Vng\EvaCore\Models\RegionalParty;
use Vng\EvaCore\Models\RegistrationCode;
use Vng\EvaCore\Models\Role;
use Vng\EvaCore\Models\TargetGroup;
use Vng\EvaCore\Models\Tile;
use Vng\EvaCore\Models\Township;
use Vng\EvaCore\Models\Video;
use Vng\EvaCore\Policies\AddressPolicy;
use Vng\EvaCore\Policies\ClientCharacteristicPolicy;
use Vng\EvaCore\Policies\ContactPolicy;
use Vng\EvaCore\Policies\DownloadPolicy;
use Vng\EvaCore\Policies\EnvironmentPolicy;
use Vng\EvaCore\Policies\GroupFormPolicy;
use Vng\EvaCore\Policies\ImplementationPolicy;
use Vng\EvaCore\Policies\InstrumentPolicy;
use Vng\EvaCore\Policies\InstrumentTrackerPolicy;
use Vng\EvaCore\Policies\InstrumentTypePolicy;
use Vng\EvaCore\Policies\LinkPolicy;
use Vng\EvaCore\Policies\LocalPartyPolicy;
use Vng\EvaCore\Policies\LocationPolicy;
use Vng\EvaCore\Policies\ManagerPolicy;
use Vng\EvaCore\Policies\NationalPartyPolicy;
use Vng\EvaCore\Policies\NeighbourhoodPolicy;
use Vng\EvaCore\Policies\NewsItemPolicy;
use Vng\EvaCore\Policies\OrganisationPolicy;
use Vng\EvaCore\Policies\PartnershipPolicy;
use Vng\EvaCore\Policies\PermissionPolicy;
use Vng\EvaCore\Policies\ProfessionalPolicy;
use Vng\EvaCore\Policies\ProviderPolicy;
use Vng\EvaCore\Policies\RatingPolicy;
use Vng\EvaCore\Policies\RegionalPartyPolicy;
use Vng\EvaCore\Policies\RegionPolicy;
use Vng\EvaCore\Policies\RegistrationCodePolicy;
use Vng\EvaCore\Policies\RolePolicy;
use Vng\EvaCore\Policies\TargetGroupPolicy;
use Vng\EvaCore\Policies\TilePolicy;
use Vng\EvaCore\Policies\TownshipPolicy;
use Vng\EvaCore\Policies\VideoPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Address::class => AddressPolicy::class,
        ClientCharacteristic::class => ClientCharacteristicPolicy::class,
        Contact::class => ContactPolicy::class,
        Download::class => DownloadPolicy::class,
        Environment::class => EnvironmentPolicy::class,
        GroupForm::class => GroupFormPolicy::class,
        Implementation::class => ImplementationPolicy::class,
        Instrument::class => InstrumentPolicy::class,
        InstrumentTracker::class => InstrumentTrackerPolicy::class,
        InstrumentType::class => InstrumentTypePolicy::class,
        Link::class => LinkPolicy::class,
        LocalParty::class => LocalPartyPolicy::class,
        Location::class => LocationPolicy::class,
        Manager::class => ManagerPolicy::class,
        NationalParty::class => NationalPartyPolicy::class,
        Neighbourhood::class => NeighbourhoodPolicy::class,
        NewsItem::class => NewsItemPolicy::class,
        Organisation::class => OrganisationPolicy::class,
        Partnership::class => PartnershipPolicy::class,
        Permission::class => PermissionPolicy::class,
        Professional::class => ProfessionalPolicy::class,
        Provider::class => ProviderPolicy::class,
        Rating::class => RatingPolicy::class,
        RegionalParty::class => RegionalPartyPolicy::class,
        Region::class => RegionPolicy::class,
        RegistrationCode::class => RegistrationCodePolicy::class,
        Role::class => RolePolicy::class,
        TargetGroup::class => TargetGroupPolicy::class,
        Tile::class => TilePolicy::class,
        Township::class => TownshipPolicy::class,
        Video::class => VideoPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
