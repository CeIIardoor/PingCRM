<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
use Tests\TestCase;

class OrganisationsTest extends TestCase
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

        $this->user->account->organisations()->createMany([
            [
                'name' => 'Apple',
                'email' => 'info@apple.com',
                'phone' => '647-943-4400',
                'address' => '1600-120 Bremner Blvd',
                'city' => 'Toronto',
                'region' => 'ON',
                'country' => 'CA',
                'postal_code' => 'M5J 0A8',
            ], [
                'name' => 'Microsoft',
                'email' => 'info@microsoft.com',
                'phone' => '877-568-2495',
                'address' => 'One Microsoft Way',
                'city' => 'Redmond',
                'region' => 'WA',
                'country' => 'US',
                'postal_code' => '98052',
            ],
        ]);
    }

    public function test_can_view_organisations()
    {
        $this->actingAs($this->user)
            ->get('/organisations')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Organisations/Index')
                ->has('organisations.data', 2)
                ->has('organisations.data.0', fn (Assert $assert) => $assert
                    ->where('id', 1)
                    ->where('name', 'Apple')
                    ->where('phone', '647-943-4400')
                    ->where('city', 'Toronto')
                    ->where('deleted_at', null)
                )
                ->has('organisations.data.1', fn (Assert $assert) => $assert
                    ->where('id', 2)
                    ->where('name', 'Microsoft')
                    ->where('phone', '877-568-2495')
                    ->where('city', 'Redmond')
                    ->where('deleted_at', null)
                )
            );
    }

    public function test_can_search_for_organisations()
    {
        $this->actingAs($this->user)
            ->get('/organisations?search=Apple')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Organisations/Index')
                ->where('filters.search', 'Apple')
                ->has('organisations.data', 1)
                ->has('organisations.data.0', fn (Assert $assert) => $assert
                    ->where('id', 1)
                    ->where('name', 'Apple')
                    ->where('phone', '647-943-4400')
                    ->where('city', 'Toronto')
                    ->where('deleted_at', null)
                )
            );
    }

    public function test_cannot_view_deleted_organisations()
    {
        $this->user->account->organisations()->firstWhere('name', 'Microsoft')->delete();

        $this->actingAs($this->user)
            ->get('/organisations')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Organisations/Index')
                ->has('organisations.data', 1)
                ->where('organisations.data.0.name', 'Apple')
            );
    }

    public function test_can_filter_to_view_deleted_organisations()
    {
        $this->user->account->organisations()->firstWhere('name', 'Microsoft')->delete();

        $this->actingAs($this->user)
            ->get('/organisations?trashed=with')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Organisations/Index')
                ->has('organisations.data', 2)
                ->where('organisations.data.0.name', 'Apple')
                ->where('organisations.data.1.name', 'Microsoft')
            );
    }
}
