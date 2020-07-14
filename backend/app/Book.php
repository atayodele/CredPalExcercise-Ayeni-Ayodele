<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable=['isbn', 'title', 'description', 'author_id'];

    public function authors(){
        return $this->belongsToMany('App\Author');
    }
}
