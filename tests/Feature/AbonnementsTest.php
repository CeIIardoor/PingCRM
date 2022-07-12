<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
use Tests\TestCase;

class AbonnementsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'account_id' => Account::create(['name' => 'Acme Corporation'])->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'johndoe@example.com',
            'owner' => true,
        ]);

        $this->user->account->abonnements()->createMany([
            [
                'name' => 'IAM',
                'email' => 'info@iAM.com',
            ], [
                'name' => 'INWI',
                'email' => 'info@iNWI.com',
            ],
        ]);
    }

    public function test_can_view_abonnements()
    {
        $this->actingAs($this->user)
            ->get('/abonnements')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Abonnements/Index')
                ->has('abonnements.data', 2)
                ->has('abonnements.data.0', fn (Assert $assert) => $assert
                    ->where('id', 1)
                    ->where('name', 'IAM')
                    ->where('deleted_at', null)
                )
                ->has('abonnements.data.1', fn (Assert $assert) => $assert
                    ->where('id', 2)
                    ->where('name', 'INWI')
                    ->where('deleted_at', null)
                )
            );
    }

    public function test_can_search_for_abonnements()
    {
        $this->actingAs($this->user)
            ->get('/abonnements?search=IAM')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Abonnements/Index')
                ->where('filters.search', 'IAM')
                ->has('abonnements.data', 1)
                ->has('abonnements.data.0', fn (Assert $assert) => $assert
                    ->where('id', 1)
                    ->where('name', 'IAM')
                    ->where('deleted_at', null)
                )
            );
    }

    public function test_cannot_view_deleted_abonnements()
    {
        $this->user->account->abonnements()->firstWhere('name', 'INWI')->delete();

        $this->actingAs($this->user)
            ->get('/abonnements')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Abonnements/Index')
                ->has('abonnements.data', 1)
                ->where('abonnements.data.0.name', 'IAM')
            );
    }

    public function test_can_filter_to_view_deleted_abonnements()
    {
        $this->user->account->abonnements()->firstWhere('name', 'INWI')->delete();

        $this->actingAs($this->user)
            ->get('/abonnements?trashed=with')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Abonnements/Index')
                ->has('abonnements.data', 2)
                ->where('abonnements.data.0.name', 'IAM')
                ->where('abonnements.data.1.name', 'INWI')
            );
    }
}
