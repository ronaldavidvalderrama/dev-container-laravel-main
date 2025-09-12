<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }


    public function withRole(string $roleName = 'viewer') 
    {
        return $this->afterCreating(function (User $user) use ($roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $user->roles()->attach($role->id);
        });
    }

    public function withRoles(array $roleNames = ['viewer']) 
    {
        return $this->afterCreating(function (User $user) use ($roleNames) {
            $role = Role::firstOrCreate(['name' => $roleNames]);
            $user->roles()->attach($role->id);
        });
    }

    public function withoutRoles(int $count = 3) 
    {
        return $this->has(Post::factory()->count($count), 'posts');
    }
}
