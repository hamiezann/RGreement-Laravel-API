<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\House_Details;
class Tenant extends Model
{
    use HasFactory;
    protected $fillable = [
        'tenant_id',
        'tenant_status',
        'house_id',
        'sign_contract_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function house()
    {
        return $this->belongsTo(House_Details::class, 'house_id');
    }

    protected $table = 'tenant_details';
}
