<x-app-layout>
    <x-slot name="title">Kelola Wilayah</x-slot>
    <x-slot name="breadcrumb">
        <a href="{{ route('admin.dashboard') }}" class="breadcrumb-item">Dashboard</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Kelola Wilayah</span>
    </x-slot>

    <div class="page-header">
        <div class="page-title">🗺️ Kelola Wilayah</div>
        <div class="page-desc">Manajemen data kecamatan sebagai referensi lokasi laporan pengaduan</div>
    </div>

    <div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start;">

        <!-- DISTRICTS TABLE -->
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Daftar Kecamatan</div>
                    <div style="font-size:13px;color:var(--text-muted);margin-top:2px;">Total {{ $districts->total() }} kecamatan terdaftar</div>
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
                                    <div style="width:32px;height:32px;border-radius:8px;background:#EFF6FF;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;">📍</div>
                                    <div>
                                        <div style="font-weight:600;color:var(--text);">{{ $district->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="text-align:center;">
                                <span style="display:inline-flex;align-items:center;justify-content:center;min-width:32px;height:26px;padding:0 10px;border-radius:13px;font-size:12.5px;font-weight:600;font-family:'IBM Plex Mono',monospace;{{ $district->reports_count > 0 ? 'background:#EFF6FF;color:var(--primary);' : 'background:var(--bg);color:var(--text-light);' }}">
                                    {{ $district->reports_count ?? 0}}
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
                                    <form method="POST" action="{{ route('admin.districts.destroy', $district->id) }}" onsubmit="return confirm('Hapus kecamatan \'{{ $district->name }}\'?\nPastikan tidak ada laporan yang terikat.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Hapus Kecamatan">
                                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                        </button>
                                    </form>
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
                <div class="empty-state-icon">🗺️</div>
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
                    <div class="card-title">+ Tambah Kecamatan</div>
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
                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                            ➕ Tambah Kecamatan
                        </button>
                    </form>
                </div>
            </div>

            <!-- INFO CARD -->
            <div style="background:#EFF6FF;border:1px solid #BFDBFE;border-radius:10px;padding:16px;">
                <div style="font-size:13px;font-weight:600;color:var(--primary);margin-bottom:8px;">ℹ️ Informasi</div>
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
                <div style="font-size:16px;font-weight:600;color:var(--text);">✏️ Edit Kecamatan</div>
                <button onclick="closeEditModal()" style="width:30px;height:30px;border:none;background:var(--bg);border-radius:6px;cursor:pointer;font-size:15px;display:flex;align-items:center;justify-content:center;">✕</button>
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
                        <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;">💾 Simpan</button>
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