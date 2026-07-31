# AUDIT.md — OLSHOP FIX Gap Analysis
Generated: 2026-05-12 | Updated: single-seller refactor

---

## ARSITEKTUR: SINGLE-SELLER

Sistem ini hanya untuk **Listrindo Jaya Elektrik** sebagai satu-satunya penjual.
- Role `seller` dihapus → diganti `staff` (operator internal)
- Seller panel (`/seller/*`) dialihkan ke admin dashboard
- Produk & order dikelola dari admin panel oleh admin/staff
- Tidak ada multi-toko, tidak ada withdrawal antar-seller

### Role yang aktif:
| Role | Akses |
|------|-------|
| super_admin | Semua |
| admin | Produk CRUD, order, user, kupon, flash sale, laporan |
| staff | Produk (view/create/update), order (view/update-status), laporan |
| customer | Storefront saja |

## 1. ROUTES SUMMARY (118 total)

| Role | Count |
|------|-------|
| Guest (storefront) | 18 |
| Customer (auth required) | 8 |
| Seller | 2 |
| Admin | 57 |
| Webhook | 1 |
| Shipping API | 3 |
| System/Docs | 29 |

---

## 2. CONTROLLER STATUS

### Storefront
| Controller | Method | Status |
|-----------|--------|--------|
| HomeController | index | DONE |
| ProductController | show | DONE |
| CategoryController | show | DONE |
| SearchController | index | DONE |
| CartController | index, add, update, remove | DONE |
| CheckoutController | index, process | DONE |
| PaymentController | show, finish | DONE |
| PaymentController | webhook | PARTIAL — tidak idempotent, tidak simpan raw payload |
| CustomerDashboardController | index, orders, addresses | DONE |
| FlashSaleController | index | DONE |
| ShippingController | provinces, cities, cost | DONE |

### Auth
| Controller | Method | Status |
|-----------|--------|--------|
| AuthController | login, register, logout | DONE |
| AuthController | forgot/reset password | MISSING |
| AuthController | email verification | MISSING |

### Seller
| Controller | Method | Status |
|-----------|--------|--------|
| SellerDashboardController | index | PARTIAL — revenue = 0 placeholder |
| SellerDashboardController | onboarding | STUB — view exists, no form/store logic |
| Seller\ProductController | semua CRUD | MISSING |
| Seller\OrderController | list, detail, update status, AWB | MISSING |
| Seller\WithdrawalController | request, history | MISSING |

### Admin
| Controller | Method | Status |
|-----------|--------|--------|
| DashboardController | index | DONE |
| ProductController | index, create, store, show, edit, update, destroy, bulk, export | DONE |
| ProductController | import | STUB — validasi file saja, tidak ada logic import |
| CategoryController | semua + reorder | DONE |
| BrandController | semua | DONE |
| OrderController | index, show, invoice, export, updateStatus | DONE |
| OrderController | refund | STUB — return back() saja |
| UserController | semua + ban/unban | DONE |
| SellerController | index, show, verify, suspend | DONE |
| CouponController | semua + toggle | DONE |
| FlashSaleController | semua + addItem/removeItem/toggle | DONE |
| BannerController | semua + reorder | DONE |
| SettingController | index, update | DONE |
| ReviewController | index, approve, reject, reply | DONE |
| WithdrawalController | index, approve, reject | PARTIAL — class_exists() guard, tidak robust |
| ReportController | revenue, products, export | DONE |

---

## 3. SERVICE STATUS

| Service | Status | Catatan |
|---------|--------|---------|
| CartService | DONE | Guest + auth, merge on login |
| OrderService | DONE | Atomic, lockForUpdate, snapshot |
| ShippingService | DONE | RajaOngkir + dummy fallback + cache |
| ProductService | DONE | createProduct, updateStock, getActivePrice |
| ReportService | DONE | KPI, charts, top products |
| PaymentService | PARTIAL | createMidtransTransaction DONE; handleWebhook tidak idempotent, tidak simpan raw payload, tidak restore stock saat cancel/expire |

---

## 4. MISSING INFRASTRUCTURE

| Komponen | Status |
|----------|--------|
| app/Policies/ | MISSING — tidak ada Policy, authorization hanya via middleware role |
| app/Jobs/ | MISSING — tidak ada queue job |
| app/Console/Commands/ | MISSING — tidak ada scheduler/command |
| app/Notifications/ | MISSING — tidak ada email transactional |
| Seller panel (CRUD produk, order, withdrawal) | MISSING |
| Forgot/reset password | MISSING |
| Email verification | MISSING |
| SyncPendingPayments job | MISSING |
| Auto-complete order scheduler | MISSING |
| Rate limiting | MISSING |
| Custom error pages (404, 500, 503) | MISSING |
| Seeder lengkap | MISSING |
| .env.example lengkap | PARTIAL |

---

## 5. MODEL vs MIGRATION — INKONSISTENSI

| Model | Isu |
|-------|-----|
| Payment | Kolom `meta` (raw webhook payload) tidak ada di migration maupun $fillable |
| Order | Kolom `paid_at` dipakai di PaymentService tapi perlu dicek di migration |
| Withdrawal | Model ada, tapi WithdrawalController pakai class_exists() guard — tidak percaya diri |

---

## 6. GAP PER MODUL & PRIORITAS

### P0 — Jalur Checkout-to-Paid (BLOCKER)
- [ ] Webhook tidak idempotent — bisa double-process jika Midtrans retry
- [ ] Webhook tidak simpan raw payload (kolom `meta` belum ada)
- [ ] Stock tidak di-restore saat order cancel/expire
- [ ] Tidak ada `SyncPendingPayments` job (order pending > 1 jam tidak di-sync)
- [ ] Tidak ada Policy — Seller A bisa akses order Seller B

### P0 — Auth
- [ ] Forgot password / reset password MISSING
- [ ] Email verification MISSING

### P1 — Seller Panel (hampir semua MISSING)
- [ ] Seller product CRUD (dengan varian & gambar)
- [ ] Seller order management (konfirmasi, packed, shipped + AWB)
- [ ] Seller withdrawal request & history
- [ ] Seller onboarding form (simpan ke DB)
- [ ] Seller dashboard revenue (bukan placeholder 0)

### P1 — Admin
- [ ] Refund logic STUB
- [ ] WithdrawalController tidak robust (class_exists guard)
- [ ] Audit log viewer (route/view belum ada)

### P2 — Polish
- [ ] Email transactional (order created, shipped, dll)
- [ ] Rate limiting
- [ ] Custom error pages
- [ ] Seeder lengkap
- [ ] Test coverage (hanya ExampleTest)

---

## 7. RINGKASAN EKSEKUTIF

**Sistem ~65% selesai.** Core customer flow (browse → cart → checkout → bayar) sudah berjalan. Yang kritis belum ada:

1. **Webhook tidak aman** — tidak idempotent, tidak simpan payload (P0)
2. **Seller panel hampir kosong** — hanya 2 route, tidak ada CRUD produk/order/withdrawal (P1)
3. **Auth tidak lengkap** — forgot/reset password & email verification missing (P0)
4. **Tidak ada Policy** — authorization hanya middleware, Seller A bisa akses data Seller B (P0)
5. **Tidak ada Jobs/Notifications** — tidak ada email, tidak ada sync job (P1)
