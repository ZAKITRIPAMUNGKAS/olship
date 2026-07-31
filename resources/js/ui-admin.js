// Admin UI Logic
window.confirmAction = function({ title, body, label = 'Konfirmasi', onConfirm }) {
    const titleEl = document.getElementById('modalTitle');
    const bodyEl = document.getElementById('modalBody');
    const confirmBtn = document.getElementById('modalConfirmBtn');
    const overlay = document.getElementById('confirmModal');

    if (!titleEl || !bodyEl || !confirmBtn || !overlay) return;

    titleEl.textContent = title;
    bodyEl.textContent = body;
    confirmBtn.textContent = label;
    overlay.classList.add('show');

    confirmBtn.onclick = () => {
        if (typeof onConfirm === 'function') onConfirm();
        window.closeModal();
    };
};

window.closeModal = function() {
    const overlay = document.getElementById('confirmModal');
    if (overlay) overlay.classList.remove('show');
};

window.showToast = function(message, type = 'success', duration = 3000) {
    const icons = { success: 'fa-check-circle', danger: 'fa-times-circle', warning: 'fa-exclamation-circle' };
    const container = document.getElementById('toast-container');
    if (!container) return;

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
};

window.setLoading = function(btn, loading, label) {
    if (loading) {
        btn.disabled = true;
        btn.dataset.originalLabel = btn.innerHTML;
        btn.innerHTML = `<i class="fas fa-circle-notch fa-spin"></i> ${label || 'Memproses...'}`;
        btn.style.opacity = '0.75';
    } else {
        btn.disabled = false;
        if (btn.dataset.originalLabel) {
            btn.innerHTML = btn.dataset.originalLabel;
        } else if (label) {
            btn.innerHTML = label;
        }
        btn.style.opacity = '';
    }
};

// Keyboard Shortcuts
document.addEventListener('keydown', (e) => {
    const tag = document.activeElement.tagName;
    const isTyping = tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT';

    if (e.key === '/' && !isTyping) {
        e.preventDefault();
        const searchInput = document.querySelector('.global-search input') || document.querySelector('.search-wrap input') || document.querySelector('input[type="search"]');
        if (searchInput) {
            searchInput.focus();
            searchInput.select();
        }
    }

    if (e.key === 'Escape' && (
        document.activeElement === document.querySelector('.global-search input') ||
        document.activeElement === document.querySelector('.search-wrap input')
    )) {
        document.activeElement.blur();
    }
});

// Mobile Scroll Indicator
function checkTableOverflow() {
    document.querySelectorAll('.table-responsive').forEach(el => {
        const hasOverflow = el.scrollWidth > el.clientWidth;
        el.classList.toggle('has-overflow', hasOverflow);

        el.addEventListener('scroll', () => {
            const atEnd = el.scrollLeft + el.clientWidth >= el.scrollWidth - 4;
            el.classList.toggle('has-overflow', !atEnd);
        }, { passive: true });
    });
}

window.addEventListener('load', checkTableOverflow);
window.addEventListener('resize', checkTableOverflow);

// Handle modal click outside
document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.getElementById('confirmModal');
    if (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === this) window.closeModal();
        });
    }
});
