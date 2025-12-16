import type { RouteRecordRaw } from 'vue-router'
import { adminRoute } from './admin'
import { pkoRoute } from './pko'

export const ruangKerjaRoutes: RouteRecordRaw[] = [adminRoute, pkoRoute]
