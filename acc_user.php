<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::where('name', 'like', '%sisil%')
    ->orWhere('email', 'like', '%sisil%')
    ->first();

if (!$user) {
    // Try unverified users
    echo "User 'sisil' tidak ditemukan. Daftar user belum terverifikasi:\n";
    $unverified = App\Models\User::whereNull('email_verified_at')->get(['id', 'name', 'email', 'created_at']);
    foreach ($unverified as $u) {
        echo "ID: {$u->id} | {$u->name} | {$u->email} | Created: {$u->created_at}\n";
    }
} else {
    echo "Ditemukan:\n";
    echo "ID: {$user->id} | Nama: {$user->name} | Email: {$user->email}\n";
    echo "Verified: " . ($user->email_verified_at ? $user->email_verified_at : 'BELUM TERVERIFIKASI') . "\n";
    
    // Auto verify
    $user->email_verified_at = now();
    $user->save();
    echo "\n✓ Akun berhasil diverifikasi!\n";
}
