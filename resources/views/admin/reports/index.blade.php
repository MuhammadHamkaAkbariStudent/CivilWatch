<x-app-layout>
    <x-slot name="title">Manajemen Laporan</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">Dashboard</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Manajemen Laporan</span>
    </x-slot>

    <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">
        <div>
            <div class="page-title" style="display:flex;align-items:center;gap:8px;">
                <!-- Clipboard list -->
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                    <line x1="9" y1="12" x2="15" y2="12"/>
                    <line x1="9" y1="16" x2="13" y2="16"/>
                </svg>
                Manajemen Laporan
            </div>
            <div class="page-desc">Verifikasi, pantau, dan kelola seluruh laporan pengaduan dari warga</div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
            <span style="font-size:13px;color:var(--text-muted);">Total:</span>
            <span style="font-size:14px;font-weight:700;color:var(--text);font-family:'IBM Plex Mono',monospace;">{{ $reports->total() }}</span>
        </div>
    </div>

    <!-- STATUS TABS -->
    @php
        $statusTabs = [
            [
                'value' => '',
                'label' => 'Semua',
                'icon'  => '<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><line x1="9" y1="12" x2="15" y2="12"/><line x1="9" y1="16" x2="13" y2="16"/></svg>',
            ],
            [
                'value' => 'pending',
                'label' => 'Menunggu',
                'icon'  => '<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
            ],
            [
                'value' => 'published',
                'label' => 'Diterima',
                'icon'  => '<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="9 11 12 14 22 4"/></svg>',
            ],
            [
                'value' => 'in_progress',
                'label' => 'Diproses',
                'icon'  => '<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>',
            ],
            [
                'value' => 'resolved',
                'label' => 'Selesai',
                'icon'  => '<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
            ],
            [
                'value' => 'rejected',
                'label' => 'Ditolak',
                'icon'  => '<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
            ],
        ];
        $activeStatus = request('status', '');
    @endphp

    <div style="display:flex;justify-content:center;gap:75px;margin-bottom:20px;background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:4px;overflow-x:auto;">
        @foreach($statusTabs as $tab)
        <a
            href="{{ route('admin.reports.index', array_merge(request()->except(['status','page']), ['status' => $tab['value']])) }}"
            style="
                display:inline-flex;align-items:center;gap:6px;
                padding:8px 16px;border-radius:7px;
                font-size:13px;font-weight:{{ $activeStatus === $tab['value'] ? '600' : '500' }};
                white-space:nowrap;text-decoration:none;transition:all .15s;
                {{ $activeStatus === $tab['value']
                    ? 'background:var(--primary);color:#fff;'
                    : 'color:var(--text-muted);background:transparent;' }}
            "
            onmouseover="{{ $activeStatus !== $tab['value'] ? 'this.style.background=\'var(--bg)\';this.style.color=\'var(--text)\'' : '' }}"
            onmouseout="{{ $activeStatus !== $tab['value'] ? 'this.style.background=\'transparent\';this.style.color=\'var(--text-muted)\'' : '' }}"
        >
            {!! $tab['icon'] !!} {{ $tab['label'] }}
        </a>
        @endforeach
    </div>

    <!-- FILTER BAR -->
    <form method="GET" action="{{ route('admin.reports.index') }}">
        @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        <div class="filter-bar">
            <div class="search-box" style="position:relative;">
                <span class="search-icon" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-light);display:flex;align-items:center;pointer-events:none;">
                    <!-- Search magnifier -->
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </span>
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="Cari judul laporan..."
                    value="{{ request('search') }}"
                    style="width:100%;padding:9px 14px 9px 38px;border:1.5px solid var(--border);border-radius:8px;font-size:13.5px;font-family:'Sora',sans-serif;color:var(--text);background:var(--surface);outline:none;"
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
                        { value: 'upvotes', label: 'Upvote Terbanyak' }],
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
            <button type="submit" class="btn btn-primary" style="padding:9px 18px;font-size:13.5px;">Terapkan</button>
            @if(request('search') || request('district_id') || request('sort_by'))
                <a href="{{ route('admin.reports.index', request('status') ? ['status'=>request('status')] : []) }}" class="btn btn-outline" style="padding:9px 14px;font-size:13.5px;">Reset</a>
            @endif
        </div>
    </form>

    <!-- REPORTS TABLE -->
    <div class="card">
        @if($reports->count() > 0)
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:36px;">#</th>
                        <th style="min-width:280px;">Laporan</th>
                        <th>Pelapor</th>
                        <th>Kecamatan</th>
                        <th style="text-align:center;">Upvote</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th style="text-align:center;width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $i => $report)
                    <tr>
                        <td style="font-size:12px;font-family:'IBM Plex Mono',monospace;color:var(--text-light);">
                            {{ $reports->firstItem() + $i }}
                        </td>
                        <td>
                            <div style="display:flex;align-items:flex-start;gap:12px;">
                                <!-- Thumbnail -->
                                <div style="width:44px;height:44px;border-radius:8px;overflow:hidden;flex-shrink:0;background:linear-gradient(135deg,#EFF6FF,#BFDBFE);display:flex;align-items:center;justify-content:center;border:1px solid var(--border);">
                                    @if($report->image)
                                        <img src="{{ asset('storage/'.$report->image) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                                    @else
                                        @php
                                            $thumbIcons = [
                                                '<svg width="20" height="20" fill="none" stroke="#60A5FA" viewBox="0 0 24 24" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="10" rx="2"/><path d="M12 7v10M2 12h20"/></svg>',
                                                '<svg width="20" height="20" fill="none" stroke="#60A5FA" viewBox="0 0 24 24" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><line x1="9" y1="18" x2="15" y2="18"/><line x1="10" y1="22" x2="14" y2="22"/><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5A4.61 4.61 0 0 1 8.91 14"/></svg>',
                                                '<svg width="20" height="20" fill="none" stroke="#60A5FA" viewBox="0 0 24 24" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M17 8C8 10 5.9 16.17 3.82 22h3.28"/><path d="M10.66 10C10.9 18 11.5 21.33 12 22"/><path d="M17.54 18.4A12.5 12.5 0 0 0 21 12c0-5.52-4-10-9-10C7 2 3 6.48 3 12"/></svg>',
                                                '<svg width="20" height="20" fill="none" stroke="#60A5FA" viewBox="0 0 24 24" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C6 2 2 7 2 12s4 10 10 10 10-4.5 10-10S18 2 12 2z"/><path d="M12 8v8M8 12h8"/></svg>',
                                                '<svg width="20" height="20" fill="none" stroke="#60A5FA" viewBox="0 0 24 24" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>',
                                                '<svg width="20" height="20" fill="none" stroke="#60A5FA" viewBox="0 0 24 24" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
                                            ];
                                        @endphp
                                        {!! $thumbIcons[$report->id % 6] !!}
                                    @endif
                                </div>
                                <div style="min-width:0;">
                                    <div style="font-size:13.5px;font-weight:600;color:var(--text);line-height:1.3;margin-bottom:3px;">
                                        {{ Str::limit($report->title, 50) }}
                                    </div>
                                    <div style="font-size:12px;color:var(--text-light);overflow-wrap:break-word;word-break:break-word;">
                                        {{ Str::limit($report->description, 55) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:7px;">
                                <div style="width:26px;height:26px;border-radius:6px;background:var(--primary);color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    {{ strtoupper(substr($report->user->name ?? 'A', 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-size:13px;font-weight:500;color:var(--text);">{{ $report->user->name ?? '-' }}</div>
                                    <div style="font-size:11.5px;color:var(--text-light);">{{ $report->user->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="font-size:13px;color:var(--text-muted);display:inline-flex;align-items:center;gap:5px;">
                                <!-- Map pin -->
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;">
                                    <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                {{ $report->district->name ?? '-' }}
                            </span>
                        </td>
                        <td style="text-align:center;">
                            @if($report->upvotes_count > 0)
                            <span style="display:inline-flex;align-items:center;gap:4px;font-size:13px;font-weight:600;color:var(--accent);font-family:'IBM Plex Mono',monospace;">
                                <!-- Thumbs up -->
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3H14z"/>
                                    <path d="M7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"/>
                                </svg>
                                {{ $report->upvotes_count }}
                            </span>
                            @else
                            <span style="font-size:12px;color:var(--text-light);">—</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $badges = [
                                    'pending'     => ['label'=>'Menunggu',  'class'=>'badge-pending'],
                                    'published'   => ['label'=>'Diterima',  'class'=>'badge-published'],
                                    'in_progress' => ['label'=>'Diproses',  'class'=>'badge-in-progress'],
                                    'resolved'    => ['label'=>'Selesai',   'class'=>'badge-resolved'],
                                    'rejected'    => ['label'=>'Ditolak',   'class'=>'badge-rejected'],
                                ];
                                $b = $badges[$report->status] ?? ['label'=>$report->status,'class'=>'badge-rejected'];
                            @endphp
                            <span class="badge {{ $b['class'] }}">
                                <span class="badge-dot" style="background:currentColor;opacity:.5"></span>
                                {{ $b['label'] }}
                            </span>
                        </td>
                        <td>
                            <div style="font-size:12.5px;font-family:'IBM Plex Mono',monospace;color:var(--text-muted);">
                                {{ $report->created_at->format('d M Y') }}
                            </div>
                            <div style="font-size:11px;color:var(--text-light);">
                                {{ $report->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td style="text-align:center;">
                            <a
                                href="{{ route('admin.reports.show', $report->id) }}"
                                class="btn btn-primary btn-sm btn-icon"
                                title="Periksa Laporan"
                                style="display:inline-flex;"
                            >
                                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="1.5"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="1.5"/></svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <div style="padding:16px 24px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
            <span style="font-size:13px;color:var(--text-muted);">
                Menampilkan <strong style="color:var(--text);">{{ $reports->firstItem() }}–{{ $reports->lastItem() }}</strong> dari <strong style="color:var(--text);">{{ $reports->total() }}</strong> laporan
            </span>
            {{ $reports->appends(request()->query())->links('vendor.pagination.simple-tailwind') }}
        </div>

        @else
        <div class="empty-state">
            <div class="empty-state-icon">
                @if(request('status') === 'pending')
                    <!-- Clock -->
                    <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" style="opacity:.4;">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                @elseif(request('status') === 'resolved')
                    <!-- Circle check -->
                    <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" style="opacity:.4;">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                @else
                    <!-- Clipboard -->
                    <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" style="opacity:.4;">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                        <rect x="9" y="3" width="6" height="4" rx="1"/>
                        <line x1="9" y1="12" x2="15" y2="12"/>
                        <line x1="9" y1="16" x2="13" y2="16"/>
                    </svg>
                @endif
            </div>
            <div class="empty-state-title">
                @if(request('search'))
                    Tidak Ada Laporan Cocok dengan "{{ request('search') }}"
                @elseif(request('status'))
                    Tidak Ada Laporan Berstatus "{{ ucfirst(str_replace('_',' ',request('status'))) }}"
                @else
                    Belum Ada Laporan Masuk
                @endif
            </div>
            <div class="empty-state-desc">
                @if(request('search') || request('district_id'))
                    Coba ubah filter pencarian untuk menemukan laporan.
                @else
                    Laporan dari warga akan muncul di sini secara otomatis.
                @endif
            </div>
            @if(request('search') || request('district_id') || request('status'))
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline">Reset Filter</a>
            @endif
        </div>
        @endif
    </div>
</x-app-layout>