require('./bootstrap');
require('moment');

//manually
// require('./dashkit');
// require('./abuamr');

import Vue from 'vue';
import {InertiaApp} from '@inertiajs/inertia-vue';
import {InertiaForm} from 'laravel-jetstream';
import PortalVue from 'portal-vue';
import VueSwal from 'vue-swal';
// import {swal} from "vue-swal/src";
import VueSweetalert2 from 'vue-sweetalert2';
// import Swal from 'sweetalert2';
import 'bootstrap';
import vSelect from 'vue-select'
import VueGoodTablePlugin from 'vue-good-table';
import Multiselect from 'vue-multiselect'
import VTreeselect from 'vue-treeselect'
import 'vue-good-table/dist/vue-good-table.css'
import 'vue-select/dist/vue-select.css';
import 'sweetalert2/dist/sweetalert2.min.css';
import VueMask from 'v-mask';
import VueTimepicker from 'vue2-timepicker'
import 'vue2-timepicker/dist/VueTimepicker.css'
import VueCurrencyInput from 'vue-currency-input'
import VueClipboard from 'vue-clipboard2'
import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'


// If you don't need the styles, do not connect
import 'sweetalert2/dist/sweetalert2.min.css';


import Vue2Editor from "vue2-editor";

Vue.use(Vue2Editor);

Vue.use(VueCurrencyInput)
Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);
Vue.use(VueGoodTablePlugin);
Vue.use(VueSwal);
Vue.use(VueSweetalert2);
Vue.use(VueGoodTablePlugin);
Vue.use(VTreeselect);
Vue.use(VueMask);
Vue.use(VueTimepicker);
Vue.use(VueClipboard)

Vue.component('multiselect', Multiselect)
Vue.component('v-select', vSelect)
Vue.component('vue-timepicker', VueTimepicker)
Vue.component('vue-bootstrap-typeahead', VueBootstrapTypeahead)

//Including sweet alert
import swal2 from 'sweetalert2'

window.swal2 = swal2;

const toast = swal2.mixin({
    toast: true,
    type: 'success',
    icon: 'success',
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});
window.toast = toast;

Vue.mixin({
    methods: {
        globalHelper: function () {
            alert("Hello world")
        },
        async get_typeahead_suggestions(query) {
            // axios.post(route("commands.search"), {query: query}, {
            //     headers: {
            //         "Content-Type": "application/json",
            //         "Accept": "application/json",
            //     }
            // })
            //     .then(
            //         (response) => {
            // console.log([{value: '/start'}, {value: 'help'}])
            // this.bot.commands = [{value: '/start'}, {value: '/help'}]
            //     },
            //     (error) => {
            //         console.log(error)
            //     }
            // )
        },
    },
})

Vue.mixin({methods: {route}});

try {
    Vue.prototype.$userId = document.querySelector("meta[name='user-id']").getAttribute('content');
    Vue.prototype.$userPermissions = JSON.parse(document.querySelector("meta[name='user-permissions']").getAttribute('content'));
    Vue.prototype.$userRoles = JSON.parse(document.querySelector("meta[name='user-roles']").getAttribute('content'));
} catch (err) {
    Vue.prototype.$userId = 0;
}


const app = document.getElementById('app');

new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);
