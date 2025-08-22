<div class="p-4 border rounded flex justify-between items-center">
    <div>
        <h2 class="font-bold">{{ $note->title }}</h2>
        <p class="text-sm text-gray-600">{!! $note->renderRichContent('body') !!}</p>
    </div>
</div>
