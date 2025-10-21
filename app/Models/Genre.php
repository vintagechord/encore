<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = ['discipline_id', 'slug', 'name'];

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }
}
