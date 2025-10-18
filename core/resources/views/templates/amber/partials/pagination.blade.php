<div class="pagination">
    @if ($paginator->onFirstPage())
        <span class="prev disabled">
            << {{ __('pagination.previous') }}</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="prev">
                    << {{ __('pagination.previous') }}</a>
    @endif

    <!-- Pagination Links -->
    @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
        @if ($page == $paginator->currentPage())
            <a href="{{ $url }}" class="page-link active">{{ $page }}</a>
        @else
            <a href="{{ $url }}" class="page-link">{{ $page }}</a>
        @endif
    @endforeach

    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="next">{{ __('pagination.next') }} >> </a>
    @else
        <span class="next disabled">{{ __('pagination.next') }} >> </span>
    @endif
</div>