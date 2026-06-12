<x-app-layout>
    <x-slot name="title">Admin Dashboard</x-slot>
    <x-slot name="breadcrumb">
        <span class="breadcrumb-current">Admin Dashboard</span>
    </x-slot>

    <div class="page-header" style="display:flex;align-items:flex-start;justify-content:space-between;">
        <div>
            <div class="page-title" style="display:flex;align-items:center;gap:8px;">
                <!-- Settings / gear icon -->
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
                Panel Kontrol Admin
            </div>
            <div class="page-desc">Selamat datang, <strong>{{ Auth::user()->name }}</strong></div>
        </div>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-primary" style="flex-shrink:0;display:flex;align-items:center;gap:6px;">
            Kelola Laporan
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>

    <!-- EXECUTIVE MATRIX -->
    <div class="stats-grid" style="margin-bottom:28px;">

        <!-- Total Aduan -->
        <div class="stat-card s-blue">
            <div class="stat-icon s-blue">
                <!-- Clipboard / report list -->
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                    <line x1="9" y1="12" x2="15" y2="12"/>
                    <line x1="9" y1="16" x2="13" y2="16"/>
                </svg>
            </div>
            <div class="stat-label">Total Aduan Kota</div>
            <div class="stat-value">{{ number_format($totalReports) }}</div>
            <div class="stat-sub">Semua laporan dari warga</div>
        </div>

        <!-- Antrian Pending -->
        <div class="stat-card s-amber">
            <div class="stat-icon s-amber">
                <!-- Clock / pending -->
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            <div class="stat-label">Antrian Pending</div>
            <div class="stat-value">{{ number_format($pendingReports) }}</div>
            <div class="stat-sub">
                @if($pendingReports > 0)
                    <span style="color:var(--warning);font-weight:600;">Perlu segera diverifikasi</span>
                @else
                    <span style="display:inline-flex;align-items:center;gap:4px;">
                        Tidak ada antrian
                        <!-- Party / celebrate checkmark -->
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </span>
                @endif
            </div>
        </div>

        <!-- Sedang Ditangani -->
        <div class="stat-card s-orange">
            <div class="stat-icon s-orange">
                <!-- Wrench / in-progress -->
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                </svg>
            </div>
            <div class="stat-label">Sedang Ditangani</div>
            <div class="stat-value">{{ number_format($inProgressReports) }}</div>
            <div class="stat-sub">Dalam proses instansi</div>
        </div>

        <!-- Berhasil Diselesaikan -->
        <div class="stat-card s-green">
            <div class="stat-icon s-green">
                <!-- Circle check -->
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>
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
            <div style="font-size:14px;font-weight:600;color:var(--text);margin-bottom:14px;display:flex;align-items:center;gap:7px;">
                <!-- Bar chart icon -->
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"/>
                    <line x1="12" y1="20" x2="12" y2="4"/>
                    <line x1="6"  y1="20" x2="6"  y2="14"/>
                </svg>
                Distribusi Status Laporan
            </div>
            <div style="display:flex;height:12px;border-radius:6px;overflow:hidden;gap:2px;margin-bottom:12px;">
                @php
                    $publishedCount = \App\Models\Report::where('status','published')->count();
                    $rejectedCount  = \App\Models\Report::where('status','rejected')->count();
                    $total          = $totalReports ?: 1;
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
                <div class="card-title" style="display:flex;align-items:center;gap:7px;">
                    <!-- Trending up -->
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/>
                        <polyline points="17 6 23 6 23 12"/>
                    </svg>
                    Tren Laporan 6 Bulan Terakhir
                </div>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline btn-sm" style="background:var(--surface);color:var(--primary);">Lihat Semua</a>
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
                        <div style="flex:1;display:flex;align-items:center;gap:12px;">
                            <div style="flex:1;height:12px;background:var(--bg);border-radius:6px;overflow:hidden;">
                                <div style="height:100%;width:{{ $pct }}%;background:linear-gradient(90deg,var(--primary),var(--primary-light));border-radius:6px;transition:width .3s;"></div>
                            </div>
                            <span style="font-size:13px;font-weight:600;color:var(--text);font-family:'IBM Plex Mono',monospace;min-width:20px;text-align:right;">{{ $trend->total }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state" style="padding:40px 20px;">
                    <div class="empty-state-icon">
                        <!-- Bar chart empty state -->
                        <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" style="opacity:.4;">
                            <line x1="18" y1="20" x2="18" y2="10"/>
                            <line x1="12" y1="20" x2="12" y2="4"/>
                            <line x1="6"  y1="20" x2="6"  y2="14"/>
                        </svg>
                    </div>
                    <div class="empty-state-title">Belum Ada Data Tren</div>
                </div>
                @endif
            </div>
        </div>

        <!-- PRIORITY DISTRICTS -->
        <div class="card">
            <div class="card-header">
                <div class="card-title" style="display:flex;align-items:center;gap:7px;">
                    <!-- Map pin -->
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    Prioritas Wilayah
                </div>
                <a href="{{ route('admin.districts.index') }}" class="btn btn-outline btn-sm" style="background:var(--surface);color:var(--primary);">Kelola</a>
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
                    <div class="empty-state-icon">
                        <!-- Map empty state -->
                        <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" style="opacity:.4;">
                            <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/>
                            <line x1="8" y1="2" x2="8" y2="18"/>
                            <line x1="16" y1="6" x2="16" y2="22"/>
                        </svg>
                    </div>
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
            <div style="width:48px;height:48px;background:#F59E0B;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <!-- Inbox / tray icon -->
                <svg width="24" height="24" fill="none" stroke="#fff" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/>
                    <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>
                </svg>
            </div>
            <div>
                <div style="font-size:16px;font-weight:700;color:#92400E;">{{ $pendingReports }} Laporan Menunggu Verifikasi</div>
                <div style="font-size:13.5px;color:#B45309;margin-top:2px;">Segera tinjau laporan masuk agar tidak menumpuk di inbox.</div>
            </div>
        </div>
        <a href="{{ route('admin.reports.index', ['status'=>'pending']) }}" class="btn btn-accent" style="flex-shrink:0;display:inline-flex;align-items:center;gap:6px;">
            Tinjau Sekarang
            <!-- Arrow right -->
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="5" y1="12" x2="19" y2="12"/>
                <polyline points="12 5 19 12 12 19"/>
            </svg>
        </a>
    </div>
    @endif
</x-app-layout>