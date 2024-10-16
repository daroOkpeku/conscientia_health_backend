<?php

namespace App\Providers;

use App\Console\Commands\Existingpatientemail;
use App\Events\BookingAdminEvent;
use App\Events\BookingEvent;
use App\Events\Contactevent;
use App\Events\ForgotPasswordEvent;
use App\Events\patientEvent;
use App\Events\RegisterEvent;
use App\Events\Sentotpevent;
use App\Events\ExistinguserEmailEvent;
use App\Listeners\ExistinguserEmailListener;
use App\Listeners\BookingAdminListener;
use App\Listeners\BookingListener;
use App\Listeners\Contactlistener;
use App\Listeners\ForgotPasswordListener;
use App\Listeners\patientListener;
use App\Listeners\RegisterListener;
use App\Listeners\SentotpListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        RegisterEvent::class =>[
            RegisterListener::class
        ],
        Sentotpevent::class =>[
           SentotpListener::class
        ],
        ForgotPasswordEvent::class =>[
            ForgotPasswordListener::class
        ],

        BookingEvent::class =>[
            BookingListener::class
        ],
        BookingAdminEvent::class =>[
            BookingAdminListener::class
        ],
        Contactevent::class=>[
            Contactlistener::class
        ],
        patientEvent::class=>[
            patientListener::class
        ],
        ExistinguserEmailEvent::class=>[
            ExistinguserEmailListener::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
