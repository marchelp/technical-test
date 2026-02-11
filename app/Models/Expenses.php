<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'pocket_id',
        'amount',
        'notes',
    ];

    public function pocket() {
        return $this->belongsTo(UserPocket::class, 'pocket_id');
    }
}
