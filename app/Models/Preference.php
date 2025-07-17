<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'preferred_age_min',
        'preferred_age_max',
        'preferred_gender',
        'preferred_religion',
        'preferred_caste',
        'preferred_income_min',
        'preferred_income_max',
        'preferred_education',
        'preferred_location',
        'preferred_occupation',
        'age_weight',
        'gender_weight',
        'religion_weight',
        'caste_weight',
        'income_weight',
        'education_weight',
        'location_weight',
        'occupation_weight',
    ];

    protected $casts = [
        'preferred_age_min' => 'integer',
        'preferred_age_max' => 'integer',
        'preferred_income_min' => 'integer',
        'preferred_income_max' => 'integer',
        'age_weight' => 'float',
        'gender_weight' => 'float',
        'religion_weight' => 'float',
        'caste_weight' => 'float',
        'income_weight' => 'float',
        'education_weight' => 'float',
        'location_weight' => 'float',
        'occupation_weight' => 'float',
    ];

    /**
     * Get the user that owns the preference
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get default weights for preferences
     */
    public static function getDefaultWeights(): array
    {
        return [
            'age_weight' => 1.0,
            'gender_weight' => 1.0,
            'religion_weight' => 1.0,
            'caste_weight' => 1.0,
            'income_weight' => 1.0,
            'education_weight' => 1.0,
            'location_weight' => 1.0,
            'occupation_weight' => 1.0,
        ];
    }
} 