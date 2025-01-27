<?php

namespace App\Providers;

use App\Listeners\OrderPlacedNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
//        'App\Events\OrderCreated' => [
//            'App\Listeners\Notifications\NotifyOrderCreatedToAdmins',
//            'App\Listeners\Notifications\NotifyOrderCreatedToMerchants',
//            'App\Listeners\Notifications\NotifyOrderCreatedToCustomers',
//        ],
//        'App\Events\OrderCancelled' => [
//            'App\Listeners\Notifications\NotifyOrderCancelledToAdmins',
//            'App\Listeners\Notifications\NotifyOrderCancelledToMerchants',
//            'App\Listeners\Notifications\NotifyOrderCancelledToCustomers',
//        ],
//        'App\Events\OrderRefunded' => [
//            'App\Listeners\Notifications\NotifyOrderRefundedToAdmins',
//            'App\Listeners\Notifications\NotifyOrderRefundedToMerchants',
//            'App\Listeners\Notifications\NotifyOrderRefundedToCustomers',
//        ],
//        'App\Events\OrderPaid' => [
//            'App\Listeners\Notifications\NotifyOrderPaidToAdmins',
//            'App\Listeners\Notifications\NotifyOrderPaidToMerchants',
//        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
