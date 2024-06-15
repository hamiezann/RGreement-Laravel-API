<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class House_Details extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'user_id',
        'rent_address',
        'latitude',
        'longitude',
        'uni_identifier',
        'prefered_occupants',
        'type_of_house',
        'description',
        'rent_fee',
        'number_of_rooms',
        'amenities',
        'num_bedrooms',
        'num_toilets',
        'available',
        'contract_status',
    ];

    // public function images()
    // {
    //     return $this->hasMany(Image::class, 'house_detail_id');
    // }
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    protected $table = 'house_details';


    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
