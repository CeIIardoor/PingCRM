<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function organisations()
    {
        return $this->hasMany(Organisation::class);
    }

    public function collaborateurs()
    {
        return $this->hasMany(Collaborateur::class);
    }
}
