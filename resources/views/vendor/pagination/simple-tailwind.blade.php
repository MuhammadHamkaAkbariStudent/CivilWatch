@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="display:flex; align-items:center; gap:4px;">

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span style="display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 12px;border-radius:7px;font-size:13px;font-weight:500;color:var(--text-light);border:1px solid var(--border);background:var(--surface);cursor:not-allowed;user-select:none;">
                ‹
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 12px;border-radius:7px;font-size:13px;font-weight:500;color:var(--text-muted);border:1px solid var(--border);background:var(--surface);text-decoration:none;transition:all .15s;" onmouseover="this.style.background='var(--bg)';this.style.color='var(--primary)';this.style.borderColor='var(--primary)'" onmouseout="this.style.background='var(--surface)';this.style.color='var(--text-muted)';this.style.borderColor='var(--border)'">
                ‹
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span style="display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 8px;font-size:13px;color:var(--text-light);">
                    {{ $element }}
                </span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 12px;border-radius:7px;font-size:13px;font-weight:600;color:#fff;border:1px solid var(--primary);background:var(--primary);">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" style="display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 12px;border-radius:7px;font-size:13px;font-weight:500;color:var(--text-muted);border:1px solid var(--border);background:var(--surface);text-decoration:none;transition:all .15s;" onmouseover="this.style.background='var(--bg)';this.style.color='var(--primary)';this.style.borderColor='var(--primary)'" onmouseout="this.style.background='var(--surface)';this.style.color='var(--text-muted)';this.style.borderColor='var(--border)'">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 12px;border-radius:7px;font-size:13px;font-weight:500;color:var(--text-muted);border:1px solid var(--border);background:var(--surface);text-decoration:none;transition:all .15s;" onmouseover="this.style.background='var(--bg)';this.style.color='var(--primary)';this.style.borderColor='var(--primary)'" onmouseout="this.style.background='var(--surface)';this.style.color='var(--text-muted)';this.style.borderColor='var(--border)'">
                ›
            </a>
        @else
            <span style="display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 12px;border-radius:7px;font-size:13px;font-weight:500;color:var(--text-light);border:1px solid var(--border);background:var(--surface);cursor:not-allowed;user-select:none;">
                ›
            </span>
        @endif

    </nav>
@endif