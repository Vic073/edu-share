<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Material;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'department',
        'student_id',
        'institution_id',
        'subscription_tier',
        'kyc_status',
        'id_document_path',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPremium()
    {
        return $this->subscription_tier === 'premium';
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class)->latest();
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function getMonthlyUploadsCount()
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        return $this->materials()
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();
    }

    public function favorites()
    {
        return $this->belongsToMany(Material::class, 'favorites')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function downloadedMaterials()
    {
        return $this->belongsToMany(Material::class, 'downloads')
                   ->withPivot('created_at')
                   ->withTimestamps();
    }

    public function aiUsageLogs()
    {
        return $this->hasMany(AIUsageLog::class);
    }

    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
}