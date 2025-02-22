<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\User;

class TenantTest extends TestCase
{
    public function test_user_can_only_access_their_tenant_data()
    {
        $tenant1 = Tenant::factory()->create();
        $tenant2 = Tenant::factory()->create();

        $user = User::factory()->create([
            'tenant_id' => $tenant1->id
        ]);

        $response = $this->actingAs($user)
            ->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonMissing(['tenant_id' => $tenant2->id]);
    }
} 