<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use Illuminate\Http\Response;
use Livewire\Component;

class MarkCommentAsNotSpam extends Component
{
    public $comment;

    protected $listeners = ['setMarkCommentAsNotSpam'];

    public function setMarkCommentAsNotSpam($commentId)
    {
        $this->comment = Comment::find($commentId);

        $this->emit('markCommentAsNotSpamWasSet');
    }

    public function markAsNotSpam()
    {
        if(auth()->guest()){
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->comment->spam_reports = 0;

        $this->comment->save();

        $this->emit('commentWasMarkedAsNotSpam', 'Comment spam counter was reset.');
    }

    public function render()
    {
        return view('livewire.mark-comment-as-not-spam');
    }
}
