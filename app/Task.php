<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
    protected $fillable = ['title','description', 'status'];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'user_id');
    }
}
