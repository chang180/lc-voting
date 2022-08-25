<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }

    public function getStatusClasses()
    {
        switch ($this->name) {
            case 'Open':
                return 'bg-gray-200';
            case 'Considering':
                return 'bg-purple text-white';
            case 'In Progress':
                return 'bg-yellow text-white';
            case 'Implemented':
                return 'bg-green text-white';
            case 'Closed':
                return 'bg-red text-white';
        }
        return 'bg-gray-200';
    }
}
