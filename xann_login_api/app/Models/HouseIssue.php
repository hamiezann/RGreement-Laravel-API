<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HouseIssue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'landlord_id',
        'renter_id',
        'house_id',
        'description',
        'image',
        'amount_requested',
        'status',
    ];

    /**
     * Get the images for the house issue.
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * Get the landlord that owns the house issue.
     */
    public function landlord()
    {
        return $this->belongsTo(User::class, 'landlord_id');
    }

    /**
     * Get the renter that owns the house issue.
     */
    public function renter()
    {
        return $this->belongsTo(User::class, 'renter_id');
    }

    /**
     * Get the house that owns the house issue.
     */
    public function house()
    {
        return $this->belongsTo(House_Details::class, 'house_id');
    }
}
