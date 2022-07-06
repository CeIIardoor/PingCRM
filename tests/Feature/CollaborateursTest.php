<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\Assert;
use Tests\TestCase;

class CollaborateursTest extends TestCase
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

        $organisation = $this->user->account->organisations()->create(['name' => 'Example Organisation Inc.']);

        $this->user->account->collaborateurs()->createMany([
            [
                'organisation_id' => $organisation->id,
                'first_name' => 'Martin',
                'last_name' => 'Abbott',
                'email' => 'martin.abbott@example.com',
                'phone' => '555-111-2222',
                'address' => '330 Glenda Shore',
                'city' => 'Murphyland',
                'region' => 'Tennessee',
                'country' => 'US',
                'postal_code' => '57851',
            ], [
                'organisation_id' => $organisation->id,
                'first_name' => 'Lynn',
                'last_name' => 'Kub',
                'email' => 'lynn.kub@example.com',
                'phone' => '555-333-4444',
                'address' => '199 Connelly Turnpike',
                'city' => 'Woodstock',
                'region' => 'Colorado',
                'country' => 'US',
                'postal_code' => '11623',
            ],
        ]);
    }

    public function test_can_view_collaborateurs()
    {
        $this->actingAs($this->user)
            ->get('/collaborateurs')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Collaborateurs/Index')
                ->has('collaborateurs.data', 2)
                ->has('collaborateurs.data.0', fn (Assert $assert) => $assert
                    ->where('id', 1)
                    ->where('name', 'Martin Abbott')
                    ->where('phone', '555-111-2222')
                    ->where('city', 'Murphyland')
                    ->where('deleted_at', null)
                    ->has('organisation', fn (Assert $assert) => $assert
                        ->where('name', 'Example Organisation Inc.')
                    )
                )
                ->has('collaborateurs.data.1', fn (Assert $assert) => $assert
                    ->where('id', 2)
                    ->where('name', 'Lynn Kub')
                    ->where('phone', '555-333-4444')
                    ->where('city', 'Woodstock')
                    ->where('deleted_at', null)
                    ->has('organisation', fn (Assert $assert) => $assert
                        ->where('name', 'Example Organisation Inc.')
                    )
                )
            );
    }

    public function test_can_search_for_collaborateurs()
    {
        $this->actingAs($this->user)
            ->get('/collaborateurs?search=Martin')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Collaborateurs/Index')
                ->where('filters.search', 'Martin')
                ->has('collaborateurs.data', 1)
                ->has('collaborateurs.data.0', fn (Assert $assert) => $assert
                    ->where('id', 1)
                    ->where('name', 'Martin Abbott')
                    ->where('phone', '555-111-2222')
                    ->where('city', 'Murphyland')
                    ->where('deleted_at', null)
                    ->has('organisation', fn (Assert $assert) => $assert
                        ->where('name', 'Example Organisation Inc.')
                    )
                )
            );
    }

    public function test_cannot_view_deleted_collaborateurs()
    {
        $this->user->account->collaborateurs()->firstWhere('first_name', 'Martin')->delete();

        $this->actingAs($this->user)
            ->get('/collaborateurs')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Collaborateurs/Index')
                ->has('collaborateurs.data', 1)
                ->where('collaborateurs.data.0.name', 'Lynn Kub')
            );
    }

    public function test_can_filter_to_view_deleted_collaborateurs()
    {
        $this->user->account->collaborateurs()->firstWhere('first_name', 'Martin')->delete();

        $this->actingAs($this->user)
            ->get('/collaborateurs?trashed=with')
            ->assertInertia(fn (Assert $assert) => $assert
                ->component('Collaborateurs/Index')
                ->has('collaborateurs.data', 2)
                ->where('collaborateurs.data.0.name', 'Martin Abbott')
                ->where('collaborateurs.data.1.name', 'Lynn Kub')
            );
    }
}
