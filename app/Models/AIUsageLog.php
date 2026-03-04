<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIUsageLog extends Model
{
    use HasFactory;

    protected $table = 'ai_usage_logs';

    protected $fillable = ['user_id', 'query_type', 'tokens_used'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
