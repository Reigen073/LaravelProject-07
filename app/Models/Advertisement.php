<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'price', 'category', 'type', 'status', 'qr_code', 'image', 'condition', 'expires_at', 'acquirer_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
    public function biddings()
    {
        return $this->hasMany(Bidding::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

}
