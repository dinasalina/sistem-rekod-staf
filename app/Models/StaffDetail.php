<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StaffDetail extends Model
{
     use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'staff_id_number',
        'department',
        'position',
        'phone_number',
        'address',
        'date_joined',
        'salary',
        'bank_name',
        'bank_account_number',
        'emergency_contact_name',
        'emergency_contact_phone',
        'profile_image_path',
    ];

    /**
     * Get the user that owns the staff detail.
     */
    public function user() // Nama method ni penting
    {
        return $this->belongsTo(User::class);
    }
}
