<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleRestaurants extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $hidden = ['date_time'];

    protected $fillable = [
        'key_search',
        'business_status',
        'formatted_address',
        'geometry',
        'icon',
        'icon_background_color',
        'icon_mask_base_uri',
        'name',
        'opening_hours',
        'photos',
        'photos_original',
        'place_id',
        'plus_code',
        'rating',
        'reference',
        'types',
        'user_ratings_total',
        'date_current'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTable('google_restaurants');
    }
}
