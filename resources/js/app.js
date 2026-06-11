/**
 * CivilWatch — app.js
 * Entry point yang di-bundle oleh Vite.
 *
 * Tanggung jawab:
 *  1. Inisialisasi Alpine.js (reaktivitas UI, modal, dropdown)
 *  2. Helper AJAX Upvote (Fetch API + JSON response dari UpvoteController)
 *  3. Sidebar toggle untuk layar mobile
 *  4. Auto-dismiss flash alert setelah 4 detik
 *  5. Konfirmasi hapus berbasis data-confirm (fallback non-Alpine)
 *  6. Fungsi utilitas bersama (format angka, truncate, dsb.)
 */

import Alpine from 'alpinejs';

/* ── 1. Daftarkan Alpine ke window agar bisa diakses dari Blade ── */
window.Alpine = Alpine;

/* ─────────────────────────────────────────────────────────────────
   2. ALPINE DATA STORES
   Dipakai lewat x-data="..." di Blade tanpa perlu deklarasi ulang
───────────────────────────────────────────────────────────────── */

/**
 * Store: upvote(reportId)
 * Gunakan di tombol upvote:
 *   <div x-data="upvote({{ $report->id }}, {{ $count }}, {{ $isUpvoted ? 'true' : 'false' }})">
 */
Alpine.data('upvote', (reportId, initialCount = 0, initialUpvoted = false) => ({
    count:   initialCount,
    upvoted: initialUpvoted,
    loading: false,

    async toggle() {
        if (this.loading) return;
        this.loading = true;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

            const response = await fetch(`/reports/${reportId}/upvote`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept':       'application/json',
                    'Content-Type': 'application/json',
                },
            });

            if (!response.ok) {
                const err = await response.json().catch(() => ({}));
                console.warn('[CivilWatch] Upvote gagal:', err.message ?? response.statusText);
                return;
            }

            const data = await response.json();
            this.count   = data.count;
            this.upvoted = data.upvoted;
        } catch (e) {
            console.error('[CivilWatch] Upvote error:', e);
        } finally {
            this.loading = false;
        }
    },
}));

/**
 * Store: confirmModal(message)
 * Modal konfirmasi generik yang bisa dipakai tanpa membangun HTML modal penuh.
 *   <div x-data="confirmModal('Yakin ingin menghapus?')">
 *     <button @click="open = true">Hapus</button>
 *     <form x-show="open" ...>
 */
Alpine.data('confirmModal', (message = 'Yakin melanjutkan tindakan ini?') => ({
    open:    false,
    message: message,
    show(msg) {
        if (msg) this.message = msg;
        this.open = true;
    },
    hide() { this.open = false; },
}));

/**
 * Store: imagePreview()
 * Preview foto sebelum upload (dipakai di create/edit report).
 *   <div x-data="imagePreview()">
 */
Alpine.data('imagePreview', () => ({
    previewUrl:  null,
    hasNewFile:  false,

    handleFile(event) {
        const file = event.target.files[0];
        if (!file) return;

        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2 MB!');
            event.target.value = '';
            this.previewUrl = null;
            this.hasNewFile = false;
            return;
        }

        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Format file harus JPG atau PNG!');
            event.target.value = '';
            return;
        }

        this.hasNewFile = true;
        const reader = new FileReader();
        reader.onload = (e) => { this.previewUrl = e.target.result; };
        reader.readAsDataURL(file);
    },

    clearFile(inputRef) {
        this.previewUrl = null;
        this.hasNewFile = false;
        if (inputRef) inputRef.value = '';
    },
}));

/**
 * Store: statusVerification()
 * Untuk panel verifikasi status laporan di halaman admin show.
 */
Alpine.data('statusVerification', () => ({
    selectedStatus: '',
    confirmVisible: false,
    currentBtn:     null,

    statusLabels: {
        published:   '🔵 Terima & Publikasi Laporan',
        rejected:    '🚫 Tolak Laporan',
        in_progress: '⚙️ Tandai Sedang Diproses',
        resolved:    '✅ Tandai Selesai & Tutup Laporan',
    },

    select(status, btnEl) {
        if (this.currentBtn) this.currentBtn.style.outline = 'none';
        this.currentBtn = btnEl;
        btnEl.style.outline = '2px solid #1E3A8A';
        this.selectedStatus  = status;
        this.confirmVisible  = true;
    },

    cancel() {
        if (this.currentBtn) this.currentBtn.style.outline = 'none';
        this.currentBtn     = null;
        this.selectedStatus = '';
        this.confirmVisible = false;
    },

    get confirmLabel() {
        return this.statusLabels[this.selectedStatus] ?? this.selectedStatus;
    },
}));

/* ── Start Alpine ── */
Alpine.start();

