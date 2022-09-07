<x-modal-confirm 
    event-to-open-modal="custom-show-mark-idea-as-not-spam-modal"
    event-to-close-modal="ideaWasMarkedAsNotSpam"
    modal-title="Mark Idea as Not Spam"
    modal-description="Are you sure you want to mark this idea as not spam?"
    modal-confirm-button-text="Not Spam"
    wire-click="markAsNotSpam"
/>