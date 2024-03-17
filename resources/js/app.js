import './bootstrap'
import '../css/app.css';
import {createApp} from 'vue'
// import IndexComponent from './views/IndexComponent.vue'
import AppTemplate from '/resources/js/layouts/App.vue';
import { pinia } from '/resources/js/stores';
/*
 * Vendors/Plugins
*/
import { library } from '@fortawesome/fontawesome-svg-core' /* import the fontawesome core | npm i @fortawesome/fontawesome-svg-core*/
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome' /* import font awesome icon component | npm i @fortawesome/vue-fontawesome*/
import { fas } from '@fortawesome/free-solid-svg-icons' /* import entire style | npm i @fortawesome/free-solid-svg-icons*/
library.add(fas) /* add icons to the library */
/*
 *Startbootstrap-sb-admin template
*/
import "startbootstrap-sb-admin/dist/css/styles.css";
import "startbootstrap-sb-admin/dist/js/scripts.js";
import "startbootstrap-sb-admin/dist/js/datatables-simple-demo.js";
/*
Local JS extensions
*/
import 'vue-toast-notification/dist/theme-bootstrap.css'
import router from "../routes";
createApp(AppTemplate)
.use(pinia)
.use(router)
.component('font-awesome-icon',FontAwesomeIcon)
.mount('#app');
