<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }

    public static function getCount()
    {
        return Idea::query()
        ->selectRaw('count(*) as all_statuses')
        ->selectRaw('count(CASE WHEN status_id = 1 THEN 1 END) as open')
        ->selectRaw('count(CASE WHEN status_id = 2 THEN 1 END) as considering')
        ->selectRaw('count(CASE WHEN status_id = 3 THEN 1 END) as in_progress')
        ->selectRaw('count(CASE WHEN status_id = 4 THEN 1 END) as implemented')
        ->selectRaw('count(CASE WHEN status_id = 5 THEN 1 END) as closed')
        ->first()
        ->toArray();
    }

}
