<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use Illuminate\Http\Response;
use Livewire\Component;

class MarkCommentAsSpam extends Component
{
    public $comment;

    protected $listeners = ['setMarkCommentAsSpam'];

    public function setMarkCommentAsSpam($commentId)
    {
        $this->comment = Comment::find($commentId);

        $this->emit('markCommentAsSpamWasSet');
    }

    public function markAsSpam()
    {
        if(auth()->guest()){
            abort(Response::HTTP_FORBIDDEN);
        }

        $this->comment->spam_reports += 1;

        $this->comment->save();

        $this->emit('commentWasMarkedAsSpam', 'Comment was marked as spam.');
    }

    public function render()
    {
        return view('livewire.mark-comment-as-spam');
    }
}
