// Cart Management Logic
document.addEventListener('alpine:init', () => {
    Alpine.data('cartPage', () => ({
        isLoading: false,
        
        async updateQty(itemId, newQty) {
            if (newQty < 1) return this.removeItem(itemId);
            
            this.isLoading = true;
            try {
                const response = await fetch(`/cart/${itemId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ quantity: newQty })
                });
                if (response.ok) window.location.reload();
            } catch (e) {
                alert('Gagal memperbarui keranjang');
            } finally {
                this.isLoading = false;
            }
        },
        
        async removeItem(itemId) {
            if (!confirm('Hapus produk ini dari keranjang?')) return;
            
            this.isLoading = true;
            try {
                const response = await fetch(`/cart/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                if (response.ok) window.location.reload();
            } catch (e) {
                alert('Gagal menghapus produk');
            } finally {
                this.isLoading = false;
            }
        }
    }));
});
