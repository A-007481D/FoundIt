<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class ItemLifecycleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test lost item creation fails if required fields are missing.
     */
    public function test_lost_item_creation_requires_lost_date(): void
    {
        $user = User::factory()->create();
        $payload = [
            'type' => 'lost',
            'title' => 'Lost Wallet',
            // 'lost_date' => missing on purpose
            'category_id' => 1,
            'location' => 'Library',
        ];
        $response = $this->actingAs($user, 'api')->postJson('/api/items', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['lost_date']);
    }

    /**
     * Test found item creation fails if found_date is missing.
     */
    public function test_found_item_creation_requires_found_date(): void
    {
        $user = User::factory()->create();
        $payload = [
            'type' => 'found',
            'title' => 'Found Keys',
            // 'found_date' => missing on purpose
            'category_id' => 1,
            'location' => 'Cafeteria',
        ];
        $response = $this->actingAs($user, 'api')->postJson('/api/items', $payload);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['found_date']);
    }

    /**
     * Test a user cannot delete another user's item.
     */
    public function test_user_cannot_delete_others_item(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $item = Item::factory()->create(['user_id' => $owner->id]);

        $response = $this->actingAs($otherUser, 'api')->deleteJson("/api/items/{$item->id}");
        $response->assertStatus(403);
    }
}
