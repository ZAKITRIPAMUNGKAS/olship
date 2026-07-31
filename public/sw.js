/**
 * Service Worker Kill-Switch
 * File ini menghapus service worker lama yang tersimpan di browser.
 * SW lama itulah yang menyebabkan error JSON "Offline" setelah login.
 */
self.addEventListener('install', () => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        // Hapus semua cache yang tersimpan
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => caches.delete(cacheName))
            );
        }).then(() => {
            // Unregister service worker ini setelah membersihkan cache
            return self.registration.unregister();
        }).then(() => {
            // Paksa semua client (tab) untuk refresh
            return self.clients.matchAll({ type: 'window' });
        }).then((clients) => {
            clients.forEach(client => {
                // Beritahu halaman bahwa SW sudah di-unregister
                client.postMessage({ type: 'SW_UNREGISTERED' });
            });
        })
    );
});
