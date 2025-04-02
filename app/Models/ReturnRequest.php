<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = ['advertisement_id', 'user_id', 'image', 'reason', 'status'];

    public function advertisement() {
        return $this->belongsTo(Advertisement::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
