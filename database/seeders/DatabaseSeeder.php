<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Unidade;
use App\Models\Quadra;
use App\Models\Lote;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Seeding default user roles
        $this->call(RoleSeeder::class);
        
        //Seeding default admin
        $this->call(UserAdminSeeder::class);

        //Seeding example Users and Clientes
        User::factory(25)->create();
        Cliente::factory(100)->create();

        //Seeding Example Unidades, Quadras and Lotes
        Unidade::factory(100)->create();
        Quadra::factory(Unidade::count() * 4)->create();
        Lote::factory(Quadra::count() * 30)->create();
    }
}
