<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->create([
            'name'=>'Admin',
            'guard_name'=>'web'
        ]);
        Role::query()->create([
            'name'=>'Customer',
            'guard_name'=>'web'
        ]);
    }
}
