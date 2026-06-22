<?php

namespace Database\Factories;

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
            'name'            => fake()->name(),
            'email'           => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'        => static::$password ??= Hash::make('password'),
            'remember_token'  => Str::random(10),
            'role'            => 'employee',
            'employment_type' => 'professor',
            'hourly_rate'     => 0,
            'monthly_salary'  => 0,
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

    /** Super-admin state. */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /** Moderator state — operational access, no system settings. */
    public function moderator(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'moderator',
        ]);
    }

    /** Regular employee state (self-service only). */
    public function employee(): static
    {
        return $this->state(fn (array $attributes) => [
            'role'            => 'employee',
            'employment_type' => 'professor',
            'hourly_rate'     => 120.00,
        ]);
    }

    /** Professor state (hourly payroll). */
    public function professor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role'            => 'professor',
            'employment_type' => 'professor',
            'hourly_rate'     => 150.00,
        ]);
    }

    /** Staff state (fixed monthly salary). */
    public function staff(): static
    {
        return $this->state(fn (array $attributes) => [
            'role'            => 'employee',
            'employment_type' => 'staff',
            'hourly_rate'     => 0,
            'monthly_salary'  => 25000.00,
        ]);
    }
}
