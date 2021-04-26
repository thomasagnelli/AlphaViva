<?php
namespace Database\Factories;

use \App\Models\Unidade;
use \App\Models\Quadra;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuadraFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Quadra::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => '...',
            'unidadeId' => rand(1, Unidade::count())
        ];
    }
}
