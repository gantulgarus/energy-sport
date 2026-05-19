<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = ['team_id', 'sport_id', 'gender', 'place', 'points', 'notes'];

    protected $casts = ['points' => 'decimal:1'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }
}
