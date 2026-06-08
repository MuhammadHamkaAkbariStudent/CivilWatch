<x-app-layout>
    <x-slot name="title">Manajemen Laporan</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">Dashboard</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Manajemen Laporan</span>
    </x-slot>

    <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;">
        <div>
            <div class="page-title">📋 Manajemen Laporan</div>
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
            ['value' => '',            'label' => 'Semua',     'icon' => '📋'],
            ['value' => 'pending',     'label' => 'Menunggu',   'icon' => '⏳'],
            ['value' => 'published',   'label' => 'Diterima',  'icon' => '🔵'],
            ['value' => 'in_progress', 'label' => 'Diproses',  'icon' => '⚙️'],
            ['value' => 'resolved',    'label' => 'Selesai',   'icon' => '✅'],
            ['value' => 'rejected',    'label' => 'Ditolak',   'icon' => '🚫'],
        ];
        $activeStatus = request('status', '');
    @endphp

    <div style="display:flex;gap:4px;margin-bottom:20px;background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:4px;overflow-x:auto;">
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
            {{ $tab['icon'] }} {{ $tab['label'] }}
        </a>
        @endforeach
    </div>

    <!-- FILTER BAR -->
    <form method="GET" action="{{ route('admin.reports.index') }}">
        @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
        <div class="filter-bar">
            <div class="search-box">
                <span class="search-icon" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-light);">🔍</span>
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="Cari judul laporan..."
                    value="{{ request('search') }}"
                    style="width:100%;padding:9px 14px 9px 38px;border:1.5px solid var(--border);border-radius:8px;font-size:13.5px;font-family:'Sora',sans-serif;color:var(--text);background:var(--surface);outline:none;"
                >
            </div>
            <select name="district_id" style="padding:9px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13.5px;font-family:'Sora',sans-serif;color:var(--text);background:var(--surface);outline:none;min-width:170px;cursor:pointer;">
                <option value="">Semua Kecamatan</option>
                @foreach($districts as $d)
                    <option value="{{ $d->id }}" {{ request('district_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                @endforeach
            </select>
            <select name="sort_by" style="padding:9px 14px;border:1.5px solid var(--border);border-radius:8px;font-size:13.5px;font-family:'Sora',sans-serif;color:var(--text);background:var(--surface);outline:none;min-width:170px;cursor:pointer;">
                <option value="latest" {{ request('sort_by') !== 'upvotes' ? 'selected' : '' }}>Terbaru</option>
                <option value="upvotes" {{ request('sort_by') === 'upvotes' ? 'selected' : '' }}>👍 Upvote Terbanyak</option>
            </select>
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
                                <div style="width:44px;height:44px;border-radius:8px;overflow:hidden;flex-shrink:0;background:linear-gradient(135deg,#EFF6FF,#BFDBFE);display:flex;align-items:center;justify-content:center;font-size:18px;border:1px solid var(--border);">
                                    @if($report->image)
                                        <img src="{{ asset('storage/'.$report->image) }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                                    @else
                                        @php $emojis=['🚧','💡','🌳','🚰','🏗️','⚠️']; echo $emojis[$report->id % 6]; @endphp
                                    @endif
                                </div>
                                <div style="min-width:0;">
                                    <div style="font-size:13.5px;font-weight:600;color:var(--text);line-height:1.3;margin-bottom:3px;">
                                        {{ Str::limit($report->title, 50) }}
                                    </div>
                                    <div style="font-size:12px;color:var(--text-light);">
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
                            <span style="font-size:13px;color:var(--text-muted);display:inline-flex;align-items:center;gap:4px;">
                                 {{ $report->district->name ?? '-' }}
                            </span>
                        </td>
                        <td style="text-align:center;">
                            @if($report->upvotes_count > 0)
                            <span style="display:inline-flex;align-items:center;gap:4px;font-size:13px;font-weight:600;color:var(--accent);font-family:'IBM Plex Mono',monospace;">
                                👍 {{ $report->upvotes_count }}
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
                @if(request('status') === 'pending') ⏳
                @elseif(request('status') === 'resolved') ✅
                @else 📋
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