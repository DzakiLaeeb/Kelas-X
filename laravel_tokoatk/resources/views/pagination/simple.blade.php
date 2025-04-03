@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="pagination-item disabled">&laquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-item">&laquo;</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="pagination-item disabled">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="pagination-item active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pagination-item">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-item">&raquo;</a>
        @else
            <span class="pagination-item disabled">&raquo;</span>
        @endif
    </div>
@endif

<style>
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px 0;
        padding: 0;
        list-style: none;
    }
    
    .pagination-item {
        display: inline-block;
        margin: 0 5px;
        width: 40px;
        height: 40px;
        line-height: 40px;
        text-align: center;
        color: #3498db;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .pagination-item.active {
        background-color: #3498db;
        color: #fff;
        border-color: #3498db;
    }
    
    .pagination-item.disabled {
        color: #aaa;
        cursor: not-allowed;
    }
    
    .pagination-item:hover:not(.active):not(.disabled) {
        background-color: #f5f5f5;
        border-color: #3498db;
    }
    
    @media (max-width: 576px) {
        .pagination-item {
            width: 35px;
            height: 35px;
            line-height: 35px;
            margin: 0 3px;
        }
    }
</style>
