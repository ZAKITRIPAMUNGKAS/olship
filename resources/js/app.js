import Alpine from 'alpinejs';
import focus from '@alpinejs/focus';
import mask from '@alpinejs/mask';

window.Alpine = Alpine;

Alpine.plugin(focus);
Alpine.plugin(mask);

// Global UI Components
Alpine.data('countdown', (endsAt) => ({
    endTime: new Date(endsAt).getTime(),
    hours: '00',
    minutes: '00',
    seconds: '00',
    timer: null,
    init() {
        this.update();
        this.timer = setInterval(() => this.update(), 1000);
    },
    update() {
        const now = new Date().getTime();
        const diff = this.endTime - now;
        if (diff <= 0) {
            clearInterval(this.timer);
            return;
        }
        this.hours = Math.floor(diff / (1000 * 60 * 60)).toString().padStart(2, '0');
        this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, '0');
        this.seconds = Math.floor((diff % (1000 * 60)) / 1000).toString().padStart(2, '0');
    }
}));

Alpine.data('tabs', (initial = 'detail') => ({
    activeTab: initial,
    setTab(tab) {
        this.activeTab = tab;
    }
}));



Alpine.start();
