<x-app-layout>
    <x-slot name="title">Admin Dashboard</x-slot>
    <x-slot name="breadcrumb">
        <span class="breadcrumb-current">Admin Dashboard</span>
    </x-slot>

    <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;">
        <div>
            <div class="page-title">Panel Kontrol Admin ⚙️</div>
            <div class="page-desc">Selamat datang, <strong>{{ Auth::user()->name }}</strong> — Pantau statistik dan prioritas penanganan kota</div>
        </div>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-primary" style="flex-shrink:0;">
            Kelola Laporan
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>

    <!-- EXECUTIVE MATRIX -->
    <div class="stats-grid" style="margin-bottom:28px;">
        <div class="stat-card s-blue">
            <div class="stat-icon s-blue">📋</div>
            <div class="stat-label">Total Aduan Kota</div>
            <div class="stat-value">{{ number_format($totalReports) }}</div>
            <div class="stat-sub">Semua laporan dari warga</div>
        </div>
        <div class="stat-card s-amber">
            <div class="stat-icon s-amber">⏳</div>
            <div class="stat-label">Antrian Pending</div>
            <div class="stat-value">{{ number_format($pendingReports) }}</div>
            <div class="stat-sub">
                @if($pendingReports > 0)
                    <span style="color:var(--warning);font-weight:600;">Perlu segera diverifikasi</span>
                @else
                    Tidak ada antrian 🎉
                @endif
            </div>
        </div>
        <div class="stat-card s-orange">
            <div class="stat-icon s-orange">⚙️</div>
            <div class="stat-label">Sedang Ditangani</div>
            <div class="stat-value">{{ number_format($inProgressReports) }}</div>
            <div class="stat-sub">Dalam proses instansi</div>
        </div>
        <div class="stat-card s-green">
            <div class="stat-icon s-green">✅</div>
            <div class="stat-label">Berhasil Diselesaikan</div>
            <div class="stat-value">{{ number_format($resolvedReports) }}</div>
            <div class="stat-sub">
                @if($totalReports > 0)
                    {{ round(($resolvedReports / $totalReports) * 100) }}% tingkat penyelesaian
                @else
                    Belum ada data
                @endif
            </div>
        </div>
    </div>

    <!-- PROGRESS BAR OVERVIEW -->
    @if($totalReports > 0)
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body" style="padding:20px 24px;">
            <div style="font-size:14px;font-weight:600;color:var(--text);margin-bottom:14px;">📊 Distribusi Status Laporan</div>
            <div style="display:flex;height:12px;border-radius:6px;overflow:hidden;gap:2px;margin-bottom:12px;">
                @php
                    $publishedCount = \App\Models\Report::where('status','published')->count();
                    $rejectedCount = \App\Models\Report::where('status','rejected')->count();
                    $total = $totalReports ?: 1;
                @endphp
                <div style="width:{{ ($pendingReports/$total)*100 }}%;background:#F59E0B;" title="Pending"></div>
                <div style="width:{{ ($publishedCount/$total)*100 }}%;background:#3B82F6;" title="Published"></div>
                <div style="width:{{ ($inProgressReports/$total)*100 }}%;background:#EF4444;" title="In Progress"></div>
                <div style="width:{{ ($resolvedReports/$total)*100 }}%;background:#10B981;" title="Resolved"></div>
                <div style="width:{{ ($rejectedCount/$total)*100 }}%;background:#94A3B8;" title="Rejected"></div>
            </div>
            <div style="display:flex;gap:20px;flex-wrap:wrap;">
                @foreach([
                    ['#F59E0B','Menunggu',$pendingReports],
                    ['#3B82F6','Diterima',$publishedCount],
                    ['#EF4444','Diproses',$inProgressReports],
                    ['#10B981','Selesai',$resolvedReports],
                    ['#94A3B8','Ditolak',$rejectedCount],
                ] as [$color,$label,$count])
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:10px;height:10px;border-radius:2px;background:{{ $color }};flex-shrink:0;"></div>
                    <span style="font-size:12.5px;color:var(--text-muted);">{{ $label }}</span>
                    <span style="font-size:12.5px;font-weight:600;color:var(--text);font-family:'IBM Plex Mono',monospace;">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- TWO COLUMNS -->
    <div style="display:grid;grid-template-columns:1fr 380px;gap:20px;align-items:start;">

        <!-- MONTHLY TREND -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">📈 Tren Laporan 6 Bulan Terakhir</div>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline btn-sm">Lihat Semua</a>
            </div>
            <div class="card-body" style="padding:20px 24px;">
                @if($monthlyTrend->count() > 0)
                @php $maxTrend = $monthlyTrend->max('total') ?: 1; @endphp
                <div style="display:flex;flex-direction:column;gap:12px;">
                    @foreach($monthlyTrend->sortBy('month') as $trend)
                    @php
                        $pct = ($trend->total / $maxTrend) * 100;
                        [$y,$m] = explode('-', $trend->month);
                        $monthName = \Carbon\Carbon::createFromDate($y,$m,1)->translatedFormat('F Y');
                    @endphp
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:90px;font-size:12.5px;color:var(--text-muted);font-family:'IBM Plex Mono',monospace;flex-shrink:0;">{{ $monthName }}</div>
                        <div style="flex:1;height:28px;background:var(--bg);border-radius:6px;overflow:hidden;position:relative;">
                            <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,var(--primary),var(--primary-light));border-radius:6px;transition:width .3s;"></div>
                            <span style="position:absolute;right:10px;top:50%;transform:translateY(-50%);font-size:12px;font-weight:600;color:var(--text);font-family:'IBM Plex Mono',monospace;">{{ $trend->total }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state" style="padding:40px 20px;">
                    <div class="empty-state-icon">📊</div>
                    <div class="empty-state-title">Belum Ada Data Tren</div>
                </div>
                @endif
            </div>
        </div>

        <!-- PRIORITY DISTRICTS -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">🗺️ Prioritas Wilayah</div>
                <a href="{{ route('admin.districts.index') }}" class="btn btn-outline btn-sm">Kelola</a>
            </div>
            <div class="card-body" style="padding:20px 24px;">
                @if($priorityDistricts->count() > 0)
                @php $maxDistrict = $priorityDistricts->max('reports_count') ?: 1; @endphp
                <div style="display:flex;flex-direction:column;gap:14px;">
                    @foreach($priorityDistricts as $i => $district)
                    @php $pct = ($district->reports_count / $maxDistrict) * 100; @endphp
                    <div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="width:22px;height:22px;border-radius:6px;background:{{ $i===0?'var(--danger)':($i===1?'var(--warning)':($i===2?'var(--accent)':'var(--border)')) }};display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:{{ $i<3?'#fff':'var(--text-muted)' }};">{{ $i+1 }}</div>
                                <span style="font-size:13.5px;font-weight:600;color:var(--text);">{{ $district->name }}</span>
                            </div>
                            <span style="font-size:13px;font-weight:600;color:var(--text-muted);font-family:'IBM Plex Mono',monospace;">{{ $district->reports_count }}</span>
                        </div>
                        <div style="height:6px;background:var(--bg);border-radius:3px;overflow:hidden;">
                            <div style="height:100%;width:{{ $pct }}%;background:{{ $i===0?'var(--danger)':($i===1?'var(--warning)':($i===2?'var(--accent)':'var(--primary)')) }};border-radius:3px;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state" style="padding:40px 20px;">
                    <div class="empty-state-icon">🗺️</div>
                    <div class="empty-state-title">Belum Ada Data Wilayah</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- PENDING INBOX ALERT -->
    @if($pendingReports > 0)
    <div style="margin-top:20px;background:linear-gradient(135deg,#FEF3C7,#FFF7ED);border:1.5px solid #FCD34D;border-radius:12px;padding:20px 24px;display:flex;align-items:center;justify-content:space-between;gap:16px;">
        <div style="display:flex;align-items:center;gap:14px;">
            <div style="width:48px;height:48px;background:#F59E0B;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">⏳</div>
            <div>
                <div style="font-size:16px;font-weight:700;color:#92400E;">{{ $pendingReports }} Laporan Menunggu Verifikasi</div>
                <div style="font-size:13.5px;color:#B45309;margin-top:2px;">Segera tinjau laporan masuk agar tidak menumpuk di inbox.</div>
            </div>
        </div>
        <a href="{{ route('admin.reports.index', ['status'=>'pending']) }}" class="btn btn-accent" style="flex-shrink:0;">
            Tinjau Sekarang →
        </a>
    </div>
    @endif
</x-app-layout>