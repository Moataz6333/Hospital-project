

import axios from 'axios';
window.axios = axios;
window.axios.defaults.withCredentials = true; 
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    import Echo from 'laravel-echo';
    import Pusher from 'pusher-js';
    
    window.Pusher = Pusher;
    
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: 'caa359463e78926e217b',
        cluster: 'eu',
        forceTLS: true,
        authEndpoint: "/broadcasting/auth",
        auth: {
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            }
        }
    });

    
    