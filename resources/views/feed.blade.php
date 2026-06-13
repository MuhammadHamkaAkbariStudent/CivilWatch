<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CivilWatch — Laporan Publik</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-grid">

<x-public-nav active="feed" />

{{-- ═══ FEED CONTENT ═══ --}}
<div style="max-width:1200px;margin:0 auto;padding:32px;">

    {{-- Page Header --}}
    <div class="page-header">
        <div class="page-title" style="display:flex;align-items:center;gap:8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><rect x="9" y="2" width="6" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="15" y2="16"/></svg>
            Laporan Publik
        </div>
        <div class="page-desc">Laporan infrastruktur yang telah diverifikasi dan sedang / sudah ditangani</div>
    </div>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('feed') }}" class="feed-filter-form">
        <div class="filter-bar">
            <div class="search-box">
                <span class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </span>
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="Cari judul laporan..."
                    value="{{ request('search') }}"
                >
            </div>

            <div
                x-data="{
                    open: false,
                    selected: '{{ request('district_id') }}',
                    selectedLabel: '{{ $districts->firstWhere('id', request('district_id'))?->name ?? 'Semua Kecamatan' }}'}"
                class="cw-select-wrapper"
                @click.outside="open = false">
                 {{-- Hidden input agar value tetap terkirim saat form submit --}}
                <input type="hidden" name="district_id" :value="selected">

                {{-- Trigger button --}}
                <button
                    type="button"
                    class="cw-select-trigger"
                    @click="open = !open"
                    :class="{ 'cw-select-trigger--open': open }">
        
                    <span x-text="selectedLabel"></span>
                    <svg class="cw-select-chevron" :class="{ 'rotated': open }"
                        width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                {{-- Dropdown list --}}
                <x-scrollable as="ul" class="cw-select-dropdown" maxHeight="200px" paddingRight="0px" x-show="open" x-transition>
                    <li
                        class="cw-select-option"
                        :class="{ 'cw-select-option--active': selected === '' }"
                        @click="selected = ''; selectedLabel = 'Semua Kecamatan'; open = false"
                        @mouseenter="$el.classList.add('cw-select-option--hover')"
                        @mouseleave="$el.classList.remove('cw-select-option--hover')">
                        Semua Kecamatan
                    </li>
                    @foreach($districts as $d)
                    <li
                        class="cw-select-option"
                        :class="{ 'cw-select-option--active': selected === '{{ $d->id }}' }"
                        @click="selected = '{{ $d->id }}'; selectedLabel = '{{ $d->name }}'; open = false"
                        @mouseenter="$el.classList.add('cw-select-option--hover')"
                        @mouseleave="$el.classList.remove('cw-select-option--hover')">
                        {{ $d->name }}
                    </li>
                    @endforeach
                </x-scrollable>
            </div>

            <div
                x-data="{
                    open: false,
                    selected: '{{ request('sort_by', 'latest') }}',
                    options: [
                        { value: 'latest',  label: 'Terbaru' },
                        { value: 'upvotes', label: 'Upvote Terbanyak' },
                        { value: 'least_upvotes', label: 'Upvote Tersedikit' }],
                    get selectedLabel() {
                        return this.options.find(o => o.value === this.selected)?.label ?? 'Terbaru'}
                }"
                class="cw-select-wrapper"
                @click.outside="open = false">
    
                <input type="hidden" name="sort_by" :value="selected">

                <button
                    type="button"
                    class="cw-select-trigger"
                    @click="open = !open"
                    :class="{ 'cw-select-trigger--open': open }">
                    <span x-text="selectedLabel"></span>
                    <svg class="cw-select-chevron" :class="{ 'rotated': open }"
                        width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <x-scrollable as="ul" class="cw-select-dropdown" maxHeight="200px" paddingRight="0px" x-show="open" x-transition>
                    <template x-for="opt in options" :key="opt.value">
                        <li
                            class="cw-select-option"
                            :class="{ 'cw-select-option--active': selected === opt.value }"
                            @click="selected = opt.value; open = false"
                            x-text="opt.label">
                        </li>
                    </template>
                </x-scrollable>
            </div>

            <button type="submit" class="btn btn-primary" style="display:inline-flex;align-items:center;gap:6px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                Terapkan Filter
            </button>

            @if(request('search') || request('district_id') || request('sort_by'))
                <a href="{{ route('feed') }}" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Status Tabs (Pill Buttons) --}}
    @php
        $feedTabs = [
            [
                'value' => '',
                'label' => 'Semua Laporan',
                'icon'  => '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>',
                'bg'    => 'var(--primary)',
            ],
            [
                'value' => 'published',
                'label' => 'Diterima',
                'icon'  => '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
                'bg'    => 'var(--info)',
            ],
            [
                'value' => 'in_progress',
                'label' => 'Diproses',
                'icon'  => '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>',
                'bg'    => 'var(--danger)',
            ],
            [
                'value' => 'resolved',
                'label' => 'Selesai',
                'icon'  => '<svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
                'bg'    => 'var(--success)',
            ],
        ];
        $activeFeedStatus = request('status', '');
        $statusLabels = [
            'published'   => 'Diterima',
            'in_progress' => 'Diproses',
            'resolved'    => 'Selesai',
        ];
    @endphp

    <div style="display:flex;gap:12px;margin:24px 0 20px 0;overflow-x:auto;padding-bottom:8px;scrollbar-width:none;-ms-overflow-style:none;" class="cw-scrollbar">
        @foreach($feedTabs as $tab)
            @php
                $isActive = $activeFeedStatus === $tab['value'];
                $url = route('feed', array_merge(request()->except(['status', 'page']), $tab['value'] ? ['status' => $tab['value']] : []));
            @endphp
            <a href="{{ $url }}"
               style="
                   display:inline-flex;align-items:center;gap:8px;
                   padding:10px 20px;border-radius:100px;
                   font-size:13.5px;font-weight:600;
                   white-space:nowrap;transition:all 0.2s ease;
                   border: 1.5px solid {{ $isActive ? $tab['bg'] : 'var(--border)' }};
                   background: {{ $isActive ? $tab['bg'] : 'var(--surface)' }};
                   color: {{ $isActive ? '#ffffff' : 'var(--text-muted)' }};
                   box-shadow: {{ $isActive ? 'var(--shadow-sm)' : 'none' }};
               "
               onmouseover="if(!{{ $isActive ? 'true' : 'false' }}) { this.style.borderColor='{{ $tab['bg'] }}'; this.style.color='var(--text)'; }"
               onmouseout="if(!{{ $isActive ? 'true' : 'false' }}) { this.style.borderColor='var(--border)'; this.style.color='var(--text-muted)'; }"
            >
                {!! $tab['icon'] !!}
                {{ $tab['label'] }}
            </a>
        @endforeach
    </div>

    {{-- Result Count --}}
    <p style="font-size:13px;color:var(--text-muted);margin-bottom:20px;">
        Menampilkan <strong style="color:var(--text);">{{ $reports->total() }}</strong> laporan
        @if(request('search'))
            yang cocok dengan "<strong>{{ request('search') }}</strong>"
        @endif
        @if(request('district_id') && ($matchDistrict = $districts->firstWhere('id', request('district_id'))))
            di <strong>{{ $matchDistrict->name }}</strong>
        @endif
        @if(request('status') && isset($statusLabels[request('status')]))
            dengan status <strong>{{ $statusLabels[request('status')] }}</strong>
        @endif
    </p>

    {{-- Report Grid --}}
    @if($reports->count() > 0)
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:20px;">
            @foreach($reports as $report)
            @php
                $statusMap = [
                    'published'   => ['label' => 'Diterima', 'class' => 'b-published'],
                    'in_progress' => ['label' => 'Diproses', 'class' => 'b-in_progress'],
                    'resolved'    => ['label' => 'Selesai',  'class' => 'b-resolved'],
                ];
                $s = $statusMap[$report->status] ?? ['label' => $report->status, 'class' => 'b-published'];

                $statusIcons = [
                    'published'   => '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
                    'in_progress' => '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93a10 10 0 0 1 0 14.14M4.93 4.93a10 10 0 0 0 0 14.14"/></svg>',
                    'resolved'    => '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
                ];
                $si = $statusIcons[$report->status] ?? '<svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>';
            @endphp

            <a href="{{ route('reports.show', $report->id) }}" class="report-card">
                @if($report->image)
                <img src="{{ asset('storage/'.$report->image) }}"
                     alt="{{ $report->title }}"
                     class="rc-img"
                     style="height:176px;object-fit:cover;">
                @endif

                <div class="rc-body">
                    <div class="rc-meta">
                        <span class="rc-badge {{ $s['class'] }}" style="display:inline-flex;align-items:center;gap:4px;">
                            {!! $si !!} {{ $s['label'] }}
                        </span>
                        {{-- Upvote Button --}}
                        @auth
                        <div x-data="upvote({{ $report->id }}, {{ $report->upvotes_count }}, {{ Auth::user()->upvotedReports->contains($report->id) ? 'true' : 'false' }})">
                            <button
                                type="button"
                                @click.prevent="toggle()"
                                :class="['upvote-btn', upvoted ? 'upvoted' : '']"
                                style="display:inline-flex;align-items:center;gap:5px;"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/><path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
                                <span x-text="count">{{ $report->upvotes_count }}</span>
                            </button>
                        </div>
                        @else
                        <button
                            type="button"
                            class="upvote-btn"
                            style="display:inline-flex;align-items:center;gap:5px;"
                            @click.prevent.stop="window.location.href='{{ route('login') }}'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/><path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/></svg>
                            {{ $report->upvotes_count }}
                        </button>
                        @endauth
                    </div>

                    <div class="rc-title">{{ $report->title }}</div>
                    <div class="rc-desc">{{ $report->description }}</div>

                    <div class="rc-footer">
                        <div class="rc-district" style="display:inline-flex;align-items:center;gap:4px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            {{ $report->district->name ?? '-' }}
                            <span style="color:var(--border);margin:0 4px">•</span>
                            <span class="mono" style="font-size:11px;">{{ $report->created_at->diffForHumans() }}</span>
                        </div>
                        <span class="rc-link" style="display:inline-flex;align-items:center;gap:4px;">
                            Detail
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div style="display:flex;justify-content:center;margin-top:32px;">
            {{ $reports->appends(request()->query())->links('vendor.pagination.simple-tailwind') }}
        </div>

    @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            </div>
            <div class="empty-state-title">Tidak Ada Laporan Ditemukan</div>
            <div class="empty-state-desc">Coba ubah kata kunci pencarian atau pilih kecamatan yang berbeda.</div>
            @if(request('search') || request('district_id') || request('sort_by') || request('status'))
                <a href="{{ route('feed') }}" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Reset Filter
                </a>
            @endif
        </div>
    @endif

</div>
</body>
</html>