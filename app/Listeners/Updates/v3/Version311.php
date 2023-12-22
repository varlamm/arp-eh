<?php

namespace Xcelerate\Listeners\Updates\v3;

use Artisan;
use Xcelerate\Events\UpdateFinished;
use Xcelerate\Listeners\Updates\Listener;
use Xcelerate\Models\Setting;

class Version311 extends Listener
{
    public const VERSION = '3.1.1';

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

        Artisan::call('migrate', ['--force' => true]);

        // Update Xcelerate app version
        Setting::setSetting('version', static::VERSION);
    }
}
