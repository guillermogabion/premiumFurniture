// Load lodash
window._ = require('lodash');

// Import Laravel Echo
import Echo from 'laravel-echo';

// Load Pusher
window.Pusher = require('pusher-js');

try {
    require('bootstrap');
} catch (e) {}

/**
 * Load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Set up Laravel Echo with Pusher for real-time event broadcasting.
 */
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY, // Use environment variables for keys
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true, // Use 'encrypted: true' for secure connections
});

// Subscribe to the messages channel and listen for the MessageSent event
window.Echo.channel('messages')
    .listen('MessageSent', (e) => {
        console.log(e.message); // Log the message received
    });
