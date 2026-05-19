<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sport extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'gender_type', 'sort_order'];

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function matches()
    {
        return $this->hasMany(GameMatch::class);
    }

    public function isMixed(): bool
    {
        return $this->gender_type === 'mixed';
    }
}
