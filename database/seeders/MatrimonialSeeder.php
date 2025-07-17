<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Preference;
use Illuminate\Support\Facades\Hash;

class MatrimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Priya Sharma',
                'email' => 'priya@example.com',
                'password' => Hash::make('password123'),
                'age' => 25,
                'gender' => 'female',
                'religion' => 'Hindu',
                'caste' => 'Brahmin',
                'income' => 500000,
                'education' => 'Masters',
                'location' => 'Mumbai',
                'occupation' => 'Software Engineer',
                'bio' => 'Looking for a caring and understanding partner.',
            ],
            [
                'name' => 'Rahul Patel',
                'email' => 'rahul@example.com',
                'password' => Hash::make('password123'),
                'age' => 28,
                'gender' => 'male',
                'religion' => 'Hindu',
                'caste' => 'Patel',
                'income' => 800000,
                'education' => 'MBA',
                'location' => 'Delhi',
                'occupation' => 'Business Analyst',
                'bio' => 'Passionate about life and looking for someone special.',
            ],
            [
                'name' => 'Aisha Khan',
                'email' => 'aisha@example.com',
                'password' => Hash::make('password123'),
                'age' => 24,
                'gender' => 'female',
                'religion' => 'Muslim',
                'caste' => 'Sunni',
                'income' => 400000,
                'education' => 'Bachelors',
                'location' => 'Hyderabad',
                'occupation' => 'Teacher',
                'bio' => 'Educated and family-oriented person.',
            ],
            [
                'name' => 'Amit Singh',
                'email' => 'amit@example.com',
                'password' => Hash::make('password123'),
                'age' => 30,
                'gender' => 'male',
                'religion' => 'Sikh',
                'caste' => 'Jat',
                'income' => 1200000,
                'education' => 'PhD',
                'location' => 'Punjab',
                'occupation' => 'Doctor',
                'bio' => 'Dedicated professional seeking a life partner.',
            ],
            [
                'name' => 'Neha Gupta',
                'email' => 'neha@example.com',
                'password' => Hash::make('password123'),
                'age' => 26,
                'gender' => 'female',
                'religion' => 'Hindu',
                'caste' => 'Agarwal',
                'income' => 600000,
                'education' => 'Masters',
                'location' => 'Bangalore',
                'occupation' => 'Data Scientist',
                'bio' => 'Tech-savvy and ambitious individual.',
            ],
            [
                'name' => 'Vikram Malhotra',
                'email' => 'vikram@example.com',
                'password' => Hash::make('password123'),
                'age' => 29,
                'gender' => 'male',
                'religion' => 'Hindu',
                'caste' => 'Khatri',
                'income' => 900000,
                'education' => 'Masters',
                'location' => 'Chennai',
                'occupation' => 'Architect',
                'bio' => 'Creative and artistic person looking for love.',
            ],
            [
                'name' => 'Fatima Ali',
                'email' => 'fatima@example.com',
                'password' => Hash::make('password123'),
                'age' => 23,
                'gender' => 'female',
                'religion' => 'Muslim',
                'caste' => 'Shia',
                'income' => 350000,
                'education' => 'Bachelors',
                'location' => 'Lucknow',
                'occupation' => 'Designer',
                'bio' => 'Creative and artistic soul seeking companionship.',
            ],
            [
                'name' => 'Rajesh Kumar',
                'email' => 'rajesh@example.com',
                'password' => Hash::make('password123'),
                'age' => 27,
                'gender' => 'male',
                'religion' => 'Hindu',
                'caste' => 'Yadav',
                'income' => 700000,
                'education' => 'Bachelors',
                'location' => 'Pune',
                'occupation' => 'Marketing Manager',
                'bio' => 'Outgoing and friendly person.',
            ],
            [
                'name' => 'Sneha Reddy',
                'email' => 'sneha@example.com',
                'password' => Hash::make('password123'),
                'age' => 25,
                'gender' => 'female',
                'religion' => 'Hindu',
                'caste' => 'Reddy',
                'income' => 550000,
                'education' => 'Masters',
                'location' => 'Hyderabad',
                'occupation' => 'HR Manager',
                'bio' => 'People person with a big heart.',
            ],
            [
                'name' => 'Arjun Mehta',
                'email' => 'arjun@example.com',
                'password' => Hash::make('password123'),
                'age' => 31,
                'gender' => 'male',
                'religion' => 'Hindu',
                'caste' => 'Brahmin',
                'income' => 1100000,
                'education' => 'MBA',
                'location' => 'Mumbai',
                'occupation' => 'Investment Banker',
                'bio' => 'Ambitious and driven professional.',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            
            // Create preferences for each user
            $this->createPreferences($user);
        }

        // Create some sample likes and matches
        $this->createSampleLikesAndMatches();
    }

    private function createPreferences(User $user)
    {
        $preferences = [
            'user_id' => $user->id,
            'preferred_age_min' => $user->age - 3,
            'preferred_age_max' => $user->age + 5,
            'preferred_gender' => $user->gender === 'male' ? 'female' : 'male',
            'preferred_religion' => $user->religion,
            'preferred_caste' => $user->caste,
            'preferred_income_min' => $user->income * 0.7,
            'preferred_income_max' => $user->income * 1.5,
            'preferred_education' => $user->education,
            'preferred_location' => $user->location,
            'preferred_occupation' => $user->occupation,
            'age_weight' => 1.0,
            'gender_weight' => 1.0,
            'religion_weight' => 1.0,
            'caste_weight' => 1.0,
            'income_weight' => 1.0,
            'education_weight' => 1.0,
            'location_weight' => 1.0,
            'occupation_weight' => 1.0,
        ];

        Preference::create($preferences);
    }

    private function createSampleLikesAndMatches()
    {
        $users = User::all();

        // Create some sample likes
        $users[0]->likes()->attach($users[1]->id); // Priya likes Rahul
        $users[1]->likes()->attach($users[0]->id); // Rahul likes Priya (mutual match)
        
        $users[2]->likes()->attach($users[3]->id); // Aisha likes Amit
        $users[4]->likes()->attach($users[5]->id); // Neha likes Vikram
        $users[5]->likes()->attach($users[4]->id); // Vikram likes Neha (mutual match)
        
        $users[6]->likes()->attach($users[7]->id); // Fatima likes Rajesh
        $users[8]->likes()->attach($users[9]->id); // Sneha likes Arjun

        // Create some mutual matches
        $users[0]->matches()->attach($users[1]->id); // Priya and Rahul are matched
        $users[4]->matches()->attach($users[5]->id); // Neha and Vikram are matched
    }
} 