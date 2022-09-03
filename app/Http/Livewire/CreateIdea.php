<?php

namespace App\Http\Livewire;

use App\Models\Idea;
use Livewire\Component;
use App\Models\Category;
use App\Models\Vote;
use Illuminate\Http\Response;

class CreateIdea extends Component
{

    public $title;
    public $category = 1;
    public $description;

    protected $rules = [
        'title' => 'required|min:4|max:255',
        'category' => 'required|integer',
        'description' => 'required|min:4|max:255',
    ];


    public function createIdea()
    {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->validate();

        Idea::create([
            'user_id' => auth()->id(),
            'category_id' => $this->category,
            'status_id' => 1,
            'title' => $this->title,
            'description' => $this->description,
        ])
        ->vote(auth()->user());

        session()->flash('success_message', 'Idea created successfully!');

        $this->reset();

        return redirect()->route('idea.index');
    }

    public function render()
    {
        return view('livewire.create-idea', [
            'categories' => Category::all(),
        ]);
    }
}
