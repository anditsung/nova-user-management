import NovaSidebarMenu from 'nova-sidebar-menu'

Nova.booting((Vue, router, store) => {
    Vue.component('nova-sidebar', NovaSidebarMenu)

    Vue.component('detail-permission-checkbox-field', require('./components/permission-checkbox/detail/Index'))
    Vue.component('form-permission-checkbox-field', require('./components/permission-checkbox/form/Index'))
})
