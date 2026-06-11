<x-app-layout>
    <x-slot name="title">Kelola Wilayah</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">Dashboard</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Kelola Wilayah</span>
    </x-slot>

    <div class="page-header">
        <div class="page-title" style="display:flex;align-items:center;gap:8px;">
            <!-- Map icon -->
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/>
                <line x1="8" y1="2" x2="8" y2="18"/>
                <line x1="16" y1="6" x2="16" y2="22"/>
            </svg>
            Kelola Wilayah
        </div>
        <div class="page-desc">Manajemen data kecamatan sebagai referensi lokasi laporan pengaduan</div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start;">

        <!-- DISTRICTS TABLE -->
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Daftar Kecamatan</div>
                    <div style="font-size:13px;color:var(--text);margin-top:2px;">Total {{ $districts->total() }} kecamatan terdaftar</div>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <input type="text" placeholder="Cari kecamatan..." style="padding:7px 12px;border:1.5px solid var(--border);border-radius:7px;font-size:13px;font-family:'Sora',sans-serif;outline:none;width:160px;" oninput="filterTable(this.value)">
                </div>
            </div>

            @if($districts->count() > 0)
            <div style="overflow-x:auto;">
                <table class="data-table" id="districtTable">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Nama Kecamatan</th>
                            <th style="text-align:center">Jumlah Laporan</th>
                            <th>Dibuat</th>
                            <th style="text-align:center;width:140px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($districts as $i => $district)
                        <tr class="district-row" data-name="{{ strtolower($district->name) }}">
                            <td style="font-family:'IBM Plex Mono',monospace;font-size:12px;color:var(--text-light);">{{ $districts->firstItem() + $i }}</td>
                            <td>
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:8px;background:#EFF6FF;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <!-- Map pin -->
                                        <svg width="16" height="16" fill="none" stroke="#3B82F6" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/>
                                            <circle cx="12" cy="10" r="3"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div style="font-weight:600;color:var(--text);">{{ $district->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <span style="display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:26px;padding:0 10px;border-radius:13px;font-size:12.5px;font-weight:600;font-family:'IBM Plex Mono',monospace;{{ $district->reports_count > 0 ? 'background:#EFF6FF;color:var(--primary);' : 'background:var(--bg);color:var(--text-light);' }}">
                                    {{ $district->reports_count ?? 0 }}
                                </span>
                            </td>
                            <td style="font-size:12.5px;color:var(--text-muted);font-family:'IBM Plex Mono',monospace;">
                                {{ $district->created_at->format('d M Y') }}
                            </td>
                            <td>
                                <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                    <!-- Edit Button -->
                                    <button
                                        onclick="openEditModal({{ $district->id }}, '{{ addslashes($district->name) }}')"
                                        class="btn btn-outline btn-sm btn-icon"
                                        title="Edit Kecamatan"
                                    >
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </button>

                                    <!-- Delete Button -->
                                    <div x-data="{ confirmDelete: false }" style="display:inline-block;">
                                        <button type="button" @click="confirmDelete = true" class="btn btn-danger btn-sm btn-icon" title="Hapus Kecamatan">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>

                                        <div x-show="confirmDelete"
                                            style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background-color:rgba(0,0,0,0.5);z-index:9999;"
                                            x-transition.opacity
                                            @keydown.escape.window="confirmDelete = false">

                                            <div @click.away="confirmDelete = false"
                                                style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:#FFF;width:90%;max-width:400px;padding:24px;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.2);box-sizing:border-box;text-align:left;">

                                                <div style="display:flex;align-items:center;gap:8px;font-size:18px;font-weight:bold;color:#DC2626;margin-bottom:8px;">
                                                    <!-- Warning triangle -->
                                                    <svg width="20" height="20" fill="none" stroke="#DC2626" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                                        <line x1="12" y1="9" x2="12" y2="13"/>
                                                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                                                    </svg>
                                                    Hapus Kecamatan
                                                </div>

                                                <div style="font-size:14px;color:#475569;margin-bottom:24px;line-height:1.5;white-space:normal;">
                                                    Apakah Anda yakin ingin menghapus kecamatan ini secara permanen? Tindakan ini tidak dapat dibatalkan.
                                                </div>

                                                <form method="POST" action="{{ route('admin.districts.destroy', $district->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div style="display:flex;justify-content:center;gap:10px;">
                                                        <button type="button" @click="confirmDelete = false" class="btn btn-outline" style="margin:0;">Batal</button>
                                                        <button type="submit" class="btn btn-danger" style="margin:0;">Hapus</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($districts->hasPages())
            <div style="padding:16px 24px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:13px;color:var(--text-muted);">
                    Menampilkan {{ $districts->firstItem() }}–{{ $districts->lastItem() }} dari {{ $districts->total() }}
                </span>
                {{ $districts->links('vendor.pagination.simple-tailwind') }}
            </div>
            @endif

            @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <!-- Map empty state -->
                    <svg width="40" height="40" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" style="opacity:.4;">
                        <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/>
                        <line x1="8" y1="2" x2="8" y2="18"/>
                        <line x1="16" y1="6" x2="16" y2="22"/>
                    </svg>
                </div>
                <div class="empty-state-title">Belum Ada Kecamatan</div>
                <div class="empty-state-desc">Tambahkan kecamatan pertama menggunakan form di samping.</div>
            </div>
            @endif
        </div>

        <!-- RIGHT SIDEBAR -->
        <div style="display:flex;flex-direction:column;gap:16px;">

            <!-- ADD FORM -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title" style="display:flex;align-items:center;gap:7px;">
                        <!-- Plus circle -->
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="16"/>
                            <line x1="8" y1="12" x2="16" y2="12"/>
                        </svg>
                        Tambah Kecamatan
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.districts.store') }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Nama Kecamatan <span>*</span></label>
                            <input
                                type="text"
                                name="name"
                                class="form-input"
                                value="{{ old('name') }}"
                                placeholder="Contoh: Banjarmasin Tengah"
                                required
                                autofocus
                            >
                            @error('name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;display:inline-flex;align-items:center;gap:7px;">
                            <!-- Plus icon -->
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round">
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Tambah Kecamatan
                        </button>
                    </form>
                </div>
            </div>

            <!-- INFO CARD -->
            <div style="background:#EFF6FF;border:1px solid #BFDBFE;border-radius:10px;padding:16px;">
                <div style="font-size:13px;font-weight:600;color:var(--primary);margin-bottom:8px;display:flex;align-items:center;gap:6px;">
                    <!-- Info circle -->
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    Informasi
                </div>
                <div style="font-size:12.5px;color:#1E40AF;line-height:1.7;">
                    Kecamatan yang masih memiliki laporan terikat <strong>tidak dapat dihapus</strong> untuk menjaga integritas data laporan.
                    <br><br>
                    Pastikan tidak ada laporan aktif sebelum menghapus kecamatan.
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="editModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;align-items:center;justify-content:center;">
        <div style="background:var(--surface);border-radius:14px;width:420px;overflow:hidden;box-shadow:0 20px 60px rgba(0,0,0,.2);">
            <div style="padding:20px 24px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;">
                <div style="font-size:16px;font-weight:600;color:var(--text);display:flex;align-items:center;gap:8px;">
                    <!-- Edit / pencil icon -->
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/>
                    </svg>
                    Edit Kecamatan
                </div>
                <button onclick="closeEditModal()" style="width:30px;height:30px;border:none;background:var(--bg);border-radius:6px;cursor:pointer;display:flex;align-items:center;justify-content:center;">
                    <!-- X close -->
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6"  y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            <div style="padding:24px;">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label class="form-label">Nama Kecamatan <span>*</span></label>
                        <input type="text" id="editName" name="name" class="form-input" required placeholder="Nama kecamatan">
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div style="display:flex;gap:10px;">
                        <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;display:inline-flex;align-items:center;gap:7px;">
                            <!-- Save / floppy disk -->
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                                <polyline points="7 3 7 8 15 8"/>
                            </svg>
                            Simpan
                        </button>
                        <button type="button" onclick="closeEditModal()" class="btn btn-outline">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, name) {
            document.getElementById('editForm').action = `/admin/districts/${id}`;
            document.getElementById('editName').value = name;
            document.getElementById('editModal').style.display = 'flex';
            setTimeout(() => document.getElementById('editName').focus(), 50);
        }
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });
        function filterTable(val) {
            document.querySelectorAll('.district-row').forEach(row => {
                const name = row.getAttribute('data-name');
                row.style.display = name.includes(val.toLowerCase()) ? '' : 'none';
            });
        }
    </script>
</x-app-layout>