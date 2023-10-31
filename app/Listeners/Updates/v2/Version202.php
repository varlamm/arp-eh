<?php

namespace Xcelerate\Listeners\Updates\v2;

use Xcelerate\Events\UpdateFinished;
use Xcelerate\Listeners\Updates\Listener;
use Xcelerate\Models\Setting;

class Version202 extends Listener
{
    public const VERSION = '2.0.2';

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UpdateFinished $event)
    {
        if ($this->isListenerFired($event)) {
            return;
        }

        // Update Xcelerate app version
        Setting::setSetting('version', static::VERSION);
    }
}
