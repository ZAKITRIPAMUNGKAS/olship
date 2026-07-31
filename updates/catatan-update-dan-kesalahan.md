# Catatan Update & Kesalahan — Listrindo Jaya WMS
> Dibuat: 2026-05-25 | Tujuan: Referensi agar kesalahan tidak terulang di session berikutnya.

---

## ⚠️ KESALAHAN YANG SERING TERJADI

### 1. CSS di-push via `@push('styles')` tapi tidak jalan / di-override Vite

**Gejala:** Style yang ditulis di `@push('styles')` tidak berpengaruh, atau kalah dengan global CSS.

**Akar masalah:**
- `@vite(...)` dimuat di `<head>` **sebelum** `@stack('styles')`, sehingga seharusnya `@push` menang.
- Tapi jika global CSS punya rule yang sama dengan specificity lebih tinggi, `@push` tetap kalah.
- Contoh: Global CSS punya `.cat-content { flex: 1 }` → override scoped `.cat-content { min-width: 0 }`.

**Fix yang benar:**
1. Taruh **semua** CSS page-specific ke dalam `listrindojaya.css` (via Vite).
2. Untuk override penting yang butuh prioritas, gunakan `!important` di `@push('styles')`.
3. **JANGAN** percaya bahwa scoped CSS otomatis menang — selalu verifikasi dengan DevTools.

---

### 2. Vite belum recompile CSS → perubahan CSS tidak terlihat di browser

**Gejala:** CSS sudah diubah di file, tapi browser masih tampilkan gaya lama. Class yang baru ditambahkan tidak punya style sama sekali.

**Akar masalah:**
- Vite HMR kadang tidak mendeteksi perubahan file jika dilakukan via tool eksternal (bukan editor user).
- Jika Vite tidak running, CSS build lama (dari `public/build`) yang dipakai.

**Fix:**
1. Selalu tambahkan CSS kritis via `@push('styles')` dengan `!important` sebagai **safety net** — ini di-inject langsung ke HTML tanpa butuh Vite.
2. Minta user restart Vite jika diperlukan: `npm run dev`.
3. Untuk production: `npm run build`.

**ATURAN:** Untuk setiap page-specific CSS yang kritikal (layout grid, visibility toggle), selalu ada **dua tempat**:
- `listrindojaya.css` → source of truth
- `@push('styles')` di view → safety net pakai `!important`

---

### 3. CSS bocor ke luar `<style>` tag

**Gejala:** Raw CSS muncul sebagai teks di halaman. Semua styling hilang.

**Akar masalah:**
- Ketika edit partial (replace_file_content) gagal atau hanya menghapus sebagian `<style>...</style>` block, sisa CSS keluar dari tag.
- Contoh nyata: `category.blade.php` punya raw CSS di body setelah `@push` block dihapus sebagian.

**Fix:**
- Saat menghapus `@push('styles')` block besar, gunakan **Overwrite** (`write_to_file` dengan file baru bersih) — jangan partial replace.
- Setelah edit, selalu cek 10 baris pertama file untuk verifikasi tidak ada CSS bocor.

---

### 4. `repeat(auto-fill, minmax(Xpx, 1fr))` membuat kartu raksasa saat produk sedikit

**Gejala:** Jika hanya ada 2–3 produk, kartu stretch jadi 50% atau 33% lebar container.

**Akar masalah:**
- `auto-fill` dengan `minmax` membagi kolom berdasarkan lebar minimum, tapi sisa ruang dibagi ke kolom yang ada.
- 2 produk + `minmax(180px, 1fr)` di container 700px → 2 kolom @ ~350px → kartu raksasa.

**Fix:**
- Gunakan `repeat(N, 1fr)` fixed, **bukan** `auto-fill` untuk product grid di category page.
- Homepage `.pgrid` boleh pakai `auto-fill` karena selalu banyak produk.

**Standar kolom category:**
```css
.cat-grid {
  grid-template-columns: repeat(4, 1fr);   /* desktop */
}
@media (max-width: 1200px) { repeat(3, 1fr) }
@media (max-width: 768px)  { repeat(2, 1fr) }
```

---

### 5. `:last-child` tidak jalan jika element diikuti sibling lain

**Gejala:** CSS `filter-section:last-child { border-bottom: none }` tidak menghapus border section terakhir.

**Akar masalah:**
- `:last-child` hanya match jika element benar-benar anak terakhir dari parentnya.
- Jika ada `</aside>` atau elemen lain setelah `.filter-section` terakhir, selector tidak match.

**Fix:** Ganti `:last-child` → `:last-of-type` untuk class-based selectors.

---

