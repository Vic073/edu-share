<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['comment', 'user_id'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}