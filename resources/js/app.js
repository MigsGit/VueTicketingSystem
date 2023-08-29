import './bootstrap'
import {createApp} from 'vue'
// import IndexComponent from './views/IndexComponent.vue'
import AppTemplate from './layouts/App.vue';
import { pinia } from './stores/index.js';


/*
startbootstrap-sb-admin template
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
// .component('index-component', IndexComponent)
.use(pinia)
.use(router)
.mount('#app');
