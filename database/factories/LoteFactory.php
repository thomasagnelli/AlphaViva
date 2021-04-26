<?php
namespace Database\Factories;

use \App\Models\Lote;
use \App\Models\Quadra;
use Illuminate\Database\Eloquent\Factories\Factory;

class LoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lote::class;

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
          'quadraId' => rand(1, Quadra::count())
      ];
    }
}
