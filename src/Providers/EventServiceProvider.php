<?php

namespace Vng\EvaCore\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Vng\EvaCore\Listeners\ElasticResourceEventSubscriber;
use Vng\EvaCore\Listeners\InstrumentEventSubscriber;
use Vng\EvaCore\Models\Address;
use Vng\EvaCore\Observers\AddressObserver;
use Vng\EvaCore\Models\Contact;
use Vng\EvaCore\Models\Contactables;
use Vng\EvaCore\Models\Download;
use Vng\EvaCore\Models\GroupForm;
use Vng\EvaCore\Models\Implementation;
use Vng\EvaCore\Models\Instrument;
use Vng\EvaCore\Models\InstrumentProvider;
use Vng\EvaCore\Models\Link;
use Vng\EvaCore\Models\NewsItem;
use Vng\EvaCore\Models\Professional;
use Vng\EvaCore\Models\Provider;
use Vng\EvaCore\Models\Rating;
use Vng\EvaCore\Models\RegistrationCode;
use Vng\EvaCore\Models\User;
use Vng\EvaCore\Models\Video;
use Vng\EvaCore\Observers\ContactablesObserver;
use Vng\EvaCore\Observers\ContactObserver;
use Vng\EvaCore\Observers\DownloadObserver;
use Vng\EvaCore\Observers\GroupFormObserver;
use Vng\EvaCore\Observers\ImplementationObserver;
use Vng\EvaCore\Observers\InstrumentObserver;
use Vng\EvaCore\Observers\InstrumentProviderObserver;
use Vng\EvaCore\Observers\LinkObserver;
use Vng\EvaCore\Observers\NewsItemObserver;
use Vng\EvaCore\Observers\ProfessionalObserver;
use Vng\EvaCore\Observers\ProviderObserver;
use Vng\EvaCore\Observers\RatingObserver;
use Vng\EvaCore\Observers\RegistrationCodeObserver;
use Vng\EvaCore\Observers\UserObserver;
use Vng\EvaCore\Observers\VideoObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        ElasticResourceEventSubscriber::class,
        InstrumentEventSubscriber::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        $this->registerObservers();
    }

    private function registerObservers()
    {
        Address::observe(AddressObserver::class);
        Contactables::observe(ContactablesObserver::class);
        Contact::observe(ContactObserver::class);
        Download::observe(DownloadObserver::class);
        GroupForm::observe(GroupFormObserver::class);
        Implementation::observe(ImplementationObserver::class);
        Instrument::observe(InstrumentObserver::class);
        InstrumentProvider::observe(InstrumentProviderObserver::class);
        Link::observe(LinkObserver::class);
        NewsItem::observe(NewsItemObserver::class);
        Professional::observe(ProfessionalObserver::class);
        Provider::observe(ProviderObserver::class);
        Rating::observe(RatingObserver::class);
        RegistrationCode::observe(RegistrationCodeObserver::class);
        Video::observe(VideoObserver::class);
    }
}
