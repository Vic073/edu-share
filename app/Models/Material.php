<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'course_code',
        'description',
        'file_path',
        'file_type',
        'file_size',
        'visibility',
        'uploader_role',
        'download_count',
        'institution_id',
        'faculty_id',
        'course_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites', 'material_id', 'user_id')->withTimestamps();
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    public function downloadedBy()
    {
        return $this->belongsToMany(User::class, 'downloads')->withPivot('created_at')->withTimestamps();
    }
}
