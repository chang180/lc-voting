<x-modal-confirm 
    livewire-event-to-open-modal="markCommentAsNotSpamWasSet"
    event-to-close-modal="commentWasMarkedAsNotSpam"
    modal-title="Reset Comment Spam Counter"
    modal-description="Are you sure you want to mark this comment as not spam? This action cannot be undone."
    modal-confirm-button-text="Mark as Not Spam"
    wire-click="markAsNotSpam"
/>