<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['faculty_id', 'name', 'code', 'year_level'];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
