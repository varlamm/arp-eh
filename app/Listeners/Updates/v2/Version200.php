<?php

namespace Xcelerate\Listeners\Updates\v2;

use Xcelerate\Events\UpdateFinished;
use Xcelerate\Listeners\Updates\Listener;
use Xcelerate\Models\Setting;
use Illuminate\Database\Schema\Blueprint;

class Version200 extends Listener
{
    public const VERSION = '2.0.0';

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

        // Replace state and city id to name
        $this->replaceStateAndCityName();

        // Drop states and cities foreign key
        $this->dropForeignKey();

        // Remove states and cities tables
        $this->dropSchemas();

        // Delete state & city models, migrations & seeders
        $this->deleteFiles();

        // Update Xcelerate app version
        $this->updateVersion();
    }

    private function replaceStateAndCityName()
    {
        \Schema::table('addresses', function (Blueprint $table) {
            $table->string('state')->nullable();
            $table->string('city')->nullable();
        });

        $addresses = \Xcelerate\Models\Address::all();
        foreach ($addresses as $add) {
            $city = \Xcelerate\City::find($add->city_id);
            if ($city) {
                $add->city = $city->name;
            }

            $state = \Xcelerate\State::find($add->state_id);
            if ($state) {
                $add->state = $state->name;
            }

            $add->save();
        }
    }

    private function dropForeignKey()
    {
        \Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign('addresses_state_id_foreign');
            $table->dropForeign('addresses_city_id_foreign');
            $table->dropColumn('state_id');
            $table->dropColumn('city_id');
        });
    }

    private function dropSchemas()
    {
        \Schema::disableForeignKeyConstraints();

        \Schema::dropIfExists('states');
        \Schema::dropIfExists('cities');

        \Schema::enableForeignKeyConstraints();
    }

    private function deleteFiles()
    {
        \File::delete(
            database_path('migrations/2017_05_06_172817_create_cities_table.php'),
            database_path('migrations/2017_05_06_173711_create_states_table.php'),
            database_path('seeds/StatesTableSeeder.php'),
            database_path('seeds/CitiesTableSeeder.php'),
            app_path('City.php'),
            app_path('State.php')
        );
    }

    private function updateVersion()
    {
        Setting::setSetting('version', static::VERSION);
    }
}
