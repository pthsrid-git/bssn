import { codeRoute } from './code'
import { componentRoute } from './component'
import { helperRoute } from './helper'
import { modelRoute } from './model'

export const mainRoutes = [...componentRoute, ...helperRoute, ...modelRoute, codeRoute]
