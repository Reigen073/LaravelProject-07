<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', 
        ];
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }
    public $incrementing = true; 
    protected $keyType = 'int';

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'advertiser_id');
    }
    public function advertisements()
    {
        return $this->hasMany(Advertisement::class);
    }
    public function favorites()
    {
        return $this->belongsToMany(Advertisement::class, 'favorites', 'user_id', 'advertisement_id')
                    ->withTimestamps();
    }
    public function contracts()
    {
        return $this->hasMany(Contract::class); 
    }

}