/* ─────────────────────────────────────────────────────────────────
   3. SIDEBAR TOGGLE (Mobile)
───────────────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {

    /* Sidebar hamburger (inject tombol hanya di mobile jika belum ada) */
    const sidebar     = document.querySelector('.sidebar');
    const mainWrapper = document.querySelector('.main-wrapper');

    if (sidebar && window.innerWidth <= 768) {
        /* Tombol hamburger di topbar */
        const topbar = document.querySelector('.topbar');
        if (topbar && !document.getElementById('cw-hamburger')) {
            const hamburger = document.createElement('button');
            hamburger.id = 'cw-hamburger';
            hamburger.setAttribute('aria-label', 'Buka navigasi');
            hamburger.style.cssText = `
                display: flex; align-items: center; justify-content: center;
                width: 36px; height: 36px; border: 1.5px solid var(--border);
                border-radius: 8px; background: var(--surface); cursor: pointer;
                margin-right: 12px; flex-shrink: 0;
            `;
            hamburger.innerHTML = `
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M4 6h16M4 12h16M4 18h16" stroke-width="2" stroke-linecap="round"/>
                </svg>
            `;
            topbar.querySelector('.topbar-left')?.prepend(hamburger);

            /* Overlay backdrop */
            const backdrop = document.createElement('div');
            backdrop.id = 'cw-backdrop';
            backdrop.style.cssText = `
                display: none; position: fixed; inset: 0;
                background: rgba(0,0,0,.45); z-index: 99;
            `;
            document.body.appendChild(backdrop);

            const openSidebar = () => {
                sidebar.classList.add('open');
                backdrop.style.display = 'block';
                document.body.style.overflow = 'hidden';
            };
            const closeSidebar = () => {
                sidebar.classList.remove('open');
                backdrop.style.display = 'none';
                document.body.style.overflow = '';
            };

            hamburger.addEventListener('click', openSidebar);
            backdrop.addEventListener('click', closeSidebar);
        }
    }

    /* ─────────────────────────────────────────────────────────────
       4. AUTO-DISMISS FLASH ALERTS
    ───────────────────────────────────────────────────────────── */
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach((el) => {
        /* Tambahkan tombol tutup */
        const closeBtn = document.createElement('button');
        closeBtn.setAttribute('aria-label', 'Tutup');
        closeBtn.style.cssText = `
            margin-left: auto; padding: 0 4px; background: transparent;
            border: none; cursor: pointer; font-size: 16px; color: inherit;
            opacity: .6; line-height: 1; flex-shrink: 0;
        `;
        closeBtn.textContent = '✕';
        closeBtn.addEventListener('click', () => dismissAlert(el));
        el.style.display = 'flex';
        el.appendChild(closeBtn);

        /* Auto-dismiss setelah 4 detik */
        setTimeout(() => dismissAlert(el), 4000);
    });

    function dismissAlert(el) {
        el.style.transition = 'opacity .3s ease, max-height .3s ease, margin .3s ease';
        el.style.opacity    = '0';
        el.style.maxHeight  = '0';
        el.style.marginBottom = '0';
        el.style.overflow   = 'hidden';
        setTimeout(() => el.remove(), 350);
    }

    /* ─────────────────────────────────────────────────────────────
       5. DATA-CONFIRM (fallback untuk form delete non-Alpine)
    ───────────────────────────────────────────────────────────── */
    document.querySelectorAll('[data-confirm]').forEach((el) => {
        el.addEventListener('click', (e) => {
            const msg = el.dataset.confirm || 'Yakin melanjutkan tindakan ini?';
            if (!window.confirm(msg)) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });

    /* ─────────────────────────────────────────────────────────────
       6. ACTIVE NAV ITEM highlight (fallback jika Blade tidak bisa)
    ───────────────────────────────────────────────────────────── */
    const currentPath = window.location.pathname;
    document.querySelectorAll('.nav-item[href]').forEach((link) => {
        const href = link.getAttribute('href');
        if (href && href !== '/' && currentPath.startsWith(href)) {
            link.classList.add('active');
        }
    });

    /* ─────────────────────────────────────────────────────────────
       7. FEED: Live search debounce (opsional enhancement)
       Jika ada form.feed-filter-form maka submit otomatis setelah jeda.
    ───────────────────────────────────────────────────────────── */
    const feedSearchInput = document.querySelector('.feed-filter-form .search-input');
    if (feedSearchInput) {
        let debounceTimer;
        feedSearchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                feedSearchInput.closest('form')?.submit();
            }, 600);
        });
    }

    /* ─────────────────────────────────────────────────────────────
       8. ADMIN: Inline table filter (districts, reports)
       Cari .js-table-filter input → filter baris .js-table-row
    ───────────────────────────────────────────────────────────── */
    document.querySelectorAll('.js-table-filter').forEach((input) => {
        input.addEventListener('input', () => {
            const q = input.value.toLowerCase().trim();
            const rows = input.closest('[data-table-scope]')
                ?.querySelectorAll('.js-table-row')
                ?? document.querySelectorAll('.js-table-row');

            rows.forEach((row) => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        });
    });

});

/* ─────────────────────────────────────────────────────────────────
   9. GLOBAL UTILITIES (bisa dipanggil dari Blade inline script)
───────────────────────────────────────────────────────────────── */

/**
 * Format angka ke string lokal Indonesia.
 * Contoh: formatNumber(14500) → "14.500"
 */
window.CW = window.CW || {};

window.CW.formatNumber = (n) =>
    new Intl.NumberFormat('id-ID').format(n);

/**
 * Potong teks panjang dengan elipsis.
 */
window.CW.truncate = (str, len = 60) =>
    str.length > len ? str.slice(0, len).trimEnd() + '…' : str;

/**
 * Salin teks ke clipboard dengan feedback visual.
 * Gunakan: CW.copyToClipboard('teks', buttonElement)
 */
window.CW.copyToClipboard = async (text, btnEl) => {
    try {
        await navigator.clipboard.writeText(text);
        if (btnEl) {
            const original = btnEl.textContent;
            btnEl.textContent = '✓ Disalin!';
            setTimeout(() => { btnEl.textContent = original; }, 1800);
        }
    } catch {
        console.warn('[CivilWatch] Clipboard API tidak tersedia.');
    }
};