# Skill: VoltGear Admin — UX Fix Guide

## Description

Apply this skill when the user asks to fix, improve, or implement UX issues
in the VoltGear admin dashboard (`index.html`). Covers empty states, loading
states, destructive action confirmations, keyboard shortcuts, clickable stat
widgets, and mobile scroll indicators. Always apply fixes incrementally —
one issue at a time — and validate in the browser after each change.

---

## Context

- **File target:** `index.html` (single-file dashboard)
- **Stack:** Vanilla HTML + CSS + JavaScript, no framework
- **CSS variables already defined:** `--brand-orange`, `--brand-blue`,
  `--success`, `--danger`, `--warning`, `--panel-bg`, `--border`, `--text-muted`
- **Do not** introduce external libraries or break existing layout

---

## Fix 1 — Empty States

**Trigger:** User says "tambahkan empty state", "tabel kosong tidak ada pesan",
or similar.

**What to do:** Add `.empty-state` CSS class and inject the empty state block
inside any table `<tbody>` that returns zero rows.

### CSS — paste inside existing `<style>` block

```css
/* Empty State */
.empty-state {
  text-align: center;
  padding: 48px 24px;
  color: var(--text-muted);
}
.empty-state i {
  font-size: 32px;
  margin-bottom: 12px;
  opacity: 0.4;
  display: block;
}
.empty-state p {
  font-size: 13px;
  margin-bottom: 16px;
  line-height: 1.6;
}
```

### HTML — inject inside `<tbody>` when data is empty

```html
<!-- Contoh: tabel produk kosong -->
<tbody>
  <tr>
    <td colspan="8">
      <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <p>Tidak ada produk ditemukan.<br>
           Coba ubah filter atau tambahkan produk baru.</p>
        <button class="btn btn-primary btn-sm">
          <i class="fas fa-plus"></i> Tambah Produk
        </button>
      </div>
    </td>
  </tr>
</tbody>
```

### JavaScript — simulate with search input

```js
// Attach ke input search produk
document.querySelector('#produk .global-search input')
  .addEventListener('input', function () {
    const query = this.value.toLowerCase();
    const rows  = document.querySelectorAll('#produk tbody tr:not(.empty-row)');
    let visible = 0;

    rows.forEach(row => {
      const match = row.textContent.toLowerCase().includes(query);
      row.style.display = match ? '' : 'none';
      if (match) visible++;
    });

    const existing = document.querySelector('#produk .empty-row');
    if (existing) existing.remove();

    if (visible === 0) {
      const tbody = document.querySelector('#produk tbody');
      tbody.insertAdjacentHTML('beforeend', `
        <tr class="empty-row">
          <td colspan="8">
            <div class="empty-state">
              <i class="fas fa-search"></i>
              <p>Tidak ada produk untuk "<strong>${this.value}</strong>".<br>
                 Coba kata kunci lain atau clear filter.</p>
              <button class="btn btn-default btn-sm"
                      onclick="this.closest('tr').remove();
                               document.querySelector('#produk .global-search input').value='';
                               document.querySelectorAll('#produk tbody tr').forEach(r=>r.style.display='')">
                Clear Filter
              </button>
            </div>
          </td>
        </tr>
      `);
    }
  });
```

---

## Fix 2 — Konfirmasi Aksi Destruktif

**Trigger:** User says "tambahkan konfirmasi hapus", "confirm dialog",
"jangan langsung hapus", or similar.

**What to do:** Add a reusable modal component and wire it to all
destructive buttons (hapus, blokir massal, sembunyikan ulasan).

### CSS — paste inside existing `<style>` block

```css
/* Confirm Modal */
.modal-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(15, 23, 42, 0.5);
  z-index: 999;
  align-items: center;
  justify-content: center;
}
.modal-overlay.show { display: flex; }
.modal-box {
  background: var(--panel-bg);
  border: 1px solid var(--border);
  border-radius: 6px;
  padding: 24px;
  width: 360px;
  box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}
.modal-box h3 {
  font-size: 15px;
  font-weight: 700;
  margin-bottom: 8px;
  color: var(--text-main);
}
.modal-box p {
  font-size: 13px;
  color: var(--text-muted);
  margin-bottom: 20px;
  line-height: 1.5;
}
.modal-actions { display: flex; gap: 8px; justify-content: flex-end; }
```

### HTML — paste before closing `</body>`

```html
<div class="modal-overlay" id="confirmModal">
  <div class="modal-box">
    <h3 id="modalTitle">Konfirmasi Aksi</h3>
    <p id="modalBody">Apakah kamu yakin?</p>
    <div class="modal-actions">
      <button class="btn btn-default" onclick="closeModal()">Batal</button>
      <button class="btn btn-danger" id="modalConfirmBtn">Ya, Hapus</button>
    </div>
  </div>
</div>
```

### JavaScript — paste inside `<script>` block

