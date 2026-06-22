@if ($paginator->hasPages())
<nav style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px; margin-top:20px;">
    <div style="font-size:12px; color:#9ca3af;">
        Menampilkan {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
    </div>
    <div style="display:flex; align-items:center; gap:6px;">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span style="padding:6px 12px; border-radius:8px; border:1.5px solid #e8e7e3; color:#d1d5db; font-size:13px; font-weight:700; cursor:not-allowed;">‹ Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               style="padding:6px 12px; border-radius:8px; border:1.5px solid #e0dfd9; color:#374151; font-size:13px; font-weight:700; text-decoration:none; background:#fff; transition:all .15s;"
               onmouseover="this.style.background='#0a0a0a';this.style.color='#fff';this.style.borderColor='#0a0a0a';"
               onmouseout="this.style.background='#fff';this.style.color='#374151';this.style.borderColor='#e0dfd9';">
                ‹ Prev
            </a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="padding:6px 10px; font-size:13px; color:#9ca3af;">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="padding:6px 12px; border-radius:8px; background:#0a0a0a; color:#fff; font-size:13px; font-weight:700; border:1.5px solid #0a0a0a;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           style="padding:6px 12px; border-radius:8px; border:1.5px solid #e0dfd9; color:#374151; font-size:13px; font-weight:700; text-decoration:none; background:#fff; transition:all .15s;"
                           onmouseover="this.style.background='#0a0a0a';this.style.color='#fff';this.style.borderColor='#0a0a0a';"
                           onmouseout="this.style.background='#fff';this.style.color='#374151';this.style.borderColor='#e0dfd9';">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               style="padding:6px 12px; border-radius:8px; border:1.5px solid #e0dfd9; color:#374151; font-size:13px; font-weight:700; text-decoration:none; background:#fff; transition:all .15s;"
               onmouseover="this.style.background='#0a0a0a';this.style.color='#fff';this.style.borderColor='#0a0a0a';"
               onmouseout="this.style.background='#fff';this.style.color='#374151';this.style.borderColor='#e0dfd9';">
                Next ›
            </a>
        @else
            <span style="padding:6px 12px; border-radius:8px; border:1.5px solid #e8e7e3; color:#d1d5db; font-size:13px; font-weight:700; cursor:not-allowed;">Next ›</span>
        @endif

    </div>
</nav>
@endif