### 6. Inline style `style="color:var(--green)"` di HTML tidak ikut design token

**Gejala:** Warna hijau muncul di elemen yang harusnya brand blue.

**Fix:**
- Hapus semua inline `style=""` dari Blade view yang hardcode warna.
- Semua warna harus dari CSS class yang menggunakan token.

---

## ✅ SISTEM DESIGN TOKEN (AKTIF)

File: `resources/css/listrindojaya.css` — bagian `:root`

```css
/* Palet */
--brand:  #025cca   /* biru dominan — semua brand element */
--accent: #ea580c   /* oranye — HANYA promo/flash sale/harga flash */
--red:    #e11d48   /* HANYA error/badge diskon */
--green:  #059669   /* HANYA status icon kecil */

/* Spacing — kelipatan 4px */
--sp-1: 4px  | --sp-2: 8px  | --sp-3: 12px | --sp-4: 16px
--sp-6: 24px | --sp-8: 32px | --sp-12: 48px

/* Radius */
--r-sm: 10px   /* button, badge, chip */
--r-md: 16px   /* card, panel, hero */

/* Shadow */
--sh-1: 0 1px 4px rgba(0,0,0,.05)    /* resting */
--sh-2: 0 4px 16px rgba(0,0,0,.08)   /* elevated/hover */

/* Netral */
--bg: #f1f5f9 | --surface: #fff | --border: #e2e8f0
--ink: #0f172a | --ink-2: #334155 | --ink-3: #64748b | --ink-4: #94a3b8
```

---

## ✅ ARSITEKTUR FILE YANG SUDAH DIUPDATE

### CSS Global
- **`resources/css/listrindojaya.css`** — satu-satunya sumber CSS global

### Views yang Sudah Direfactor
| File | Status | Catatan |
|------|--------|---------|
| `storefront/home.blade.php` | ✅ Selesai | Hapus watermark, green inline, dua-warna copy |
| `storefront/dashboard/layout.blade.php` | ✅ Selesai | Mobile grid fix, sidebar, alert styles |
| `storefront/payment/show.blade.php` | ✅ Selesai | Premium UI, loading state, error feedback |
| `storefront/payment/finish.blade.php` | ✅ Selesai | Success/error state |
| `storefront/category/show.blade.php` | ✅ Selesai | Grid fix, filter collapsible mobile |
| `category.blade.php` (root) | ✅ Selesai | Legacy file disamakan dengan sistem baru |
| `storefront/products/show.blade.php` | Belum direfactor | |

---

## ✅ ATURAN MOBILE YANG HARUS DIPATUHI

1. **Zero horizontal overflow** di 360px, 375px, 414px — WAJIB
2. Tidak boleh pakai `overflow-x: hidden` sebagai fix — harus perbaiki akarnya
3. `white-space: nowrap` di elemen dalam container sempit → ganti ke `normal`
4. Semua padding container mobile: minimum `16px` kiri-kanan
5. Flash sale grid: **tetap 2 kolom** di mobile, tidak collapse ke 1

---

## ✅ ATURAN DESAIN

1. **Tidak ada emoji** di HTML
2. **Tidak ada copy dua warna dalam satu kalimat** (misal: `HARGA <span style="color:orange">GILA-GILAAN</span>`)
3. **Tidak ada watermark icon** (`.side-card-bg` dengan opacity 4%)
4. **Hijau bukan warna brand** — hanya untuk status indicator kecil
5. **Oranye hanya untuk** flash sale/promo/harga flash — tidak untuk elemen umum
6. Semua warna referensi via CSS token — **tidak ada hardcode hex** di HTML/Blade

---

## ✅ PROSEDUR KERJA YANG BENAR

### Saat menambah CSS baru:
1. Tambahkan di `listrindojaya.css` → source of truth
2. Jika CSS kritis (layout/grid), tambahkan juga di `@push('styles')` view dengan `!important` → safety net
3. Verifikasi dengan melihat file setelah edit

### Saat menghapus block CSS besar dari Blade:
1. Gunakan `write_to_file` dengan `Overwrite: true` untuk tulis ulang file bersih
2. JANGAN gunakan partial replace untuk blok > 50 baris — risiko bocor

### Saat ada laporan layout berantakan:
1. Cek apakah Vite sudah compile terbaru (tanda: class ada di CSS tapi style tidak terapply)
2. Cek apakah ada raw CSS bocor ke body HTML
3. Cek urutan specificity — global Vite vs @push

### Sebelum selesai setiap session:
1. Jalankan `vendor\bin\phpunit` — harus 7/7 pass
2. Verifikasi tidak ada inline style warna di HTML baru
3. Verifikasi file tidak punya raw CSS bocor
