<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Preference;
use Illuminate\Support\Facades\Hash;

class MatchedUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create User 1 - John (Male)
        $john = User::create([
            'name' => 'John Doe',
            'email' => 'john@test.com',
            'password' => Hash::make('password123'),
            'age' => 28,
            'gender' => 'male',
            'religion' => 'Hindu',
            'caste' => 'Brahmin',
            'income' => 800000,
            'education' => 'Masters',
            'location' => 'Mumbai',
            'occupation' => 'Software Engineer',
            'bio' => 'Looking for a caring and understanding life partner. I love reading books and traveling.',
        ]);

        // Create User 2 - Priya (Female)
        $priya = User::create([
            'name' => 'Priya Sharma',
            'email' => 'priya@test.com',
            'password' => Hash::make('password123'),
            'age' => 26,
            'gender' => 'female',
            'religion' => 'Hindu',
            'caste' => 'Brahmin',
            'income' => 600000,
            'education' => 'Masters',
            'location' => 'Mumbai',
            'occupation' => 'Data Scientist',
            'bio' => 'Seeking a loving and supportive partner. I enjoy cooking and yoga.',
        ]);

        // Create preferences for John
        Preference::create([
            'user_id' => $john->id,
            'preferred_age_min' => 24,
            'preferred_age_max' => 30,
            'preferred_gender' => 'female',
            'preferred_religion' => 'Hindu',
            'preferred_caste' => 'Brahmin',
            'preferred_income_min' => 500000,
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
            'occupation_weight' => 1.0,
        ]);

        // Create preferences for Priya
        Preference::create([
            'user_id' => $priya->id,
            'preferred_age_min' => 26,
            'preferred_age_max' => 32,
            'preferred_gender' => 'male',
            'preferred_religion' => 'Hindu',
            'preferred_caste' => 'Brahmin',
            'preferred_income_min' => 600000,
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

        // Create mutual likes (which creates a match)
        $john->likes()->attach($priya->id);
        $priya->likes()->attach($john->id);

        // Create the match
        $john->matches()->attach($priya->id);

        $this->command->info('✅ Created 2 matched users:');
        $this->command->info('');
        $this->command->info('👨 John Doe (Male)');
        $this->command->info('   Email: john@test.com');
        $this->command->info('   Password: password123');
        $this->command->info('');
        $this->command->info('👩 Priya Sharma (Female)');
        $this->command->info('   Email: priya@test.com');
        $this->command->info('   Password: password123');
        $this->command->info('');
        $this->command->info('🎉 These users are already matched!');
        $this->command->info('');
        $this->command->info('Test Commands:');
        $this->command->info('1. Login as John: POST /api/login with john@test.com');
        $this->command->info('2. Login as Priya: POST /api/login with priya@test.com');
        $this->command->info('3. Check matches: GET /api/matches');
        $this->command->info('4. Check discover: GET /api/discover');
    }
}
