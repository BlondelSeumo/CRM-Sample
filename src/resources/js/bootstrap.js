/**
 * This bootstrap file is used for both frontend and core
 */

import _ from "lodash";
import axios from "axios";
import Swal from "sweetalert2";
import $ from "jquery";
import "popper.js"; // Required for BS4
import "bootstrap";
import {urlGenerator} from "./crm/Helpers/helpers";

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = $;
window.Swal = Swal;
window._ = _; // Lodash

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require("axios");

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
if (document.querySelector('meta[name="csrf-token"]')) {
  window.axios.defaults.headers.common["X-CSRF-TOKEN"] = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from "laravel-echo";

window.Pusher = require("pusher-js");

// Enable pusher logging - don't include this in production
// Pusher.logToConsole = true;
if (window.broadcastDriver === 'pusher') {
  
    window.Echo = new Echo({
      broadcaster: "pusher",
      key: pusherDriver.MIX_PUSHER_APP_KEY,
      cluster: pusherDriver.MIX_PUSHER_APP_CLUSTER,
      forceTLS:true,
      authEndpoint: urlGenerator('/broadcasting/auth')
    });
}

