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
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'age' => fake()->numberBetween(18, 60),
            'gender' => fake()->randomElement(['male', 'female']),
            'religion' => fake()->randomElement(['Hindu', 'Muslim', 'Sikh', 'Christian']),
            'caste' => fake()->randomElement(['Brahmin', 'Patel', 'Agarwal', 'Khatri', 'Yadav', 'Reddy']),
            'income' => fake()->numberBetween(300000, 2000000),
            'education' => fake()->randomElement(['Bachelors', 'Masters', 'PhD']),
            'location' => fake()->randomElement(['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Hyderabad', 'Pune']),
            'occupation' => fake()->randomElement(['Software Engineer', 'Doctor', 'Teacher', 'Business Analyst', 'Data Scientist', 'Architect']),
            'bio' => fake()->paragraph(),
            'profile_picture' => null,
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

    /**
     * Create a male user.
     */
    public function male(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender' => 'male',
        ]);
    }

    /**
     * Create a female user.
     */
    public function female(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender' => 'female',
        ]);
    }

    /**
     * Create a Hindu user.
     */
    public function hindu(): static
    {
        return $this->state(fn (array $attributes) => [
            'religion' => 'Hindu',
        ]);
    }

    /**
     * Create a Muslim user.
     */
    public function muslim(): static
    {
        return $this->state(fn (array $attributes) => [
            'religion' => 'Muslim',
        ]);
    }

    /**
     * Create a Sikh user.
     */
    public function sikh(): static
    {
        return $this->state(fn (array $attributes) => [
            'religion' => 'Sikh',
        ]);
    }

    /**
     * Create a user with specific age range.
     */
    public function ageRange(int $min, int $max): static
    {
        return $this->state(fn (array $attributes) => [
            'age' => fake()->numberBetween($min, $max),
        ]);
    }

    /**
     * Create a user with high income.
     */
    public function highIncome(): static
    {
        return $this->state(fn (array $attributes) => [
            'income' => fake()->numberBetween(1000000, 3000000),
        ]);
    }

    /**
     * Create a user with specific location.
     */
    public function location(string $location): static
    {
        return $this->state(fn (array $attributes) => [
            'location' => $location,
        ]);
    }
}
