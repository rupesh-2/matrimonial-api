<?php

namespace Database\Factories;

use App\Models\Preference;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Preference>
 */
class PreferenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Preference::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'preferred_age_min' => $this->faker->numberBetween(20, 25),
            'preferred_age_max' => $this->faker->numberBetween(30, 35),
            'preferred_gender' => $this->faker->randomElement(['male', 'female']),
            'preferred_religion' => $this->faker->randomElement(['Hindu', 'Muslim', 'Christian', 'Sikh', 'Buddhist']),
            'preferred_caste' => $this->faker->optional()->randomElement(['Brahmin', 'Kshatriya', 'Vaishya', 'Shudra', 'Patel', 'Agarwal']),
            'preferred_income_min' => $this->faker->numberBetween(300000, 500000),
            'preferred_income_max' => $this->faker->numberBetween(800000, 1200000),
            'preferred_education' => $this->faker->randomElement(['Bachelors', 'Masters', 'PhD', 'Diploma']),
            'preferred_location' => $this->faker->randomElement(['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Kolkata']),
            'preferred_occupation' => $this->faker->randomElement(['Software Engineer', 'Doctor', 'Teacher', 'Business Owner', 'Manager']),
            'age_weight' => $this->faker->randomFloat(1, 0.5, 3.0),
            'gender_weight' => $this->faker->randomFloat(1, 0.5, 3.0),
            'religion_weight' => $this->faker->randomFloat(1, 0.5, 3.0),
            'caste_weight' => $this->faker->randomFloat(1, 0.5, 3.0),
            'income_weight' => $this->faker->randomFloat(1, 0.5, 3.0),
            'education_weight' => $this->faker->randomFloat(1, 0.5, 3.0),
            'location_weight' => $this->faker->randomFloat(1, 0.5, 3.0),
            'occupation_weight' => $this->faker->randomFloat(1, 0.5, 3.0),
        ];
    }

    /**
     * Create preferences for a specific user
     */
    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Create preferences with specific gender preference
     */
    public function withGenderPreference(string $gender): static
    {
        return $this->state(fn (array $attributes) => [
            'preferred_gender' => $gender,
        ]);
    }

    /**
     * Create preferences with specific age range
     */
    public function withAgeRange(int $min, int $max): static
    {
        return $this->state(fn (array $attributes) => [
            'preferred_age_min' => $min,
            'preferred_age_max' => $max,
        ]);
    }

    /**
     * Create preferences with specific religion preference
     */
    public function withReligionPreference(string $religion): static
    {
        return $this->state(fn (array $attributes) => [
            'preferred_religion' => $religion,
        ]);
    }
}
