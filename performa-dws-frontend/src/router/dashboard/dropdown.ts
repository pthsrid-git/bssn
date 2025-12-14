import ComponentView from '@/views/demo/ComponentView.vue'
import HelperView from '@/views/demo/HelperView.vue'
import ModelView from '@/views/demo/ModelView.vue'

export const dropdownRoute = {
  path: 'dropdown',
  name: 'dashboard.dropdown',
  meta: {
    title: 'Dropdown',
    menu: true,
    guard: 'project.role.default',
    icon: 'fa-duotone fa-circle-o'
  },
  redirect: { name: 'dashboard.dropdown.component' },
  children: [
    {
      path: 'component',
      name: 'dashboard.dropdown.component',
      component: ComponentView,
      meta: {
        title: 'Dropdown Component',
        menu: true,
        icon: 'fa-duotone fa-circle-o'
      }
    },
    {
      path: 'helper',
      name: 'dashboard.dropdown.helper',
      component: HelperView,
      meta: {
        title: 'Dropdown Helper',
        menu: true,
        icon: 'fa-duotone fa-circle-o'
      }
    },
    {
      path: 'model',
      name: 'dashboard.dropdown.model',
      component: ModelView,
      meta: {
        title: 'Dropdown Model',
        menu: true,
        icon: 'fa-duotone fa-circle-o'
      }
    }
  ]
}
