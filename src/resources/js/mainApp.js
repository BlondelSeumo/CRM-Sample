import "./bootstrap";
import "./plugins";
import Vue from "vue";
import vuexI18n from "vuex-i18n";
import moment from "moment";
import "./core/coreApp";
import "./crm/crmComponent";
import {urlGenerator} from "./crm/Helpers/helpers";
import crmVuexStore from "./crm/Store";

window.moment = moment;
window.Vue = Vue;

// for working with subfolder
// window.axios.defaults.baseURL = window.localStorage.getItem('base_url') !=="" ? window.localStorage.getItem('base_url') : window.location.origin;

// Vue.filter('formatTime', function(value) {
//     if (value) {
//         const parts = value.split(":");
//         return +parts[0] + "h " + +parts[1] + "m";
//     } else {
//         return "unknown"
//     }
// });
/**
 * localization
 * $t('key') or this.$('key')
 * link: https://github.com/dkfbasel/vuex-i18n
 * */

Vue.use(vuexI18n.plugin, crmVuexStore);

let language = JSON.parse(window.localStorage.getItem("app-languages"));
let key = window.localStorage.getItem("app-language");
Vue.i18n.add(key, language);
// set the start locale to use
Vue.i18n.set(key);

/*------ localization end ------*/

const app = new Vue({
    store: crmVuexStore,
    el: "#app"
});
Vue.prototype.notify = (ev) => {
    app.$store.dispatch("getAllNotification");
    app.$toastr.Add({
        title: "New Notification",
        msg: ev.message,
        type: "information",
        preventDuplicates: true, //Default is false,
        style: {
            borderColor: "var(--default-card-bg) !important",
            backgroundColor: "var(--default-card-bg) !important",
            color: "var(--default-font-color) !important",
            boxShadow: "var(--avatars-box-shadow) !important"
        } // bind inline style to toast  (check [Vue doc](https://vuejs.org/v2/guide/class-and-style.html#Binding-Inline-Styles) for more examples)
    });
    let el = document.getElementById("notificationDropdown");
    let newValueCounter = Number(el.getAttribute("data-noti")) + 1;
    el.setAttribute("data-noti", newValueCounter);
    el.classList.add("newNotificationBadge");
    // play sound
    var audio = new Audio(urlGenerator("/vendor/sound/new_notification.mp3"));
    audio.play();
}
if (window.broadcastDriver === 'pusher') {
Echo.private(`pipex_notification_for_agent.${window.user?.id}`).listen(
    "NewNotification",
    ev => {
        if (window.user.id !== 1) {
            if (ev.user.id !== window.user.id) {
                app.notify(ev)
                app.$hub.$emit('pipexDealChanged', {
                    model: ev,
                    msg: app.$t('data_changed_by_other_user', {user: ev.user.full_name})
                });
            } else {
                app.$hub.$emit('pipexDealChanged', {model: ev, msg: app.$t('data_changed_in_other_tab')});
            }
        }
    }
);

Echo.private(`pipex_notification_for_client.${window.user?.id}`).listen(
    "NewNotification",
    ev => {

        if (ev.user.id !== window.user.id) {
            app.notify(ev)
            app.$hub.$emit('pipexDealChanged', {
                model: ev,
                msg: app.$t('data_changed_by_other_user', {user: ev.user.full_name})
            });
        } else {
            app.$hub.$emit('pipexDealChanged', {model: ev, msg: app.$t('data_changed_in_other_tab')});
        }
    }
);

Echo.private(`pipex_notification_for_manager`).listen(
    "NewNotification",
    ev => {
        if (ev.user.id !== window.user.id) {
            app.notify(ev)
            app.$hub.$emit('pipexDealChanged', {
                model: ev,
                msg: app.$t('data_changed_by_other_user', {user: ev.user.full_name})
            });
        } else {
            app.$hub.$emit('pipexDealChanged', {model: ev, msg: app.$t('data_changed_in_other_tab')});
        }
    }
);

Echo.private(`pipex_notification_for_admin`).listen(
    "NewNotification",
    ev => {
        if (ev.user.id !== window.user.id) {
            app.notify(ev)
            app.$hub.$emit('pipexDealChanged', {
                model: ev,
                msg: app.$t('data_changed_by_other_user', {user: ev.user.full_name})
            });
        } else {
            app.$hub.$emit('pipexDealChanged', {model: ev, msg: app.$t('data_changed_in_other_tab')});
        }
    }
);
}
if (window.broadcastDriver === 'ajax') {
    let reqHnadler = true;
    setInterval(()=>{
        if(reqHnadler){
            reqHnadler = false;
            window.axios.get(route('extended_notification')).then(({data,status}) => {
                reqHnadler = true;
                if(data.length > 0 && status == 200){
                    data.map(ev => {
                        app.notify(ev);
                        app.$hub.$emit('pipexDealChanged', {model:{'deal':ev.contextable},msg: app.$t('data_changed_in_other_tab')});
                    });

                }
            }).catch(e=>reqHnadler = true);
        }
    },1500);

}

$("#notificationDropdown").on('click',({target})=>{   
    if(target.classList.contains("newNotificationBadge")){
        target.setAttribute("data-noti", 0);
        target.classList.remove("newNotificationBadge");
    }
});

window.axios.interceptors.response.use(
    function (response) {
        return response;
    },
    function (error) {
        const code = error.response.status;
        if (401 === code) {
            if (
                document.getElementsByClassName("swal2-container").length === 0
            ) {
                swal(error.response);
            }
        }
        if (403 === code) {
            app.$toastr.i(app.$t("permission_problem"));
        }
        if (419 === code) {
            app.$toastr.e(error.response.data.message);
        }
        if (424 === code) {
            app.$toastr.e(error.response.data.message);
        }
        if (code > 499) {
            app.$toastr.e(error.response.data.message);
        } else {
            return Promise.reject(error);
        }
    }
);

function swal(response, showConfirm = true) {
    Swal.fire({
        title: "Error!!",
        text: response.data.message,
        showCancelButton: true,
        showConfirmButton: showConfirm,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Login",
        imageWidth: 100,
        imageHeight: 70,
        imageAlt: "Error"
    }).then(function (response) {
        if (!response.dismiss) {
            window.location.replace(urlGenerator("/admin/users/login"));
        }
    });
}
