import Vue from 'vue'
import Router from 'vue-router'
import Main from '@/components/Main'
import axios from 'axios'
import VueAxios from 'vue-axios'
import Calendar from '@/components/Calendar'
import Boardroom from '@/components/Boardroom'
import Employe from '@/components/Employe'
import AddUser from '@/components/AddUser'
import EditUser from '@/components/EditUser'

Vue.use(Router)
Vue.use(VueAxios, axios)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Main',
      component: Main
    },
    {
      path: '/boardroom/:id',
      name: 'Boardroom',
      component: Boardroom
    },
    {
      path: '/employeelist/',
      name: 'Employe',
      component: Employe,
      children: [
        { path: 'adduser', component: AddUser },
        { path: 'edituser/:id', component: EditUser },
      ]
    }
  
  ]
})
