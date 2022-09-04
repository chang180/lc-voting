<?php

namespace App\Http\Livewire;

use App\Models\Idea;
use App\Models\Vote;
use Livewire\Component;
use Illuminate\Http\Response;

class DeleteIdea extends Component
{
    public $idea;

    public function mount(Idea $idea)
    {
        $this->idea = $idea;
    }

    public function deleteIdea(){
        if (auth()->guest() || auth()->user()->cannot('delete', $this->idea)) {
            abort(Response::HTTP_FORBIDDEN);
        }
        //delete idea with related votes
        Vote::where('idea_id', $this->idea->id)->delete();

        $this->idea->delete();
        
        return redirect()->route('idea.index');
    }

    public function render()
    {
        return view('livewire.delete-idea');
    }
}
