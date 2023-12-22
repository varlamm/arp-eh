<?php

namespace Database\Factories;

use Xcelerate\Models\Currency;
use Xcelerate\Models\Item;
use Xcelerate\Models\Unit;
use Xcelerate\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'company_id' => User::find(1)->companies()->first()->id,
            'price' => $this->faker->randomDigitNotNull,
            'unit_id' => Unit::factory(),
            'creator_id' => User::where('role', 'super admin')->first()->company_id,
            'currency_id' => Currency::find(1)->id,
            'tax_per_item' => $this->faker->randomElement([true, false])
        ];
    }
}
