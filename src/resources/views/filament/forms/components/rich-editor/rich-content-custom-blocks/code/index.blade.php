<div>
    @php
        $collapsed ??= false;
    @endphp

    <details
        @if( !$collapsed ) open @endif
        class="ml-4 overflow-scroll rounded-md bg-zinc-200 dark:bg-zinc-900"
    >
        <summary class="pl-2 italic cursor-pointer">
            {{  $title ?? 'Sample code...' }}
            <span class="float-end mr-2 text-xs text-zinc-500 inline-flex items-center">...</span>
        </summary>
        <code class="fi-not-prose">
            <pre class="p-2 rounded-lg  min-w-fit">{!! $highlightedCode !!}</pre>
        </code>
    </details>
</div>
