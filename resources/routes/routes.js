import IndexComponent from '../js/views/IndexComponent.vue'
import Login from '../js/views/Login.vue'
// import PanelTemplate from '../js/views/admin/AdminPanel.vue'
import PanelTemplate from '../js/views/IndexComponent.vue';
import Dashboard from '../js/views/admin/Dashboard.vue'
import UserMaster from '../js/views/admin/UserMaster.vue'

// USER
import Ticket from '../js/views/user/Ticket.vue';

console.log('routes');
export default [
    {
        path: '/',
        // redirect: { name: 'login' },
        component: Login,
        children: [
            {
                path: '/',
                name: 'login',
                component: Login,
            },
        ]
    },
    {
        path: '/panel_template/',
        component: PanelTemplate,
        children: [
            {
                path: 'dashboard',
                name: 'dashboard',
                component: Dashboard,
            },
            {
                path: 'user_master',
                name: 'user_master',
                component: UserMaster,
            },
            {
                path: 'ticket',
                name: 'ticket',
                component: Ticket
            }
        ]
    }
];
