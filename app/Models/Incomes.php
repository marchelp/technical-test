<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Incomes extends Model
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
