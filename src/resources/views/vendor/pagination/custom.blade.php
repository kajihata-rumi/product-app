@if ($paginator->hasPages())
<nav class="pagination-nav" role="navigation" aria-label="Pagination Navigation">
    <ul class="pagination-list">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
        <li class="pagination-item is-disabled" aria-disabled="true">
            <span class="pagination-link" aria-hidden="true">&lt;</span>
        </li>
        @else
        <li class="pagination-item">
            <a class="pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&lt;</a>
        </li>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
        {{-- "..." --}}
        @if (is_string($element))
        <li class="pagination-item is-disabled" aria-disabled="true">
            <span class="pagination-link">{{ $element }}</span>
        </li>
        @endif

        {{-- Page links --}}
        @if (is_array($element))
        @foreach ($element as $page => $url)
        @if ($page == $paginator->currentPage())
        <li class="pagination-item is-active" aria-current="page">
            <span class="pagination-link">{{ $page }}</span>
        </li>
        @else
        <li class="pagination-item">
            <a class="pagination-link" href="{{ $url }}">{{ $page }}</a>
        </li>
        @endif
        @endforeach
        @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
        <li class="pagination-item">
            <a class="pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&gt;</a>
        </li>
        @else
        <li class="pagination-item is-disabled" aria-disabled="true">
            <span class="pagination-link" aria-hidden="true">&gt;</span>
        </li>
        @endif
    </ul>
</nav>
@endif