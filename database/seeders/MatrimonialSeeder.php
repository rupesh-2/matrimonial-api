<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Preference;
use App\Models\Message;
use Illuminate\Support\Facades\Hash;

class MatrimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            // Female Users
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
                'bio' => 'Looking for a caring and understanding partner who values family and career balance.',
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
                'bio' => 'Educated and family-oriented person seeking a respectful and caring partner.',
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
                'bio' => 'Tech-savvy and ambitious individual looking for someone who shares similar goals.',
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
                'bio' => 'Creative and artistic soul seeking companionship with someone who appreciates art and culture.',
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
                'bio' => 'People person with a big heart, looking for someone who values relationships and communication.',
            ],
            [
                'name' => 'Kavya Iyer',
                'email' => 'kavya@example.com',
                'password' => Hash::make('password123'),
                'age' => 27,
                'gender' => 'female',
                'religion' => 'Hindu',
                'caste' => 'Iyer',
                'income' => 700000,
                'education' => 'PhD',
                'location' => 'Chennai',
                'occupation' => 'Research Scientist',
                'bio' => 'Passionate about research and innovation, seeking an intellectual partner.',
            ],
            [
                'name' => 'Zara Ahmed',
                'email' => 'zara@example.com',
                'password' => Hash::make('password123'),
                'age' => 24,
                'gender' => 'female',
                'religion' => 'Muslim',
                'caste' => 'Pathan',
                'income' => 450000,
                'education' => 'Masters',
                'location' => 'Delhi',
                'occupation' => 'Journalist',
                'bio' => 'Curious and adventurous spirit looking for someone who loves to explore and learn.',
            ],
            [
                'name' => 'Anjali Verma',
                'email' => 'anjali@example.com',
                'password' => Hash::make('password123'),
                'age' => 26,
                'gender' => 'female',
                'religion' => 'Hindu',
                'caste' => 'Verma',
                'income' => 650000,
                'education' => 'MBA',
                'location' => 'Pune',
                'occupation' => 'Marketing Manager',
                'bio' => 'Dynamic and energetic professional seeking a partner who shares my zest for life.',
            ],

            // Male Users
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
                'bio' => 'Passionate about life and looking for someone special to share my journey with.',
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
                'bio' => 'Dedicated professional seeking a life partner who values family and commitment.',
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
                'bio' => 'Creative and artistic person looking for love and someone to build beautiful memories with.',
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
                'bio' => 'Outgoing and friendly person who loves to travel and meet new people.',
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
                'bio' => 'Ambitious and driven professional seeking a partner who shares my vision for the future.',
            ],
            [
                'name' => 'Karan Sharma',
                'email' => 'karan@example.com',
                'password' => Hash::make('password123'),
                'age' => 26,
                'gender' => 'male',
                'religion' => 'Hindu',
                'caste' => 'Brahmin',
                'income' => 750000,
                'education' => 'Masters',
                'location' => 'Bangalore',
                'occupation' => 'Product Manager',
                'bio' => 'Tech enthusiast and fitness lover looking for someone who values health and growth.',
            ],
            [
                'name' => 'Aditya Verma',
                'email' => 'aditya@example.com',
                'password' => Hash::make('password123'),
                'age' => 28,
                'gender' => 'male',
                'religion' => 'Hindu',
                'caste' => 'Verma',
                'income' => 850000,
                'education' => 'MBA',
                'location' => 'Delhi',
                'occupation' => 'Consultant',
                'bio' => 'Analytical mind with a passion for problem-solving, seeking an intelligent and caring partner.',
            ],
            [
                'name' => 'Rohan Kapoor',
                'email' => 'rohan@example.com',
                'password' => Hash::make('password123'),
                'age' => 25,
                'gender' => 'male',
                'religion' => 'Hindu',
                'caste' => 'Khatri',
                'income' => 600000,
                'education' => 'Bachelors',
                'location' => 'Mumbai',
                'occupation' => 'Film Director',
                'bio' => 'Creative storyteller and filmmaker looking for someone who appreciates art and culture.',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            
            // Create preferences for each user
            $this->createPreferences($user);
        }

        // Create sample likes and matches
        $this->createSampleLikesAndMatches();
        
        // Create sample messages
        $this->createSampleMessages();
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
            'age_weight' => rand(8, 10) / 10, // Random weight between 0.8 and 1.0
            'gender_weight' => 1.0,
            'religion_weight' => rand(8, 10) / 10,
            'caste_weight' => rand(6, 9) / 10,
            'income_weight' => rand(7, 9) / 10,
            'education_weight' => rand(8, 10) / 10,
            'location_weight' => rand(6, 8) / 10,
            'occupation_weight' => rand(5, 8) / 10,
        ];

        Preference::create($preferences);
    }

    private function createSampleLikesAndMatches()
    {
        $users = User::all();

        // Create mutual likes that will become matches
        $users[0]->likes()->attach($users[8]->id); // Priya likes Rahul
        $users[8]->likes()->attach($users[0]->id); // Rahul likes Priya (mutual match)
        
        $users[2]->likes()->attach($users[9]->id); // Neha likes Amit
        $users[9]->likes()->attach($users[2]->id); // Amit likes Neha (mutual match)
        
        $users[4]->likes()->attach($users[10]->id); // Sneha likes Vikram
        $users[10]->likes()->attach($users[4]->id); // Vikram likes Sneha (mutual match)
        
        $users[5]->likes()->attach($users[12]->id); // Kavya likes Karan
        $users[12]->likes()->attach($users[5]->id); // Karan likes Kavya (mutual match)

        // Create some one-sided likes
        $users[1]->likes()->attach($users[11]->id); // Aisha likes Arjun
        $users[3]->likes()->attach($users[13]->id); // Fatima likes Aditya
        $users[6]->likes()->attach($users[14]->id); // Zara likes Rohan
        $users[7]->likes()->attach($users[8]->id); // Anjali likes Rahul

        // Create mutual matches (these are the users who liked each other)
        $users[0]->matches()->attach($users[8]->id); // Priya and Rahul are matched
        $users[2]->matches()->attach($users[9]->id); // Neha and Amit are matched
        $users[4]->matches()->attach($users[10]->id); // Sneha and Vikram are matched
        $users[5]->matches()->attach($users[12]->id); // Kavya and Karan are matched
    }

    private function createSampleMessages()
    {
        $users = User::all();
        
        // Sample conversations between matched users
        $conversations = [
            // Priya and Rahul
            [
                'from' => $users[0], // Priya
                'to' => $users[8],   // Rahul
                'messages' => [
                    'Hi Rahul! I really liked your profile. How are you doing?',
                    'Hello Priya! Thank you for the message. I\'m doing great, how about you?',
                    'I\'m good too! I noticed we have similar interests. Do you like traveling?',
                    'Yes, I love traveling! I recently visited Goa. Have you been there?',
                    'Not yet, but it\'s on my bucket list! Would love to hear about your experience.',
                ]
            ],
            // Neha and Amit
            [
                'from' => $users[2], // Neha
                'to' => $users[9],   // Amit
                'messages' => [
                    'Hi Amit! Your profile caught my attention. I\'m impressed by your achievements.',
                    'Thank you Neha! That\'s very kind of you. I found your background in data science fascinating.',
                    'Thanks! I love working with data and finding patterns. What\'s your favorite part about being a doctor?',
                    'Helping people and making a difference in their lives. It\'s very rewarding.',
                    'That\'s wonderful! I can see you\'re very passionate about your work.',
                ]
            ],
            // Sneha and Vikram
            [
                'from' => $users[4], // Sneha
                'to' => $users[10],  // Vikram
                'messages' => [
                    'Hello Vikram! I love your creative approach to architecture.',
                    'Hi Sneha! Thank you! I believe every building should tell a story.',
                    'That\'s such a beautiful perspective! Do you have any favorite projects?',
                    'Yes, I recently designed a sustainable home that blends modern and traditional elements.',
                    'That sounds amazing! I\'d love to see some of your work sometime.',
                ]
            ],
            // Kavya and Karan
            [
                'from' => $users[5], // Kavya
                'to' => $users[12],  // Karan
                'messages' => [
                    'Hi Karan! I noticed we both have a passion for technology and fitness.',
                    'Hello Kavya! Yes, that\'s true! I find that tech and fitness complement each other well.',
                    'Absolutely! What kind of research are you working on?',
                    'I\'m researching AI applications in healthcare. It\'s fascinating work.',
                    'That sounds incredibly interesting! I\'d love to learn more about it.',
                ]
            ],
        ];

        foreach ($conversations as $conversation) {
            $fromUser = $conversation['from'];
            $toUser = $conversation['to'];
            $messages = $conversation['messages'];
            
            foreach ($messages as $index => $messageText) {
                Message::create([
                    'from_user_id' => $fromUser->id,
                    'to_user_id' => $toUser->id,
                    'message' => $messageText,
                    'is_read' => $index < count($messages) - 1, // All messages except the last one are read
                    'created_at' => now()->subDays(rand(1, 7))->subHours(rand(1, 24)),
                ]);
            }
        }
    }
} 