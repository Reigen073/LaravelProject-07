<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'show_ads',
        'show_favorites',
        'show_intro',
        'show_image',
        'show_custom_link',
        'show_contracts',
        'bg_color',
        'text_color',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
