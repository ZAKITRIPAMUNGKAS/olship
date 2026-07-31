import Chart from 'chart.js/auto';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Admin Dashboard Charts
Alpine.data('dashboardCharts', () => ({
    init() {
        this.initRevenueChart();
        this.initStatusChart();
    },
    
    initRevenueChart() {
        const el = document.getElementById('revenueChart');
        if (!el) return;
        
        const rawData = JSON.parse(el.dataset.values || '[]');
        const ctx = el.getContext('2d');
        const brandBlue = '#025cca';
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: rawData.map(d => d.date.slice(5)),
                datasets: [{
                    label: 'Revenue',
                    data: rawData.map(d => d.total),
                    borderColor: brandBlue,
                    backgroundColor: 'rgba(2, 92, 202, 0.05)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: brandBlue,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#e2e8f0' },
                        ticks: { callback: v => 'Rp' + (v >= 1000000 ? (v/1000000).toFixed(1)+'jt' : (v/1000).toFixed(0)+'k') }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    },
    
    initStatusChart() {
        const el = document.getElementById('statusChart');
        if (!el) return;
        
        const rawData = JSON.parse(el.dataset.values || '{}');
        const ctx = el.getContext('2d');
        const brandBlue = '#025cca';
        const brandOrange = '#f97316';
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(rawData),
                datasets: [{
                    data: Object.values(rawData),
                    backgroundColor: [brandOrange, brandBlue, '#8B5CF6', '#059669', '#dc2626'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: { legend: { display: false } }
            }
        });
    }
}));

Alpine.start();
