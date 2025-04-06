<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'description', 'price', 'category', 'wear_rate', 'type', 'status', 'qr_code', 'image', 'condition', 'expires_at', 'acquirer_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function usersWhoFavorited()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function relatedAdvertisements() {
        return $this->belongsToMany(Advertisement::class, 'related_advertisement', 'advertisement_id', 'related_advertisement_id');
    }
    
    public function biddings()
    {
        return $this->hasMany(Bidding::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function toggleFavorite(Advertisement $advertisement)
    {
        $user = auth()->user();
        
        if ($user->favorites()->where('advertisement_id', $advertisement->id)->exists()) {
            $user->favorites()->detach($advertisement);
            return back()->with('success', 'Advertentie uit je favorieten verwijderd!');
        }
        
        $user->favorites()->attach($advertisement);
        return back()->with('success', 'Advertentie toegevoegd aan je favorieten!');
    }

}
