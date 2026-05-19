<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'short_name', 'logo', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function matchesAsTeam1()
    {
        return $this->hasMany(GameMatch::class, 'team1_id');
    }

    public function matchesAsTeam2()
    {
        return $this->hasMany(GameMatch::class, 'team2_id');
    }

    public function totalPoints()
    {
        return $this->results()->sum('points');
    }
}
