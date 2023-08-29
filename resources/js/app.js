import './bootstrap'
import {createApp} from 'vue'
import IndexComponent from './views/IndexComponent.vue'

/*
startbootstrap-sb-admin template
*/
import "startbootstrap-sb-admin/dist/css/styles.css";
import "startbootstrap-sb-admin/dist/js/scripts.js";
import "startbootstrap-sb-admin/dist/js/datatables-simple-demo.js";
/*
Local JS extensions
*/
import router from "../routes";
createApp()
.component('index-component', IndexComponent)
.use(router)
.mount('#app');