```js
let _pendingAction = null;

function confirmAction({ title, body, label = 'Konfirmasi', onConfirm }) {
  document.getElementById('modalTitle').textContent    = title;
  document.getElementById('modalBody').textContent     = body;
  document.getElementById('modalConfirmBtn').textContent = label;
  document.getElementById('confirmModal').classList.add('show');
  _pendingAction = onConfirm;
  document.getElementById('modalConfirmBtn').onclick = () => {
    onConfirm();
    closeModal();
  };
}

function closeModal() {
  document.getElementById('confirmModal').classList.remove('show');
  _pendingAction = null;
}

// Tutup modal saat klik overlay
document.getElementById('confirmModal').addEventListener('click', function (e) {
  if (e.target === this) closeModal();
});

// Contoh: tombol Hapus di Flash Sale
document.querySelector('#flashsale .btn-danger').addEventListener('click', () => {
  confirmAction({
    title:    'Hapus dari Flash Sale?',
    body:     'Produk "Bor Bosch GSB 550" akan dihapus dari kampanye ini. Stok yang dialokasikan akan dikembalikan.',
    label:    'Ya, Hapus',
    onConfirm: () => {
      // logika hapus produk dari flash sale
      console.log('Produk dihapus dari flash sale');
    }
  });
});

// Contoh: tombol Blokir Massal di Pelanggan
document.querySelector('#pengguna .btn-danger').addEventListener('click', () => {
  const checked = document.querySelectorAll('#pengguna .chk-box:checked').length;
  if (checked === 0) return alert('Pilih minimal satu pelanggan terlebih dahulu.');
  confirmAction({
    title:    `Blokir ${checked} Pelanggan?`,
    body:     `${checked} akun yang dipilih akan diblokir dan tidak bisa login. Aksi ini bisa dibatalkan nanti.`,
    label:    'Ya, Blokir',
    onConfirm: () => {
      console.log(`${checked} pelanggan diblokir`);
    }
  });
});
```

---

## Fix 3 — Loading State pada Tombol

**Trigger:** User says "loading state tombol", "spinner saat simpan",
"disable button saat proses", or similar.

**What to do:** Add a reusable `setLoading()` utility and apply it to
all primary action buttons.

### JavaScript — paste inside `<script>` block

```js
/**
 * Toggle loading state pada sebuah tombol.
 * @param {HTMLElement} btn      - Elemen tombol
 * @param {boolean}     loading  - true = loading, false = normal
 * @param {string}      label    - Label asli tombol (untuk restore)
 */
function setLoading(btn, loading, label) {
  if (loading) {
    btn.disabled = true;
    btn.dataset.originalLabel = btn.innerHTML;
    btn.innerHTML = `<i class="fas fa-circle-notch fa-spin"></i> ${label || 'Memproses...'}`;
    btn.style.opacity = '0.75';
  } else {
    btn.disabled = false;
    btn.innerHTML = btn.dataset.originalLabel || label;
    btn.style.opacity = '';
  }
}

// Contoh pemakaian: tombol Simpan di pengaturan
document.querySelector('#tab-general .btn-primary')
  .addEventListener('click', async function () {
    setLoading(this, true, 'Menyimpan...');
    await new Promise(r => setTimeout(r, 1500)); // ganti dengan fetch() asli
    setLoading(this, false);
    showToast('Pengaturan berhasil disimpan', 'success');
  });

// Contoh pemakaian: tombol Kirim Resi di Order
document.querySelector('#order .btn-primary')
  .addEventListener('click', async function () {
    setLoading(this, true, 'Mengirim...');
    await new Promise(r => setTimeout(r, 1200));
    setLoading(this, false);
    showToast('Resi berhasil dikirim ke pelanggan', 'success');
  });
```

---

## Fix 4 — Keyboard Shortcut '/'

**Trigger:** User says "shortcut slash tidak jalan", "tekan / untuk search",
"keyboard shortcut", or similar.

**What to do:** Add a `keydown` listener that focuses the global search input
when `/` is pressed and the user is not already typing in a field.

### JavaScript — paste inside `<script>` block

```js
document.addEventListener('keydown', (e) => {
  const tag = document.activeElement.tagName;
  const isTyping = tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT';

  if (e.key === '/' && !isTyping) {
    e.preventDefault();
    const searchInput = document.querySelector('.global-search input');
    if (searchInput) {
      searchInput.focus();
      searchInput.select();
    }
  }

  // ESC untuk tutup search / blur
  if (e.key === 'Escape' && document.activeElement === document.querySelector('.global-search input')) {
    document.activeElement.blur();
  }
});
```

---

## Fix 5 — Stat Widget Clickable

**Trigger:** User says "widget tidak bisa diklik", "stat widget actionable",
"klik stok kritis navigasi ke produk", or similar.

**What to do:** Add click handlers and hover cursor to each stat widget,
navigating to the relevant view with the appropriate filter context.

### CSS — paste inside existing `<style>` block

```css
.stat-widget {
  cursor: pointer;
  transition: box-shadow 0.15s, transform 0.15s;
}
.stat-widget:hover {
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  transform: translateY(-1px);
}
```

