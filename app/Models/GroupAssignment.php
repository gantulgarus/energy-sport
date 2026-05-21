<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupAssignment extends Model
{
    protected $fillable = ['sport_id','gender','group_name','team_id','order_num'];

    public function team()  { return $this->belongsTo(Team::class); }
    public function sport() { return $this->belongsTo(Sport::class); }
}
