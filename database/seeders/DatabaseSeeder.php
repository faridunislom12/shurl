<?php

namespace Database\Seeders;

use App\Http\Requests\UrlRequest;
use App\Http\Requests\UserRequest;
use App\Models\Url;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(LaratrustSeeder::class);

        $users_data = [
            ['name' => 'Администратор', 'last_name' => 'Администраторов', 'email' => 'administrator@example.com'],
            ['name' => 'Александр','last_name' => 'Александров','email' => 'user@example.com']
        ];

        foreach ($users_data as $user_data) {
            $user_data['email_verified_at'] = now();
            $user_data['password'] = 'password'; // password
            $user_data['is_active'] = true;

            $user = User::create($user_data);
            $user->roles()->attach(Role::where('name', explode('@', $user_data['email'])[0])->first()->id);
        }

        Url::factory(200)->create();

    }
}
