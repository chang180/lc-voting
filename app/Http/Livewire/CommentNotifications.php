<?php

namespace App\Http\Livewire;

use App\Models\Idea;
use App\Models\Comment;
use Livewire\Component;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;

class CommentNotifications extends Component
{
    const NOTIFICATION_THRESHOLD = 20;

    public $notifications;
    public $notificationsCount;
    public $isLoading;

    protected $listeners = ['getNotifications'];

    public function mount()
    {
        $this->notifications = collect([]);
        $this->getNotificationCount();
        $this->isLoading = true;
    }

    public function getNotificationCount()
    {
        $this->notificationsCount = auth()->user()->unreadNotifications()->count();

        if ($this->notificationsCount > self::NOTIFICATION_THRESHOLD) {
            $this->notificationsCount = self::NOTIFICATION_THRESHOLD . '+';
        }
    }

    public function getNotifications()
    {
        $this->notifications = auth()
            ->user()
            ->unreadNotifications()
            ->latest()
            ->take(self::NOTIFICATION_THRESHOLD)
            ->get();

        $this->isLoading = false;
    }

    public function markAsRead($notificationId)
    {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $notification = DatabaseNotification::find($notificationId);
        $notification->markAsRead();

        $this->scrollToComment($notification);
    }

    public function scrollToComment($notification)
    {
        $idea = Idea::find($notification->data['idea_id']);
        if(!$idea){
            session()->flash('error_message', 'This idea no longer exists.');

            return redirect()->route('idea.index');
        }
        $comment = Comment::find($notification->data['comment_id']);
        if(!$comment){
            session()->flash('error_message', 'This comment no longer exists.');

            return redirect()->route('idea.index');
        }

        $comments = $idea->comments->pluck('id');
        $indexofComment = $comments->search($comment->id);

        $page = (int)($indexofComment / $comment->getPerPage()) + 1;

        session()->flash('scrollToComment', $comment->id);

        return redirect()->route('idea.show', [
            'idea' => $this->notifications->where('id', $notification->id)->first()->data['idea_slug'],
            'page' => $page,
        ]);
    }

    public function markAllAsRead()
    {
        if (auth()->guest()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        auth()->user()->unreadNotifications->markAsRead();
        $this->getNotificationCount();
        $this->getNotifications();
    }

    public function render()
    {
        return view('livewire.comment-notifications');
    }
}
