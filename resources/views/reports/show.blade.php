<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CivilWatch — {{ $report->title }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:300,400,500,600,700,800|ibm-plex-mono:400,500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--primary:#1E3A8A;--primary-dark:#162d6e;--accent:#F97316;--bg:#F8FAFC;--surface:#FFFFFF;--text:#334155;--text-muted:#64748B;--text-light:#94A3B8;--border:#E2E8F0;--success:#10B981;--warning:#F59E0B;--danger:#EF4444;}
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Sora',sans-serif;background:var(--bg);color:var(--text);}
        .top-nav{background:var(--surface);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:50;}
        .top-nav-inner{max-width:1100px;margin:0 auto;padding:0 32px;height:64px;display:flex;align-items:center;justify-content:space-between;}
        .nav-brand{display:flex;align-items:center;gap:10px;text-decoration:none;}
        .nav-brand-icon{width:36px;height:36px;background:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:17px;}
        .nav-brand-text{font-size:17px;font-weight:700;color:var(--primary);}
        .back-link{display:inline-flex;align-items:center;gap:6px;font-size:13.5px;font-weight:500;color:var(--text-muted);text-decoration:none;padding:7px 12px;border-radius:7px;transition:all .15s;}
        .back-link:hover{background:var(--bg);color:var(--primary);}
        /* LAYOUT */
        .detail-wrap{max-width:1100px;margin:0 auto;padding:32px;display:grid;grid-template-columns:1fr 340px;gap:32px;align-items:start;}
        /* MAIN */
        .report-main{display:flex;flex-direction:column;gap:20px;}
        .report-img{width:100%;border-radius:12px;object-fit:cover;max-height:420px;border:1px solid var(--border);}
        .report-img-placeholder{width:100%;height:300px;border-radius:12px;background:linear-gradient(135deg,#EFF6FF,#BFDBFE);display:flex;align-items:center;justify-content:center;font-size:80px;border:1px solid var(--border);}
        .card{background:var(--surface);border:1px solid var(--border);border-radius:12px;}
        .card-body{padding:24px;}
        .report-title{font-size:24px;font-weight:700;color:var(--text);letter-spacing:-.3px;margin-bottom:14px;line-height:1.3;}
        .report-meta-row{display:flex;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:16px;}
        .meta-item{display:flex;align-items:center;gap:5px;font-size:13px;color:var(--text-muted);}
        .meta-item strong{color:var(--text);font-weight:600;}
        .report-desc{font-size:15px;color:var(--text);line-height:1.8;}
        /* STATUS BADGE */
        .badge{display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;padding:4px 12px;border-radius:20px;}
        .b-published{background:#DBEAFE;color:#1E40AF;border:1px solid #93C5FD;}
        .b-in_progress{background:#FEE2E2;color:#991B1B;border:1px solid #FCA5A5;}
        .b-resolved{background:#D1FAE5;color:#065F46;border:1px solid #6EE7B7;}
        .b-pending{background:#FEF3C7;color:#92400E;border:1px solid #FCD34D;}
        /* TIMELINE */
        .timeline-wrap{position:relative;padding-left:28px;}
        .timeline-wrap::before{content:'';position:absolute;left:7px;top:8px;bottom:8px;width:2px;background:var(--border);}
        .tl-item{position:relative;margin-bottom:24px;}
        .tl-item:last-child{margin-bottom:0;}
        .tl-dot{position:absolute;left:-25px;top:3px;width:16px;height:16px;border-radius:50%;border:2px solid var(--surface);box-shadow:0 0 0 1px var(--border);}
        .tl-dot.first{background:var(--primary);}
        .tl-dot.middle{background:var(--warning);}
        .tl-dot.last{background:var(--success);}
        .tl-date{font-size:11px;color:var(--text-light);font-family:'IBM Plex Mono',monospace;margin-bottom:5px;}
        .tl-content{background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:12px 14px;}
        .tl-note{font-size:13.5px;color:var(--text);line-height:1.6;}
        /* SIDEBAR */
        .sidebar-card{display:flex;flex-direction:column;gap:16px;}
        .info-row{display:flex;flex-direction:column;gap:4px;padding-bottom:14px;border-bottom:1px solid var(--border);}
        .info-row:last-child{border-bottom:none;padding-bottom:0;}
        .info-label{font-size:11px;font-weight:600;color:var(--text-light);text-transform:uppercase;letter-spacing:.5px;}
        .info-value{font-size:14px;color:var(--text);font-weight:500;}
        /* UPVOTE */
        .upvote-hero{text-align:center;padding:24px;background:var(--bg);border:2px dashed var(--border);border-radius:12px;}
        .upvote-count{font-size:40px;font-weight:700;color:var(--text);font-family:'IBM Plex Mono',monospace;line-height:1;}
        .upvote-label{font-size:13px;color:var(--text-muted);margin-top:4px;margin-bottom:16px;}
        .upvote-btn-lg{width:100%;padding:12px;border-radius:9px;font-size:14px;font-weight:600;font-family:'Sora',sans-serif;cursor:pointer;border:1.5px solid;transition:all .15s;}
        .upvote-btn-lg:not(.upvoted){background:var(--surface);color:var(--text-muted);border-color:var(--border);}
        .upvote-btn-lg:not(.upvoted):hover{background:#FFF7ED;border-color:var(--accent);color:var(--accent);}
        .upvote-btn-lg.upvoted{background:#FFF7ED;border-color:var(--accent);color:var(--accent);}
        /* SECTION TITLE */
        .sec-title{font-size:15px;font-weight:600;color:var(--text);margin-bottom:16px;display:flex;align-items:center;gap:8px;}
    </style>
</head>
<body>
    <nav class="top-nav">
        <div class="top-nav-inner">
            <a href="{{ route('home') }}" class="nav-brand">
                <span class="nav-brand-text">CivilWatch</span>
            </a>
            <a href="{{ route('feed') }}" class="back-link">← Kembali ke Feed</a>
        </div>
    </nav>

    <div class="detail-wrap">
        <!-- MAIN CONTENT -->
        <div class="report-main">
            <!-- IMAGE -->
            @if($report->image)
                <img src="{{ asset('storage/'.$report->image) }}" alt="{{ $report->title }}" class="report-img">
            @else
                <div class="report-img-placeholder">🚧</div>
            @endif

            <!-- REPORT INFO -->
            <div class="card">
                <div class="card-body">
                    <div class="report-meta-row">
                        @php
                            $statusMap = ['published'=>['label'=>'Diterima','class'=>'b-published','icon'=>'🔵'],'in_progress'=>['label'=>'Sedang Diproses','class'=>'b-in_progress','icon'=>'⚙️'],'resolved'=>['label'=>'Selesai','class'=>'b-resolved','icon'=>'✅'],'pending'=>['label'=>'Menunggu','class'=>'b-pending','icon'=>'⏳']];
                            $s = $statusMap[$report->status] ?? ['label'=>$report->status,'class'=>'b-published','icon'=>'•'];
                        @endphp
                    </div>
                    <div class="report-title">{{ $report->title }}</div>
                    <div class="report-desc">{{ $report->description }}</div>
                </div>
            </div>

            <!-- PROGRESS TIMELINE -->
            <div class="card">
                <div class="card-body">
                    <div class="sec-title">📋 Riwayat Penanganan</div>
                    @if($report->progressUpdates->count() > 0)
                    <div class="timeline-wrap">
                        <!-- Initial submitted event -->
                        <div class="tl-item">
                            <div class="tl-dot first"></div>
                            <div class="tl-date">{{ $report->created_at->format('d M Y, H:i') }}</div>
                            <div class="tl-content">
                                <div class="tl-note">✍️ Laporan diajukan oleh warga dan menunggu verifikasi admin.</div>
                            </div>
                        </div>
                        @foreach($report->progressUpdates as $i => $update)
                        <div class="tl-item">
                            <div class="tl-dot {{ $loop->last ? 'last' : 'middle' }}"></div>
                            <div class="tl-date">{{ $update->created_at->format('d M Y, H:i') }}</div>
                            <div class="tl-content">
                                <div class="tl-note">{{ $update->note }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div style="text-align:center;padding:32px 20px;color:var(--text-muted);">
                        <div style="font-size:32px;margin-bottom:10px;opacity:.5">⏳</div>
                        <div style="font-size:14px;">Laporan sedang dalam antrian verifikasi. Belum ada catatan progres.</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- SIDEBAR -->
        <div class="sidebar-card">
            <!-- UPVOTE -->
            <div class="card">
                <div class="card-body">
                    @auth
                    <div
                        x-data="{
                            count: {{ $report->upvotes_count }},
                            upvoted: {{ Auth::user()->upvotedReports->contains($report->id) ? 'true' : 'false' }},
                            async toggle() {
                                const r = await fetch('{{ route('reports.upvote', $report->id) }}', {
                                    method:'POST',
                                    headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json','Content-Type':'application/json'}
                                });
                                const d = await r.json();
                                if(d.success){this.count=d.count;this.upvoted=d.upvoted;}
                            }
                        }"
                    >
                        <div class="upvote-hero">
                            <div class="upvote-count" x-text="count">{{ $report->upvotes_count }}</div>
                            <div class="upvote-label">Total Dukungan Warga</div>
                            <button @click="toggle" :class="['upvote-btn-lg', upvoted ? 'upvoted' : '']">
                                <span x-text="upvoted ? '👍 Kamu Mendukung Ini' : '👍 Dukung Laporan Ini'">
                                    {{ Auth::user()->upvotedReports->contains($report->id) ? '👍 Kamu Mendukung Ini' : '👍 Dukung Laporan Ini' }}
                                </span>
                            </button>
                        </div>
                    </div>
                    @else
                    <div class="upvote-hero">
                        <div class="upvote-count">{{ $report->upvotes_count }}</div>
                        <div class="upvote-label">Total Dukungan Warga</div>
                        <a href="{{ route('login') }}" class="upvote-btn-lg" style="display:block;text-align:center;text-decoration:none;border:1.5px solid var(--border);border-radius:9px;padding:12px;font-size:14px;font-weight:600;color:var(--text-muted);">
                            Masuk untuk Mendukung
                        </a>
                    </div>
                    @endauth
                </div>
            </div>

            <!-- DETAIL INFO -->
            <div class="card">
                <div class="card-body">
                    <div class="sec-title">Informasi Laporan</div>
                    <div style="display:flex;flex-direction:column;gap:14px;">
                        <div class="info-row">
                            <div class="info-label">Wilayah / Kecamatan</div>
                            <div class="info-value">{{ $report->district->name ?? '-' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Pelapor</div>
                            <div class="info-value">{{ $report->user->name ?? 'Anonim' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Tanggal Laporan</div>
                            <div class="info-value">{{ $report->created_at->format('d M Y') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Terakhir Diperbarui</div>
                            <div class="info-value">{{ $report->updated_at->diffForHumans() }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Status Saat Ini</div>
                            <div><span class="badge {{ $s['class'] }}">{{ $s['icon'] }} {{ $s['label'] }}</span></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Total Catatan Progres</div>
                            <div class="info-value">{{ $report->progressUpdates->count() }} catatan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>