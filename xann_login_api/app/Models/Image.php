<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    // protected $fillable = ['house_detail_id', 'path'];
    // public function houseDetail()
    // {
    //     return $this->belongsTo(House_Details::class);
    // }

    protected $fillable = ['path', 'imageable_id', 'imageable_type'];

    public function imageable()
    {
        return $this->morphTo();
    }
}
