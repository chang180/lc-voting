<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;

class EditIdea extends Component
{

    public $category = 1;

    protected $rules = [
        'title' => 'required|min:4|max:255',
        'category' => 'required|integer',
        'description' => 'required|min:4|max:255',
    ];

    public function render()
    {
        return view('livewire.edit-idea',[
            'categories' => Category::all(),
        ]);
    }
}
