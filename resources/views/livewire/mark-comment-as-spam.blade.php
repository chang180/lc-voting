<x-modal-confirm 
    livewire-event-to-open-modal="markCommentAsSpamWasSet"
    event-to-close-modal="commentWasMarkedAsSpam"
    modal-title="Mark Comment as Spam"
    modal-description="Are you sure you want to mark this comment as spam? This action cannot be undone."
    modal-confirm-button-text="Mark as Spam"
    wire-click="markAsSpam"
/>