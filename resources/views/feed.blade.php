<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CivilWatch — Public Feed</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=sora:300,400,500,600,700,800|ibm-plex-mono:400,500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary:#1E3A8A; --primary-dark:#162d6e; --accent:#F97316;
            --bg:#F8FAFC; --surface:#FFFFFF; --text:#334155;
            --text-muted:#64748B; --text-light:#94A3B8; --border:#E2E8F0;
            --success:#10B981; --warning:#F59E0B; --danger:#EF4444; --info:#3B82F6;
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Sora',sans-serif;background:var(--bg);color:var(--text);}
        .top-nav{background:var(--surface);border-bottom:1px solid var(--border);position:sticky;top:0;z-index:50;}
        .top-nav-inner{max-width:1280px;margin:0 auto;padding:0 32px;height:64px;display:flex;align-items:center;justify-content:space-between;}
        .nav-brand{display:flex;align-items:center;gap:10px;text-decoration:none;}
        .nav-brand-icon{width:36px;height:36px;background:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:17px;}
        .nav-brand-text{font-size:17px;font-weight:700;color:var(--primary);}
        .nav-links{display:flex;align-items:center;gap:8px;}
        .nav-link{font-size:13.5px;font-weight:500;color:var(--text-muted);text-decoration:none;padding:7px 14px;border-radius:7px;transition:all .15s;}
        .nav-link:hover,.nav-link.active{background:var(--bg);color:var(--primary);}
        .nav-auth{display:flex;align-items:center;gap:8px;}
        .nav-btn{padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;font-family:'Sora',sans-serif;text-decoration:none;cursor:pointer;border:1.5px solid;transition:all .15s;}
        .nav-btn-outline{background:transparent;color:var(--primary);border-color:var(--primary);}
        .nav-btn-outline:hover{background:var(--primary);color:#fff;}
        .nav-btn-primary{background:var(--primary);color:#fff;border-color:var(--primary);}
        .nav-btn-primary:hover{background:var(--primary-dark);}
        /* FEED LAYOUT */
        .feed-wrap{max-width:1280px;margin:0 auto;padding:32px;}
        .feed-header{margin-bottom:28px;}
        .feed-title{font-size:26px;font-weight:700;color:var(--text);letter-spacing:-.5px;}
        .feed-desc{font-size:14px;color:var(--text-muted);margin-top:4px;}
        /* FILTER */
        .filter-bar{display:flex;align-items:center;gap:12px;margin-bottom:28px;flex-wrap:wrap;}
        .search-wrap{position:relative;flex:1;min-width:240px;}
        .search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-light);pointer-events:none;}
        .search-input{width:100%;padding:10px 14px 10px 38px;border:1.5px solid var(--border);border-radius:9px;font-size:13.5px;font-family:'Sora',sans-serif;color:var(--text);background:var(--surface);outline:none;transition:border-color .15s;}
        .search-input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(30,58,138,.06);}
        .filter-select{padding:10px 14px;border:1.5px solid var(--border);border-radius:9px;font-size:13.5px;font-family:'Sora',sans-serif;color:var(--text);background:var(--surface);outline:none;min-width:160px;cursor:pointer;transition:border-color .15s;}
        .filter-select:focus{border-color:var(--primary);}
        .filter-btn{padding:10px 18px;border-radius:9px;font-size:13px;font-weight:600;font-family:'Sora',sans-serif;cursor:pointer;border:1.5px solid;transition:all .15s;}
        .filter-btn-primary{background:var(--primary);color:#fff;border-color:var(--primary);}
        .filter-btn-primary:hover{background:var(--primary-dark);}
        .filter-btn-reset{background:transparent;color:var(--text-muted);border-color:var(--border);}
        .filter-btn-reset:hover{border-color:var(--text-light);color:var(--text);}
        /* GRID */
        .report-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:20px;}
        .report-card{background:var(--surface);border:1px solid var(--border);border-radius:12px;overflow:hidden;display:flex;flex-direction:column;transition:transform .2s,box-shadow .2s;}
        .report-card:hover{transform:translateY(-3px);box-shadow:0 12px 32px rgba(30,58,138,.1);}
        .rc-img{width:100%;height:176px;object-fit:cover;background:linear-gradient(135deg,#EFF6FF,#BFDBFE);display:flex;align-items:center;justify-content:center;font-size:48px;}
        .rc-body{padding:16px;flex:1;display:flex;flex-direction:column;}
        .rc-meta{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;}
        .rc-badge{display:inline-flex;align-items:center;gap:4px;font-size:10.5px;font-weight:600;padding:3px 9px;border-radius:12px;}
        .rc-title{font-size:15px;font-weight:600;color:var(--text);margin-bottom:6px;line-height:1.4;}
        .rc-desc{font-size:13px;color:var(--text-muted);line-height:1.6;margin-bottom:14px;flex:1;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
        .rc-footer{display:flex;align-items:center;justify-content:space-between;padding-top:14px;border-top:1px solid var(--border);margin-top:auto;}
        .rc-district{font-size:12px;color:var(--text-light);display:flex;align-items:center;gap:4px;}
        .rc-link{font-size:13px;font-weight:600;color:var(--primary);text-decoration:none;}
        .rc-link:hover{text-decoration:underline;}
        /* UPVOTE */
        .upvote-btn{display:inline-flex;align-items:center;gap:5px;padding:5px 11px;border-radius:16px;font-size:12px;font-weight:600;border:1.5px solid var(--border);background:var(--surface);color:var(--text-muted);cursor:pointer;transition:all .15s;font-family:'Sora',sans-serif;}
        .upvote-btn:hover,.upvote-btn.upvoted{background:#FFF7ED;border-color:var(--accent);color:var(--accent);}
        /* STATUS BADGES */
        .b-published{background:#DBEAFE;color:#1E40AF;border:1px solid #93C5FD;}
        .b-in_progress{background:#FEE2E2;color:#991B1B;border:1px solid #FCA5A5;}
        .b-resolved{background:#D1FAE5;color:#065F46;border:1px solid #6EE7B7;}
        /* EMPTY */
        .empty{text-align:center;padding:80px 20px;}
        .empty-icon{font-size:56px;margin-bottom:16px;opacity:.5;}
        .empty-title{font-size:18px;font-weight:600;color:var(--text);margin-bottom:6px;}
        .empty-desc{font-size:14px;color:var(--text-muted);}
        /* PAGINATION */
        .pag-wrap{display:flex;justify-content:center;margin-top:32px;}
        .pag-inner{display:flex;align-items:center;gap:4px;}
        .pag-btn{display:inline-flex;align-items:center;justify-content:center;min-width:36px;height:36px;padding:0 10px;border-radius:7px;font-size:13px;font-weight:500;text-decoration:none;color:var(--text-muted);border:1px solid var(--border);background:var(--surface);transition:all .15s;cursor:pointer;}
        .pag-btn:hover{background:var(--bg);color:var(--primary);border-color:var(--primary);}
        .pag-btn.active{background:var(--primary);color:#fff;border-color:var(--primary);}
        /* COUNT */
        .result-count{font-size:13px;color:var(--text-muted);margin-bottom:20px;}
        .result-count strong{color:var(--text);}
    </style>
</head>
<body>
    <nav class="top-nav">
        <div class="top-nav-inner">
            <a href="{{ route('home') }}" class="nav-brand">
                <span class="nav-brand-text">CivilWatch</span>
            </a>
            <div class="nav-links">
                <a href="{{ route('home') }}" class="nav-link">Beranda</a>
                <a href="{{ route('feed') }}" class="nav-link active">Public Feed</a>
            </div>
            <div class="nav-auth">
                @auth
                    @if(Auth::user()->role === 'citizen')
                        <a href="{{ route('citizen.dashboard') }}" class="nav-btn nav-btn-outline">Dashboard</a>
                        <a href="{{ route('citizen.reports.create') }}" class="nav-btn nav-btn-primary">Buat Laporan</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="nav-btn nav-btn-outline">Masuk</a>
                    <a href="{{ route('register') }}" class="nav-btn nav-btn-primary">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="feed-wrap">
        <div class="feed-header">
            <div class="feed-title">📋 Public Feed Laporan</div>
            <div class="feed-desc">Laporan infrastruktur yang telah diverifikasi dan sedang/sudah ditangani</div>
        </div>

        <!-- FILTER BAR -->
        <form method="GET" action="{{ route('feed') }}" x-data="{ search: '{{ request('search') }}', district: '{{ request('district_id') }}' }">
            <div class="filter-bar">
                <div class="search-wrap">
                    <span class="search-icon">🔍</span>
                    <input
                        type="text"
                        name="search"
                        class="search-input"
                        placeholder="Cari judul laporan..."
                        value="{{ request('search') }}"
                    >
                </div>
                <select name="district_id" class="filter-select">
                    <option value="">Semua Kecamatan</option>
                    @foreach($districts as $d)
                        <option value="{{ $d->id }}" {{ request('district_id') == $d->id ? 'selected' : '' }}>
                            {{ $d->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="filter-btn filter-btn-primary">Terapkan Filter</button>
                @if(request('search') || request('district_id'))
                    <a href="{{ route('feed') }}" class="filter-btn filter-btn-reset">Reset</a>
                @endif
            </div>
        </form>

        <!-- RESULT COUNT -->
        <div class="result-count">
            Menampilkan <strong>{{ $reports->total() }}</strong> laporan
            @if(request('search')) yang cocok dengan "<strong>{{ request('search') }}</strong>" @endif
            @if(request('district_id') && $districts->firstWhere('id', request('district_id'))) di <strong>{{ $districts->firstWhere('id', request('district_id'))->name }}</strong> @endif
        </div>

        @if($reports->count() > 0)
        <div class="report-grid">
            @foreach($reports as $report)
            <div class="report-card">
                @if($report->image)
                    <img src="{{ asset('storage/'.$report->image) }}" alt="{{ $report->title }}" class="rc-img">
                @else
                    <div class="rc-img">
                        @php
                            $icons = ['🚧','💡','🌳','🚰','🏗️','⚠️','🛣️','🌉'];
                            echo $icons[$report->id % count($icons)];
                        @endphp
                    </div>
                @endif

                <div class="rc-body">
                    <div class="rc-meta">
                        @php
                            $statusMap = [
                                'published' => ['label'=>'Diterima','class'=>'b-published','icon'=>'🔵'],
                                'in_progress' => ['label'=>'Diproses','class'=>'b-in_progress','icon'=>'⚙️'],
                                'resolved' => ['label'=>'Selesai','class'=>'b-resolved','icon'=>'✅'],
                            ];
                            $s = $statusMap[$report->status] ?? ['label'=>$report->status,'class'=>'b-published','icon'=>'•'];
                        @endphp
                        <span class="rc-badge {{ $s['class'] }}">{{ $s['icon'] }} {{ $s['label'] }}</span>

                        <!-- UPVOTE BUTTON (AJAX) -->
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
                                    if(d.success){ this.count = d.count; this.upvoted = d.upvoted; }
                                }
                            }"
                        >
                            <button @click="toggle" :class="['upvote-btn', upvoted ? 'upvoted' : '']">
                                👍 <span x-text="count"></span>
                            </button>
                        </div>
                        @else
                        <a href="{{ route('login') }}" class="upvote-btn">👍 {{ $report->upvotes_count }}</a>
                        @endauth
                    </div>

                    <div class="rc-title">{{ $report->title }}</div>
                    <div class="rc-desc">{{ $report->description }}</div>

                    <div class="rc-footer">
                        <div class="rc-district">
                            📍 {{ $report->district->name ?? '-' }}
                            <span style="color:var(--border);margin:0 4px">•</span>
                            <span style="font-family:'IBM Plex Mono',monospace;font-size:11px;">{{ $report->created_at->diffForHumans() }}</span>
                        </div>
                        <a href="{{ route('reports.show', $report->id) }}" class="rc-link">Detail →</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- PAGINATION -->
        <div class="pag-wrap">
            <div class="pag-inner">
                {{ $reports->appends(request()->query())->links('vendor.pagination.simple-tailwind') }}
            </div>
        </div>

        @else
        <div class="empty">
            <div class="empty-icon">🔍</div>
            <div class="empty-title">Tidak Ada Laporan Ditemukan</div>
            <div class="empty-desc">Coba ubah kata kunci pencarian atau pilih kecamatan yang berbeda.</div>
        </div>
        @endif
    </div>
</body>
</html>