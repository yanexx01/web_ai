<div class="comment-item" id="comment-{{ $comment->id }}">
    <div class="comment-header">
        <span class="comment-author">{{ htmlspecialchars($comment->user->name ?? 'Аноним') }}</span>
        <span class="comment-date">{{ $commentDate }}</span>
    </div>
    <div class="comment-content">
        {{ htmlspecialchars($comment->content) }}
    </div>
</div>