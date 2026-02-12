import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.initializeRealtime = () => ({
    broadcaster: import.meta.env.VITE_BROADCAST_DRIVER ?? 'pusher',
    host: import.meta.env.VITE_PUSHER_HOST ?? window.location.hostname,
    port: Number(import.meta.env.VITE_PUSHER_PORT ?? 6001),
});
