import IndexComponent from '../js/views/IndexComponent.vue'
import Login from '../js/views/Login.vue'
import Unauthorized from '../js/views/401.vue'
// import PanelTemplate from '../js/views/admin/AdminPanel.vue'
import PanelTemplate from '../js/views/IndexComponent.vue';
import Dashboard from '../js/views/admin/Dashboard.vue'
import UserMaster from '../js/views/admin/UserMaster.vue'
import TrtMaintenance from '../js/views/admin/TrtMaintenance.vue'
import SettingProcedureList from '../js/views/settings/SettingProcedureList.vue'

// USER
import Ticket from '../js/views/user/Ticket.vue';
import AssignedTicket from '../js/views/iss/AssignedTicket.vue';

console.log('routes');
export default [
    {
        path: '/',
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
        path: '/unauthorized',
        component: Unauthorized,
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
            },
            {
                path: 'TRT_Maintenance',
                name: 'trt_maintenance',
                component: TrtMaintenance
            },
            {
                path: 'assigned_ticket',
                name: 'assigned_ticket',
                component: AssignedTicket
            },

//SETTINGS

            {
                path: 'setting_procedure_list',
                name: 'setting_procedure_list',
                component: SettingProcedureList
            },


        ]
    },
];
