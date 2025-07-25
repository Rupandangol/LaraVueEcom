<?php

namespace App\Providers;

use App\Events\OrderPlacedNotificationAdminSideEvent;
use App\Events\ProductQuantityUpdater;
use App\Listeners\OrderPlacedNotificationAdminSideListener;
use App\Listeners\ProductQuantityUpdaterListener;
use App\Models\Category;
use App\Models\Product;
use App\Observers\CategoryObserver;
use App\Observers\ProductObserver;
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
        ProductQuantityUpdater::class => [
            ProductQuantityUpdaterListener::class,
        ],
        OrderPlacedNotificationAdminSideEvent::class => [
            OrderPlacedNotificationAdminSideListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Category::observe(CategoryObserver::class);
        Product::observe(ProductObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
