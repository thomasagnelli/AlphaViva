<?php
namespace Database\Factories;

use App\Models\User;
use App\Models\Cliente;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use \Faker\Provider\pt_BR\Person;
use \Faker\Provider\pt_BR\PhoneNumber;
use Faker\Provider\pt_BR\Company;


class ClienteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cliente::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $this->faker->addProvider(new PhoneNumber($this->faker));
        $this->faker->addProvider(new Person($this->faker));
        $this->faker->addProvider(new Company($this->faker));

        $user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$5ZXQRq7VXYGcWbKu/we3yeI8Gg/5Sgf/WOxAgG605Y7MrsgXBGPZa', //P@55w0rd123
            'remember_token' => Str::random(10),
            'roleId' => Role::getByName('client'),
        ]);

        $data = [
            //'cpf' => $this->faker->cpf,
            //'cnpj' => $this->faker->cnpj,
            'phone' => $this->faker->areaCode ." - ".$this->faker->phone,
            'userId' => $user->id
        ];

        $docuemnt = [
            ['cpf' =>  $this->faker->cpf()],
            ['cnpj' =>  $this->faker->cnpj()]
        ];
        return array_merge($data, $docuemnt[rand(0, 1)]);
    }
}
