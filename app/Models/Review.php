<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'advertisement_id', 'advertiser_id', 'comment', 'rating'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function advertiser()
    {
        return $this->belongsTo(User::class, 'advertiser_id');
    }
}
