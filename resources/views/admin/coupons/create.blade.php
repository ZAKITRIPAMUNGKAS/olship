@extends('admin.layouts.app')
@section('title', 'Buat Kupon')
@section('page-title', 'Kupon')

@section('content')
<div style="display:flex;align-items:center;gap:12px;margin-bottom:22px;" class="fade-up">
    <a href="{{ route('admin.coupons.index') }}" style="color:var(--muted);text-decoration:none;font-size:13px;"><i class="fas fa-arrow-left"></i> Kembali</a>
    <span style="color:var(--border);">/</span>
    <h2 style="font-size:20px;font-weight:700;">Buat Kupon Baru</h2>
</div>

<form action="{{ route('admin.coupons.store') }}" method="POST" class="fade-up delay-1" onsubmit="const btn = this.querySelector('button[type=submit]'); if(btn) setLoading(btn, true, 'Menyimpan...'); return true;">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;">

        <div class="glass-card panel">
            <div class="panel-title" style="margin-bottom:18px;">🏷️ Detail Kupon</div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                <div>
                    <label class="form-label">Kode Kupon *</label>
                    <input class="form-input dm-mono" name="code" value="{{ old('code') }}" placeholder="LISTRINDO20" required style="text-transform:uppercase;letter-spacing:2px;font-weight:600;">
                    @error('code')<span style="color:var(--danger);font-size:11px;">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="form-label">Jenis Diskon *</label>
                    <select class="form-input" name="discount_type" id="discountType" onchange="updateDiscountLabel()">
                        <option value="percent" {{ old('discount_type') === 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="fixed"   {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                    </select>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:14px;">
                <div>
                    <label class="form-label" id="discountLabel">Nilai Diskon *</label>
                    <input class="form-input" type="number" name="discount_value" value="{{ old('discount_value') }}" min="0" required placeholder="20">
                </div>
                <div>
                    <label class="form-label">Min. Pembelian (Rp)</label>
                    <input class="form-input" type="number" name="min_purchase" value="{{ old('min_purchase') }}" min="0" placeholder="100000">
                </div>
                <div>
                    <label class="form-label">Batas Penggunaan</label>
                    <input class="form-input" type="number" name="usage_limit" value="{{ old('usage_limit') }}" min="1" placeholder="∞">
                </div>
            </div>
            <div style="margin-bottom:14px;">
                <label class="form-label">Expired</label>
                <input class="form-input" type="date" name="expires_at" value="{{ old('expires_at') }}">
            </div>
            <div>
                <label class="form-label">Keterangan</label>
                <textarea class="form-input" name="description" rows="2" placeholder="Deskripsi kupon...">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="glass-card panel" style="align-self:start;">
            <div class="panel-title" style="margin-bottom:16px;">⚙️ Aktifkan</div>
            <div style="padding:14px;border-radius:12px;background:var(--surface-2);border:1px solid var(--border);margin-bottom:14px;">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-size:13px;">Kupon Aktif</span>
                    <label class="toggle-switch">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
            <button type="submit" class="btn-ag btn-primary" style="width:100%;justify-content:center;padding:12px;">
                <i class="fas fa-save"></i> Buat Kupon
            </button>
        </div>
    </div>
</form>
@endsection
@push('styles')
<style>
.toggle-switch{position:relative;display:inline-block;width:44px;height:24px;}
.toggle-switch input{opacity:0;width:0;height:0;}
.toggle-slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background:var(--surface-2);border:1px solid var(--border);transition:.2s;border-radius:24px;}
.toggle-slider:before{position:absolute;content:"";height:18px;width:18px;left:2px;bottom:2px;background:var(--muted);transition:.2s;border-radius:50%;}
input:checked+.toggle-slider{background:var(--green);border-color:var(--green);}
input:checked+.toggle-slider:before{transform:translateX(20px);background:#fff;}
</style>
@endpush
@push('scripts')
<script>
function updateDiscountLabel() {
    const t = document.getElementById('discountType').value;
    document.getElementById('discountLabel').textContent = t === 'percent' ? 'Nilai Diskon (%)' : 'Nilai Diskon (Rp)';
}
</script>
@endpush
