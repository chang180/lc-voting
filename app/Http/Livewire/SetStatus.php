<?php

namespace App\Http\Livewire;

use App\Jobs\NotifyAllVoters;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\Status;
use Livewire\Component;
use Illuminate\Http\Response;

class SetStatus extends Component
{

    public $idea;
    public $status;
    public $comment;
    public $notifyAllVoters;

    public function mount(Idea $idea)
    {
        $this->idea = $idea;
        $this->status = $this->idea->status_id;
    }

    public function setStatus()
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if((int)$this->status === $this->idea->status_id){
            $this->emit('statusWasUpdatedError','Status is the same.');
            return;
        }

        $this->idea->update([
            'status_id' => $this->status,
        ]);

        if ($this->notifyAllVoters) {
            NotifyAllVoters::dispatch($this->idea);
        }

        Comment::create([
            'user_id' => auth()->id(),
            'idea_id' => $this->idea->id,
            'status_id' => $this->status,
            'body' => $this->comment ?? 'Status changed to ' . Status::find($this->status)->name,
            'is_status_update' => true,
        ]);

        $this->reset('comment', 'notifyAllVoters');

        $this->emit('statusWasUpdated', 'Status changed to ' . Status::find($this->status)->name);
    }

    public function render()
    {
        return view('livewire.set-status', [
            'statuses' => Status::all(),
        ]);
    }
}
