<?php

namespace Xcelerate\Listeners\Updates\v3;

use Artisan;
use Xcelerate\Events\UpdateFinished;
use Xcelerate\Listeners\Updates\Listener;
use Xcelerate\Models\Currency;
use Xcelerate\Models\Setting;

class Version310 extends Listener
{
    public const VERSION = '3.1.0';

    /**
     * Handle the event.
     *
     * @param UpdateFinished $event
     * @return void
     */
    public function handle(UpdateFinished $event)
    {
        if ($this->isListenerFired($event)) {
            return;
        }

        Currency::firstOrCreate(
            [
                'name' => 'Kyrgyzstani som',
                'code' => 'KGS',
            ],
            [
                'name' => 'Kyrgyzstani som',
                'code' => 'KGS',
                'symbol' => 'С̲ ',
                'precision' => '2',
                'thousand_separator' => '.',
                'decimal_separator' => ',',
            ]
        );

        Artisan::call('migrate', ['--force' => true]);

        // Update Xcelerate app version
        Setting::setSetting('version', static::VERSION);
    }
}
