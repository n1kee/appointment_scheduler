require('./bootstrap')

import VueRouter from 'vue-router'

const Vue = require('vue').default

Vue.use(VueRouter)

const csrfToken = document.querySelector('meta[name="csrf-token"]').content
Vue.component('Csrf', {
    template: `<input type="hidden" name="_token" value="${csrfToken}" />`
})

window.Vue = Vue

const PatientForm = Vue.component(
    'PatientFormComponent',
    require('./components/PatientFormComponent.vue').default
)

const DoctorForm = Vue.component(
    'DoctorFormComponent',
    require('./components/DoctorFormComponent.vue').default
)

const AppointmentForm = Vue.component(
    'AppointmentFormComponent',
    require('./components/AppointmentFormComponent.vue').default
)

const AppointmentList = Vue.component(
    'AppointmentListComponent',
    require('./components/AppointmentListComponent.vue').default
)

const routes = [
    { path: '/appointments', component: AppointmentList },
    { path: '/appointments/new', component: AppointmentForm },
    { path: '/patients/new', component: PatientForm },
    { path: '/doctors/new', component: DoctorForm }
]

const router = new VueRouter({ routes })

const app = new Vue({ router })
app.$mount('#app')
