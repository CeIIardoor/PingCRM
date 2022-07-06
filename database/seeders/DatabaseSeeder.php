<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Collaborateur;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $account = Account::create(['name' => 'Acme Corporation']);

        User::factory()->create([
            'account_id' => $account->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'owner' => true,
        ]);

        User::factory(5)->create(['account_id' => $account->id]);

        $organisations = Organisation::factory(100)
            ->create(['account_id' => $account->id]);

        Collaborateur::factory(100)
            ->create(['account_id' => $account->id])
            ->each(function ($collaborateur) use ($organisations) {
                $collaborateur->update(['organisation_id' => $organisations->random()->id]);
            });
    }
}
