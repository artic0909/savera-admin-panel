@if ($paginator->hasPages())
    <div class="custom-pagination">
        <ul>
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><span><i class="fi fi-rr-angle-left"></i></span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        data-page="{{ $paginator->currentPage() - 1 }}"><i class="fi fi-rr-angle-left"></i></a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="disabled"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="active"><span>{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}" data-page="{{ $page }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" rel="next"
                        data-page="{{ $paginator->currentPage() + 1 }}"><i class="fi fi-rr-angle-right"></i></a></li>
            @else
                <li class="disabled"><span><i class="fi fi-rr-angle-right"></i></span></li>
            @endif
        </ul>
    </div>
@endif

<style>
    .custom-pagination {
        display: flex;
        justify-content: center;
        margin-top: 50px;
        margin-bottom: 50px;
    }

    .custom-pagination ul {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 10px;
        align-items: center;
    }

    .custom-pagination li {
        display: inline-flex;
    }

    .custom-pagination li a,
    .custom-pagination li span {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ddd;
        border-radius: 50%;
        color: #333;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
        background: #fff;
        cursor: pointer;
    }

    .custom-pagination li.active span {
        background-color: #000;
        color: #fff;
        border-color: #000;
    }

    .custom-pagination li a:hover {
        background-color: #f5f5f5;
        border-color: #000;
    }

    .custom-pagination li.disabled span {
        color: #ccc;
        cursor: not-allowed;
    }

    .custom-pagination li i {
        font-size: 14px;
        display: flex;
    }
</style>
