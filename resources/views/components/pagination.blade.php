@if ($paginator->hasPages())
@php
    $currentPage = $paginator->currentPage();
    $lastPage = $paginator->lastPage();
    $appendQuery = !empty($append) ? '&' . http_build_query($append) : '';
    $delta = 2;

    $start = max(1, $currentPage - $delta);
    $end = min($lastPage, $currentPage + $delta);

    if ($currentPage - $delta <= 1) {
        $end = min($lastPage, 1 + ($delta * 2));
    }
    if ($currentPage + $delta >= $lastPage) {
        $start = max(1, $lastPage - ($delta * 2));
    }
@endphp

<nav aria-label="Page navigation">
    <ul class="pagination pagination-sm mb-0">

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link"><i class="ti ti-chevron-left fs-12"></i></span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}{{ $appendQuery }}" rel="prev"><i class="ti ti-chevron-left fs-12"></i></a>
            </li>
        @endif

        {{-- First Page --}}
        @if ($start > 1)
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url(1) }}{{ $appendQuery }}">1</a>
            </li>
            @if ($start > 2)
                <li class="page-item disabled" aria-disabled="true"><span class="page-link">&hellip;</span></li>
            @endif
        @endif

        {{-- Page Numbers --}}
        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $currentPage)
                <li class="page-item active" aria-current="page">
                    <span class="page-link">{{ $page }}</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($page) }}{{ $appendQuery }}">{{ $page }}</a>
                </li>
            @endif
        @endfor

        {{-- Last Page --}}
        @if ($end < $lastPage)
            @if ($end < $lastPage - 1)
                <li class="page-item disabled" aria-disabled="true"><span class="page-link">&hellip;</span></li>
            @endif
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->url($lastPage) }}{{ $appendQuery }}">{{ $lastPage }}</a>
            </li>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}{{ $appendQuery }}" rel="next"><i class="ti ti-chevron-right fs-12"></i></a>
            </li>
        @else
            <li class="page-item disabled" aria-disabled="true">
                <span class="page-link"><i class="ti ti-chevron-right fs-12"></i></span>
            </li>
        @endif

    </ul>
</nav>
@endif
