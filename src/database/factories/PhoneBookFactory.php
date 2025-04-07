<?php

namespace Database\Factories;

use App\Models\PhoneBook;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhoneBookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PhoneBook::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'phone_number' => $this->faker->e164PhoneNumber(),
            'user_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the phone book belongs to a specific user.
     *
     * @param User|int $user
     * @return Factory
     */
    public function forUser(User|int $user): Factory
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => is_int($user) ? $user : $user->id,
        ]);
    }
}
