import ModelTypeView from '@/views/demo/ModelTypeView.vue'
import ModelView from '@/views/demo/ModelView.vue'

export const modelRoute = [
  {
    path: 'model',
    name: 'main.model',
    component: ModelView
  },
  {
    path: 'model/type',
    name: 'main.model.type',
    component: ModelTypeView
  }
]
