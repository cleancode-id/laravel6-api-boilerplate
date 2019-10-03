<?php

use App\Entities\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@app.id',
            'password' => bcrypt('12345678'),
        ]);
    }
}
