<?php

namespace App\Models;

use App\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $perPage = 20;

    protected $fillable = [
        'user_id',
        'idea_id',
        'status_id',
        'body',
        'is_status_update',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function idea()
    {
        return $this->belongsTo(Idea::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
