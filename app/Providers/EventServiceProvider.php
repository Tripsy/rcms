<?php

namespace App\Providers;

use App\Models\Item;
use App\Models\ItemData;
use App\Models\ItemType;
use App\Models\ItemTypeLabel;
use App\Models\Project;
use App\Models\ProjectPermission;
use App\Observers\CreatedByObserver;
use App\Observers\UpdatedByObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
    ];

    /**
     * The model observers for your application.
     *
     * @var array
     */
    protected $observers = [
        Item::class => [
            CreatedByObserver::class,
            UpdatedByObserver::class,
        ],
        ItemData::class => [
            CreatedByObserver::class,
            UpdatedByObserver::class,
        ],
        Project::class => [
            CreatedByObserver::class,
            UpdatedByObserver::class,
        ],
        ProjectPermission::class => [
            CreatedByObserver::class,
            UpdatedByObserver::class,
        ],
        ItemType::class => [
            CreatedByObserver::class,
            UpdatedByObserver::class,
        ],
        ItemTypeLabel::class => [
            CreatedByObserver::class,
            UpdatedByObserver::class,
        ],
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
        return true; //TODO https://laravel.com/docs/10.x/events#event-discovery
    }
}
