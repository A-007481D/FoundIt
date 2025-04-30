<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class SecurityEdgeCasesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user cannot update another user's item.
     */
    public function test_user_cannot_update_others_item(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($otherUser, 'api')->patchJson("/api/items/{$item->id}", [
            'title' => 'Hacked Title',
        ]);
        $response->assertStatus(403);
    }

    /**
     * Test that an unauthenticated user cannot create an item.
     */
    public function test_guest_cannot_create_item(): void
    {
        $payload = [
            'type' => 'lost',
            'title' => 'Lost Phone',
            'lost_date' => now()->toDateString(),
            'category_id' => 1,
            'location' => 'Park',
        ];
        $response = $this->postJson('/api/items', $payload);
        $response->assertStatus(401);
    }

    /**
     * Test that a user cannot escalate their privileges via API.
     */
    public function test_user_cannot_make_themselves_admin(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user, 'api')->patchJson("/api/users/{$user->id}", [
            'role' => 'admin',
        ]);
        $response->assertStatus(403);
    }
}
