<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    protected $fillable = [
      'name',
      'description',
      'release_year',
      'rating',
      'ticket_price',
      'user',
      'country',
      'genre',
      'photo'
    ];
    
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
