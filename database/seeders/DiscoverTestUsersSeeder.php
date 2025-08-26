<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Preference;
use Illuminate\Support\Facades\Hash;

class DiscoverTestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create additional users for discover testing
        
        // User 3 - Alice (Female) - For John to discover
        $alice = User::create([
            'name' => 'Alice Johnson',
            'email' => 'alice@test.com',
            'password' => Hash::make('password123'),
            'age' => 25,
            'gender' => 'female',
            'religion' => 'Christian',
            'caste' => null,
            'income' => 500000,
            'education' => 'Bachelors',
            'location' => 'Delhi',
            'occupation' => 'Marketing Manager',
            'bio' => 'Looking for someone special who shares my values.',
        ]);

        // User 4 - Rahul (Male) - For Priya to discover
        $rahul = User::create([
            'name' => 'Rahul Patel',
            'email' => 'rahul@test.com',
            'password' => Hash::make('password123'),
            'age' => 29,
            'gender' => 'male',
            'religion' => 'Hindu',
            'caste' => 'Patel',
            'income' => 900000,
            'education' => 'Masters',
            'location' => 'Bangalore',
            'occupation' => 'Product Manager',
            'bio' => 'Passionate about technology and innovation.',
        ]);

        // User 5 - Neha (Female) - For John to discover
        $neha = User::create([
            'name' => 'Neha Gupta',
            'email' => 'neha@test.com',
            'password' => Hash::make('password123'),
            'age' => 27,
            'gender' => 'female',
            'religion' => 'Hindu',
            'caste' => 'Agarwal',
            'income' => 700000,
            'education' => 'Masters',
            'location' => 'Mumbai',
            'occupation' => 'Doctor',
            'bio' => 'Dedicated healthcare professional seeking a caring partner.',
        ]);

        // User 6 - Amit (Male) - For Priya to discover
        $amit = User::create([
            'name' => 'Amit Singh',
            'email' => 'amit@test.com',
            'password' => Hash::make('password123'),
            'age' => 30,
            'gender' => 'male',
            'religion' => 'Sikh',
            'caste' => 'Jat',
            'income' => 1100000,
            'education' => 'PhD',
            'location' => 'Pune',
            'occupation' => 'Research Scientist',
            'bio' => 'Love for science and nature. Looking for an intellectual partner.',
        ]);

        // Create preferences for Alice
        Preference::create([
            'user_id' => $alice->id,
            'preferred_age_min' => 25,
            'preferred_age_max' => 35,
            'preferred_gender' => 'male',
            'preferred_religion' => 'Christian',
            'preferred_caste' => null,
            'preferred_income_min' => 400000,
            'preferred_income_max' => 1000000,
            'preferred_education' => 'Bachelors',
            'preferred_location' => 'Delhi',
            'preferred_occupation' => 'Software Engineer',
            'age_weight' => 1.5,
            'gender_weight' => 1.5,
            'religion_weight' => 2.0,
            'caste_weight' => 0.5,
            'income_weight' => 1.0,
            'education_weight' => 1.0,
            'location_weight' => 1.5,
            'occupation_weight' => 1.0,
        ]);

        // Create preferences for Rahul
        Preference::create([
            'user_id' => $rahul->id,
            'preferred_age_min' => 24,
            'preferred_age_max' => 30,
            'preferred_gender' => 'female',
            'preferred_religion' => 'Hindu',
            'preferred_caste' => 'Brahmin',
            'preferred_income_min' => 500000,
            'preferred_income_max' => 800000,
            'preferred_education' => 'Masters',
            'preferred_location' => 'Mumbai',
            'preferred_occupation' => 'Data Scientist',
            'age_weight' => 2.0,
            'gender_weight' => 1.5,
            'religion_weight' => 2.0,
            'caste_weight' => 1.5,
            'income_weight' => 1.0,
            'education_weight' => 1.5,
            'location_weight' => 1.0,
            'occupation_weight' => 1.5,
        ]);

        // Create preferences for Neha
        Preference::create([
            'user_id' => $neha->id,
            'preferred_age_min' => 26,
            'preferred_age_max' => 32,
            'preferred_gender' => 'male',
            'preferred_religion' => 'Hindu',
            'preferred_caste' => 'Brahmin',
            'preferred_income_min' => 700000,
            'preferred_income_max' => 1200000,
            'preferred_education' => 'Masters',
            'preferred_location' => 'Mumbai',
            'preferred_occupation' => 'Software Engineer',
            'age_weight' => 2.0,
            'gender_weight' => 1.5,
            'religion_weight' => 2.0,
            'caste_weight' => 1.5,
            'income_weight' => 1.0,
            'education_weight' => 1.5,
            'location_weight' => 1.0,
            'occupation_weight' => 1.0,
        ]);

        // Create preferences for Amit
        Preference::create([
            'user_id' => $amit->id,
            'preferred_age_min' => 25,
            'preferred_age_max' => 30,
            'preferred_gender' => 'female',
            'preferred_religion' => 'Hindu',
            'preferred_caste' => 'Brahmin',
            'preferred_income_min' => 600000,
            'preferred_income_max' => 1000000,
            'preferred_education' => 'Masters',
            'preferred_location' => 'Mumbai',
            'preferred_occupation' => 'Data Scientist',
            'age_weight' => 2.0,
            'gender_weight' => 1.5,
            'religion_weight' => 2.0,
            'caste_weight' => 1.5,
            'income_weight' => 1.0,
            'education_weight' => 1.5,
            'location_weight' => 1.0,
            'occupation_weight' => 1.5,
        ]);

        $this->command->info('✅ Created 4 additional users for discover testing:');
        $this->command->info('');
        $this->command->info('👩 Alice Johnson (Female) - Email: alice@test.com');
        $this->command->info('👨 Rahul Patel (Male) - Email: rahul@test.com');
        $this->command->info('👩 Neha Gupta (Female) - Email: neha@test.com');
        $this->command->info('👨 Amit Singh (Male) - Email: amit@test.com');
        $this->command->info('');
        $this->command->info('All users have password: password123');
        $this->command->info('');
        $this->command->info('Testing Scenarios:');
        $this->command->info('1. Login as John and check discover - should see Alice and Neha');
        $this->command->info('2. Login as Priya and check discover - should see Rahul and Amit');
        $this->command->info('3. Like profiles and test mutual matching');
    }
}
