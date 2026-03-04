<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'abbreviation', 'location', 'logo_path', 'is_active'];

    public function faculties()
    {
        return $this->hasMany(Faculty::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
