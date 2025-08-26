<div>
    <div class="header">
        {{ $note->title }}
    </div>
    <div class="body">
        {!! $note->renderRichContent('body') !!}
    </div>
</div>