### JavaScript — paste inside `<script>` block

```js
// Mapping stat widget → target view
const statWidgets = document.querySelectorAll('#overview .stat-widget');

const widgetActions = [
  null, // Total Pendapatan → tidak perlu navigasi
  () => switchView('order',   document.querySelector('[onclick*="order"]'),   'Daftar Pesanan'),
  () => switchView('pengguna',document.querySelector('[onclick*="pengguna"]'),'Data Pelanggan'),
  () => {
    switchView('produk', document.querySelector('[onclick*="produk"]'), 'Kelola Produk');
    // Set filter stok kritis setelah navigasi
    setTimeout(() => {
      const search = document.querySelector('#produk .global-search input');
      if (search) { search.value = 'kritis'; search.dispatchEvent(new Event('input')); }
    }, 100);
  },
];

statWidgets.forEach((widget, i) => {
  if (widgetActions[i]) {
    widget.addEventListener('click', widgetActions[i]);
    widget.title = 'Klik untuk lihat detail';
  }
});
```

---

## Fix 6 — Mobile Scroll Indicator

**Trigger:** User says "tabel terpotong di mobile", "scroll indicator",
"fade kanan tabel", or similar.

**What to do:** Add a right-fade shadow to `.table-responsive` containers
to signal that the table is horizontally scrollable.

### CSS — paste inside existing `<style>` block

```css
/* Mobile scroll indicator */
.table-responsive {
  position: relative;
}
.table-responsive::after {
  content: '';
  position: absolute;
  top: 0;
  right: 0;
  width: 48px;
  height: 100%;
  background: linear-gradient(to right, transparent, var(--panel-bg));
  pointer-events: none;
  opacity: 0;
  transition: opacity 0.2s;
}
.table-responsive.has-overflow::after {
  opacity: 1;
}
```

### JavaScript — paste inside `<script>` block

```js
function checkTableOverflow() {
  document.querySelectorAll('.table-responsive').forEach(el => {
    const hasOverflow = el.scrollWidth > el.clientWidth;
    el.classList.toggle('has-overflow', hasOverflow);

    // Hilangkan fade saat sudah di-scroll ke ujung kanan
    el.addEventListener('scroll', () => {
      const atEnd = el.scrollLeft + el.clientWidth >= el.scrollWidth - 4;
      el.classList.toggle('has-overflow', !atEnd);
    }, { passive: true });
  });
}

checkTableOverflow();
window.addEventListener('resize', checkTableOverflow);
```

---

## Bonus — Toast Notification

Dipakai oleh Fix 3 dan bisa dipanggil dari mana saja setelah aksi berhasil.

### CSS — paste inside existing `<style>` block

```css
/* Toast */
#toast-container {
  position: fixed;
  bottom: 24px;
  right: 24px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  z-index: 9999;
}
.toast {
  padding: 12px 16px;
  border-radius: 4px;
  font-size: 13px;
  font-weight: 500;
  color: #fff;
  display: flex;
  align-items: center;
  gap: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  animation: slideIn 0.2s ease;
  max-width: 320px;
}
.toast.success { background: var(--success); }
.toast.danger  { background: var(--danger); }
.toast.warning { background: var(--warning); }
@keyframes slideIn {
  from { opacity: 0; transform: translateY(8px); }
  to   { opacity: 1; transform: translateY(0); }
}
```

### HTML — paste before closing `</body>`

```html
<div id="toast-container"></div>
```

### JavaScript — paste inside `<script>` block

```js
/**
 * Tampilkan toast notification.
 * @param {string} message  - Teks pesan
 * @param {'success'|'danger'|'warning'} type
 * @param {number} duration - Durasi ms (default 3000)
 */
function showToast(message, type = 'success', duration = 3000) {
  const icons = { success: 'fa-check-circle', danger: 'fa-times-circle', warning: 'fa-exclamation-circle' };
  const container = document.getElementById('toast-container');
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  toast.innerHTML = `<i class="fas ${icons[type]}"></i> ${message}`;
  container.appendChild(toast);
  setTimeout(() => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(8px)';
    toast.style.transition = '0.2s';
    setTimeout(() => toast.remove(), 200);
  }, duration);
}

// Contoh penggunaan:
// showToast('Produk berhasil disimpan', 'success');
// showToast('Gagal terhubung ke server', 'danger');
// showToast('Stok hampir habis', 'warning');
```

---

## Urutan Implementasi yang Disarankan

1. **Toast** — dibutuhkan oleh fix lain, pasang duluan
2. **Confirm Modal** — cegah data loss, prioritas tertinggi
3. **Empty States** — UX dasar yang sering terlihat
4. **Loading State** — cegah double-submit
5. **Keyboard Shortcut** — satu baris JS, effort minimal
6. **Stat Widget Clickable** — quick win, tambah discoverability
7. **Mobile Scroll Indicator** — polish terakhir