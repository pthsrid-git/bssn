import HelperTypeView from '@/views/demo/HelperTypeView.vue'
import HelperView from '@/views/demo/HelperView.vue'

export const helperRoute = [
  {
    path: 'helper',
    name: 'main.helper',
    component: HelperView
  },
  {
    path: 'helper/type',
    name: 'main.helper.type',
    component: HelperTypeView
  }
]
