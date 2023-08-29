import {createRouter,createWebHashHistory} from 'vue-router';
import routes from './routes';

const router = createRouter({
    /*NOTE:
        createWebHistory is creating a #hashtag to direct to diff (router 3)
        createWebHashHistory is creating a #hashtag to direct to diff (router 4)
     */
    history: createWebHashHistory(),
    routes
})

export default router;
