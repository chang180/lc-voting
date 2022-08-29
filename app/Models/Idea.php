<?php

namespace App\Models;

use App\Models\Vote;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Idea extends Model
{
    use HasFactory, Sluggable;

    const PAGINATION_COUNT = 10;

    protected $guard = [];

    protected $fillable = [
        'user_id',
        'category_id',
        'status_id',
        'title',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function votes()
    {
        return $this->belongsToMany(User::class, 'votes');
    } 

    public function isVotedByUser(?User $user)
    {
        if (!$user) {
            return false;
        }
        return Vote::where('user_id', $user->id)
        ->where('idea_id', $this->id)
        ->exists();
    }

    public function vote(User $user)
    {
        Vote::create([
            'idea_id' => $this->id,
            'user_id' => $user->id,
        ]);
    }

    public function removeVote(User $user)
    {
        Vote::where('user_id', $user->id)
        ->where('idea_id', $this->id)
        ->first()
        ->delete();
    }

    /**
     * Return the sluggable configuration array for this model.
     * 
     * @return array
     * 
     */

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
