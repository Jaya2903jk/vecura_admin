@if ($paginator->hasPages())
<nav aria-label="Page navigation">
    <ul class="pagination mb-0">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Previous</a>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}&{{ http_build_query($append ?? []) }}">Previous</a>
            </li>
        @endif

        {{-- Pages --}}
        @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
            <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}"
                @if($page == $paginator->currentPage()) aria-current="page" @endif>
                <a class="page-link" href="{{ $url }}&{{ http_build_query($append ?? []) }}">{{ $page }}</a>
            </li>
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}&{{ http_build_query($append ?? []) }}">Next</a>
            </li>
        @else
            <li class="page-item disabled">
                <a class="page-link" href="#">Next</a>
            </li>
        @endif

    </ul>
</nav>
@endif
