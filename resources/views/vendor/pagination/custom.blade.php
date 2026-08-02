@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="display: flex; align-items: center; justify-content: space-between; width: 100%; flex-wrap: wrap; gap: 12px;">
        {{-- Results Summary --}}
        <div style="font-size: 13px; color: var(--text-muted, #64748b); font-weight: 500;">
            Menampilkan <span style="font-weight: 700; color: var(--text-main, #0f172a);">{{ $paginator->firstItem() }}</span> - <span style="font-weight: 700; color: var(--text-main, #0f172a);">{{ $paginator->lastItem() }}</span> dari <span style="font-weight: 700; color: var(--text-main, #0f172a);">{{ $paginator->total() }}</span> data
        </div>

        {{-- Page Buttons --}}
        <div style="display: inline-flex; align-items: center; gap: 5px; flex-wrap: wrap;">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span style="padding: 6px 12px; font-size: 13px; font-weight: 600; color: #94a3b8; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; cursor: not-allowed;" aria-disabled="true">
                    <i class="fas fa-chevron-left"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="padding: 6px 12px; font-size: 13px; font-weight: 600; color: #334155; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#ffffff'">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span style="padding: 6px 10px; font-size: 13px; color: #94a3b8;" aria-disabled="true">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span style="padding: 6px 12px; font-size: 13px; font-weight: 700; color: #ffffff; background: #2563eb; border: 1px solid #2563eb; border-radius: 8px;" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" style="padding: 6px 12px; font-size: 13px; font-weight: 600; color: #334155; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#ffffff'">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="padding: 6px 12px; font-size: 13px; font-weight: 600; color: #334155; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#ffffff'">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <span style="padding: 6px 12px; font-size: 13px; font-weight: 600; color: #94a3b8; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; cursor: not-allowed;" aria-disabled="true">
                    <i class="fas fa-chevron-right"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
