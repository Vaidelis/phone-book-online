<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\PhoneBook;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PhoneBookControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    #[Test]
    public function store_phone_book_successfully(): void
    {
        $phoneBookData = [
            'name' => 'John Doe',
            'phone_number' => '+1234567890',
            'user_id' => $this->user->id,
        ];

        $response = $this->actingAs($this->user)
            ->postJson('/api/phone-books/store', $phoneBookData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'phone_number',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Contact created successfully',
                'data' => [
                    'name' => 'John Doe',
                    'phone_number' => '+1234567890',
                ],
            ]);

        $this->assertDatabaseHas('phone_book', [
            'name' => 'John Doe',
            'phone_number' => '+1234567890',
            'user_id' => $this->user->id,
        ]);
    }

    #[Test]
    public function update_phone_book_successfully(): void
    {
        $phoneBook = PhoneBook::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Original Name',
            'phone_number' => '+1111111111',
        ]);

        $updateData = [
            'name' => 'Updated Name',
            'phone_number' => '+2222222222',
            'user_id' => $this->user->id,
        ];

        $response = $this->actingAs($this->user)
            ->putJson("/api/phone-books/update/{$phoneBook->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'phone_number',
                    'created_at',
                    'updated_at',
                ],
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Contact updated successfully',
                'data' => [
                    'name' => 'Updated Name',
                    'phone_number' => '+2222222222',
                ],
            ]);

        $this->assertDatabaseHas('phone_book', [
            'id' => $phoneBook->id,
            'name' => 'Updated Name',
            'phone_number' => '+2222222222',
        ]);
    }

    #[Test]
    public function delete_phone_book_successfully(): void
    {
        $phoneBook = PhoneBook::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/phone-books/delete/{$phoneBook->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
            ])
            ->assertJson([
                'success' => true,
                'message' => 'Operation completed successfully',
            ]);

        $this->assertDatabaseMissing('phone_book', [
            'id' => $phoneBook->id,
        ]);
    }

    #[Test]
    public function delete_non_existent_phone_book_returns_404(): void
    {
        $nonExistentId = 99999;

        $response = $this->actingAs($this->user)
            ->deleteJson("/api/phone-books/delete/{$nonExistentId}");

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ]);
    }
}
