@if ($paginator->hasPages())
<nav>
    <ul class="pagination justify-content-center">

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link bg-light border-secondary">&laquo;</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link bg-light border-secondary" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- Dots (e.g. ...) --}}
            @if (is_string($element))
                <li class="page-item disabled">
                    <span class="page-link bg-light border-secondary">{{ $element }}</span>
                </li>
            @endif

            {{-- Page Numbers --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active">
                            <span class="page-link bg-success border-primary">{{ $page }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link bg-light border-secondary" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="page-item">
                <a class="page-link bg-light border-secondary" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link bg-light border-secondary">&raquo;</span>
            </li>
        @endif
    </ul>
</nav>
@endif
