@extends('layouts.app')
@section('title', 'Kebijakan Privasi - LISTRINDO JAYA ELEKTRIK')

@section('content')
<div style="max-width: 800px; margin: 40px auto; padding: 0 20px;">

  <div style="margin-bottom: 32px;">
    <h1 style="font-family: 'Barlow Condensed', sans-serif; font-size: 36px; font-weight: 800; color: var(--ink); margin-bottom: 8px;">Kebijakan Privasi</h1>
    <p style="color: var(--ink-3); font-size: 14px;">Terakhir diperbarui: Januari 2026</p>
  </div>

  <div style="background: #fff; border: 1px solid var(--line); border-radius: 16px; padding: 40px; line-height: 1.8; color: var(--ink-2); font-size: 14px;">

    <p style="margin-bottom: 24px;"><strong>Listrindo Jaya Elektrik</strong> berkomitmen untuk melindungi privasi Anda. Kebijakan ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda saat menggunakan layanan kami.</p>

    <h2 style="font-size: 18px; font-weight: 700; color: var(--ink); margin: 28px 0 12px;">1. Informasi yang Kami Kumpulkan</h2>
    <p style="margin-bottom: 12px;">Kami mengumpulkan informasi berikut saat Anda menggunakan layanan kami:</p>
    <ul style="padding-left: 20px; margin-bottom: 16px;">
      <li style="margin-bottom: 8px;"><strong>Informasi akun:</strong> nama lengkap, alamat email, dan kata sandi terenkripsi.</li>
      <li style="margin-bottom: 8px;"><strong>Informasi pengiriman:</strong> alamat lengkap, nomor telepon, dan nama penerima.</li>
      <li style="margin-bottom: 8px;"><strong>Informasi transaksi:</strong> riwayat pembelian, metode pembayaran (tidak termasuk data kartu secara lengkap), dan status pesanan.</li>
      <li style="margin-bottom: 8px;"><strong>Informasi teknis:</strong> alamat IP, jenis browser, dan data penggunaan platform untuk keperluan analitik.</li>
    </ul>

    <h2 style="font-size: 18px; font-weight: 700; color: var(--ink); margin: 28px 0 12px;">2. Cara Kami Menggunakan Informasi</h2>
    <ul style="padding-left: 20px; margin-bottom: 16px;">
      <li style="margin-bottom: 8px;">Memproses dan mengelola pesanan Anda.</li>
      <li style="margin-bottom: 8px;">Mengirimkan konfirmasi pesanan, notifikasi pengiriman, dan informasi akun.</li>
      <li style="margin-bottom: 8px;">Meningkatkan kualitas layanan dan pengalaman pengguna.</li>
      <li style="margin-bottom: 8px;">Mencegah penipuan dan menjaga keamanan platform.</li>
      <li style="margin-bottom: 8px;">Mengirimkan informasi promosi (hanya jika Anda menyetujuinya).</li>
    </ul>

    <h2 style="font-size: 18px; font-weight: 700; color: var(--ink); margin: 28px 0 12px;">3. Keamanan Data</h2>
    <p>Kami menggunakan enkripsi SSL/TLS untuk melindungi data yang dikirimkan antara browser Anda dan server kami. Kata sandi disimpan dalam bentuk terenkripsi (hashed) dan tidak dapat dibaca oleh siapapun, termasuk tim kami. Akses ke data pengguna dibatasi hanya untuk karyawan yang membutuhkannya untuk menjalankan layanan.</p>

    <h2 style="font-size: 18px; font-weight: 700; color: var(--ink); margin: 28px 0 12px;">4. Berbagi Informasi dengan Pihak Ketiga</h2>
    <p style="margin-bottom: 12px;">Kami tidak menjual atau menyewakan data pribadi Anda kepada pihak ketiga. Kami hanya berbagi informasi yang diperlukan dengan:</p>
    <ul style="padding-left: 20px; margin-bottom: 16px;">
      <li style="margin-bottom: 8px;"><strong>Jasa pengiriman</strong> (JNE, J&T, SiCepat, dll.) untuk keperluan pengiriman pesanan.</li>
      <li style="margin-bottom: 8px;"><strong>Penyedia pembayaran</strong> (Midtrans) untuk memproses transaksi secara aman.</li>
      <li style="margin-bottom: 8px;"><strong>Otoritas hukum</strong> jika diwajibkan oleh peraturan perundang-undangan yang berlaku.</li>
    </ul>

    <h2 style="font-size: 18px; font-weight: 700; color: var(--ink); margin: 28px 0 12px;">5. Cookie</h2>
    <p>Kami menggunakan cookie untuk menjaga sesi login Anda, menyimpan preferensi, dan menganalisis penggunaan platform. Anda dapat menonaktifkan cookie melalui pengaturan browser, namun beberapa fitur mungkin tidak berfungsi dengan baik.</p>

    <h2 style="font-size: 18px; font-weight: 700; color: var(--ink); margin: 28px 0 12px;">6. Hak Anda</h2>
    <ul style="padding-left: 20px; margin-bottom: 16px;">
      <li style="margin-bottom: 8px;"><strong>Akses:</strong> Anda berhak meminta salinan data pribadi yang kami simpan.</li>
      <li style="margin-bottom: 8px;"><strong>Koreksi:</strong> Anda dapat memperbarui informasi akun kapan saja melalui halaman profil.</li>
      <li style="margin-bottom: 8px;"><strong>Penghapusan:</strong> Anda dapat meminta penghapusan akun dengan menghubungi kami.</li>
      <li style="margin-bottom: 8px;"><strong>Opt-out:</strong> Anda dapat berhenti menerima email promosi melalui tautan unsubscribe di setiap email.</li>
    </ul>

    <h2 style="font-size: 18px; font-weight: 700; color: var(--ink); margin: 28px 0 12px;">7. Retensi Data</h2>
    <p>Kami menyimpan data pribadi Anda selama akun Anda aktif atau selama diperlukan untuk menyediakan layanan. Data transaksi disimpan sesuai kewajiban hukum yang berlaku di Indonesia.</p>

    <h2 style="font-size: 18px; font-weight: 700; color: var(--ink); margin: 28px 0 12px;">8. Perubahan Kebijakan</h2>
    <p>Kami dapat memperbarui kebijakan privasi ini sewaktu-waktu. Perubahan signifikan akan diberitahukan melalui email atau notifikasi di platform. Penggunaan layanan setelah perubahan dianggap sebagai persetujuan atas kebijakan yang baru.</p>

    <h2 style="font-size: 18px; font-weight: 700; color: var(--ink); margin: 28px 0 12px;">9. Hubungi Kami</h2>
    <p>Untuk pertanyaan atau permintaan terkait privasi data Anda, silakan hubungi:</p>
    <div style="margin-top: 12px; padding: 16px; background: var(--bg); border-radius: 10px; font-size: 13px;">
      <div><strong>Email:</strong> info@listrindojaya.com</div>
      <div style="margin-top: 6px;"><strong>Telepon:</strong> 021-12345678</div>
      <div style="margin-top: 6px;"><strong>Alamat:</strong> Jl. Teknik Raya No. 123, Jakarta Pusat</div>
    </div>

  </div>

  <div style="margin-top: 24px; text-align: center;">
    <a href="{{ route('register') }}" style="color: var(--blue); font-size: 14px; font-weight: 600;">← Kembali ke halaman pendaftaran</a>
  </div>

</div>
@endsection
