<div class="comment-item" id="comment-{{ $comment->id }}">
    <div class="comment-header">
        <span class="comment-author">{{ htmlspecialchars($comment->user->name ?? 'Аноним') }}</span>
        <span class="comment-date">{{ $commentDate }}</span>
    </div>
    <div class="comment-content">
        {{ htmlspecialchars($comment->content) }}
    </div>
    @auth
        @if(Auth::id() === $comment->user_id)
            <div class="comment-actions">
                <button type="button" class="btn-comment-edit" onclick="editComment({{ $comment->id }}, '{{ addslashes($comment->content) }}')">✏️ Редактировать</button>
                <button type="button" class="btn-comment-delete" onclick="deleteComment({{ $comment->id }})">🗑️ Удалить</button>
            </div>
        @endif
    @endauth
</div>