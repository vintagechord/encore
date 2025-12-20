@if ($paginator->hasPages())
    <div class="pager">
        <nav role="navigation" aria-label="Pagination" class="enc-pager">
            @php($current = $paginator->currentPage())
            @php($last = $paginator->lastPage())

            {{-- Prev to 10 --}}
            @if ($current > 10)
                <a href="{{ $paginator->url(max(1, $current - 10)) }}" aria-label="Previous 10">PREV TO 10</a>
            @else
                <span class="disabled" aria-disabled="true">PREV TO 10</span>
            @endif

            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <span class="disabled" aria-disabled="true">PREV</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev">PREV</a>
            @endif

            {{-- Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="disabled" aria-disabled="true">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next">NEXT</a>
            @else
                <span class="disabled" aria-disabled="true">NEXT</span>
            @endif

            {{-- Next to 10 --}}
            @if ($current + 10 <= $last)
                <a href="{{ $paginator->url(min($last, $current + 10)) }}" aria-label="Next 10">NEXT TO 10</a>
            @else
                <span class="disabled" aria-disabled="true">NEXT TO 10</span>
            @endif
        </nav>
    </div>
@endif

