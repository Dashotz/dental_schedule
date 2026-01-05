import './bootstrap';
import { createApp } from 'vue';
import vuetify from './plugins/vuetify';

// Initialize Vuetify globally for CSS classes
// Vue app will only mount if there's a #vue-app element
document.addEventListener('DOMContentLoaded', () => {
    const vueAppElement = document.getElementById('vue-app');
    if (vueAppElement) {
        import('./App.vue').then(({ default: App }) => {
            const app = createApp(App);
            app.use(vuetify);
            app.mount('#vue-app');
        });
    }
});

// Make Vuetify available globally for CSS
window.vuetify = vuetify;

