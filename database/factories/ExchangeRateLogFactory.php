<?php

namespace Database\Factories;

use Xcelerate\Models\Currency;
use Xcelerate\Models\ExchangeRateLog;
use Xcelerate\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExchangeRateLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ExchangeRateLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Currency::find(1)->id,
            'base_currency_id' => User::find(1)->companies()->first()->id,
            'currency_id' => Currency::find(4)->id,
            'exchange_rate' => $this->faker->randomDigitNotNull
        ];
    }
}
