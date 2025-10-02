<div>
    <button x-data class="back-to-top"
{{--
        @click="$dispatch('scroll-to-top')"
--}}
        @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        x-tooltip.raw="Back to top"
    >
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
        </svg>
    </button>

{{--
@push('scripts')
<script>
    window.addEventListener('scroll-to-top', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
</script>
@endpush
--}}
</div>
