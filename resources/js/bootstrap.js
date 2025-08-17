import axios from 'axios';
window.axios = axios;
axios.create({
    baseURL: "https://ff133802f320.ngrok-free.app",
    headers: {
        "X-Requested-With": "XMLHttpRequest"
    }
});
