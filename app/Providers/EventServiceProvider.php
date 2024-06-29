<?php

namespace App\Providers;

use App\Listeners\BlueprintComponentSubscriber;
use App\Listeners\ItemSubscriber;
use App\Listeners\ProjectBlueprintSubscriber;
use App\Listeners\ProjectPermissionSubscriber;
use App\Listeners\ProjectSubscriber;
use App\Listeners\TagsSubscriber;
use App\Models\BlueprintComponent;
use App\Models\Item;
use App\Models\Project;
use App\Models\ProjectBlueprint;
use App\Models\ProjectPermission;
use App\Models\Tag;
use App\Observers\BlueprintComponentObserver;
use App\Observers\ItemObserver;
use App\Observers\ProjectBlueprintObserver;
use App\Observers\ProjectObserver;
use App\Observers\ProjectPermissionObserver;
use App\Observers\TagsObserver;
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
     * The subscriber classes to register.
     *
     * Event subscribers are classes that may subscribe to multiple events from within the subscriber class itself,
     * allowing you to define several event handlers within a single class. Subscribers should define a subscribe
     * method, which will be passed an event dispatcher instance. You may call the listen method on the given
     * dispatcher to register event listeners.
     *
     * @var array
     */
    protected $subscribe = [
        ProjectSubscriber::class,
        ProjectPermissionSubscriber::class,
        ProjectBlueprintSubscriber::class,
        BlueprintComponentSubscriber::class,
        TagsSubscriber::class,
        ItemSubscriber::class,
    ];

    /**
     * The model observers for your application.
     *
     * If you are listening for many events on a given model, you may use observers to group all of your listeners
     * into a single class. Observer classes have method names which reflect the Eloquent events you wish to
     * listen for. Each of these methods receives the affected model as their nly argument.
     * The make:observer Artisan command is the easiest way to create a new observer class:
     *
     * $ php artisan make:observer UserObserver --model=User
     *
     * @var array
     */
    protected $observers = [
        Project::class => [
            ProjectObserver::class,
        ],
        ProjectPermission::class => [
            ProjectPermissionObserver::class,
        ],
        ProjectBlueprint::class => [
            ProjectBlueprintObserver::class,
        ],
        BlueprintComponent::class => [
            BlueprintComponentObserver::class,
        ],
        Tag::class => [
            TagsObserver::class,
        ],
        Item::class => [
            ItemObserver::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}
