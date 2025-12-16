import type { RouteRecordRaw } from 'vue-router'
import { adminRoute } from './admin'
import { pkoRoute } from './pko'
import { pmkRoute } from './pmk'

export const ruangKerjaRoutes: RouteRecordRaw[] = [adminRoute, pkoRoute, pmkRoute]
