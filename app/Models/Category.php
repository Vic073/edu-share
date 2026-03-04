<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public function materials()
{
    return $this->hasMany(Material::class);
}

// app/Models/Download.php
public function material()
{
    return $this->belongsTo(Material::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}
}
