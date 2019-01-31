<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];
    public function questions()
    {
        return $this->belongsToMany('App\Question');
    }
    public function scopeOrdered($query)
    {
        return $query->orderBy('label', 'asc')->get();
    }
}
