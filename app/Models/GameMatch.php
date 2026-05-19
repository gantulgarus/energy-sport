<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameMatch extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'sport_id', 'team1_id', 'team2_id', 'gender',
        'round', 'venue', 'scheduled_at',
        'team1_score', 'team2_score', 'status', 'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function sport()
    {
        return $this->belongsTo(Sport::class);
    }

    public function team1()
    {
        return $this->belongsTo(Team::class, 'team1_id');
    }

    public function team2()
    {
        return $this->belongsTo(Team::class, 'team2_id');
    }

    public function isFinished(): bool
    {
        return $this->status === 'finished';
    }

    public function isLive(): bool
    {
        return $this->status === 'live';
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'scheduled' => 'Болоогүй',
            'live'      => 'Явагдаж байна',
            'finished'  => 'Дууссан',
        };
    }

    public function genderLabel(): string
    {
        return match($this->gender) {
            'male'   => 'Эрэгтэй',
            'female' => 'Эмэгтэй',
            'mixed'  => 'Холимог',
        };
    }
}
