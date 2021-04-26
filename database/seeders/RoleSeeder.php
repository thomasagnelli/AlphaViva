<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Role::DEFAULT_ROLES as $id => $name) {
            $role = Role::create([
                'id' => $id,
                'name' => $name
            ]);
            $role->save();
        }
    }
}
