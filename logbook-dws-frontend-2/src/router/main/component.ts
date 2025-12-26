import ComponentTypeView from '@/views/demo/ComponentTypeView.vue'
import ComponentView from '@/views/demo/ComponentView.vue'

export const componentRoute = [
  {
    path: 'component',
    name: 'main.component',
    component: ComponentView
  },
  {
    path: 'component/type',
    name: 'dashboard.component.type',
    component: ComponentTypeView
  }
]
