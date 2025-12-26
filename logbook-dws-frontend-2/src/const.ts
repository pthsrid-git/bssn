// Project Const
export default class ProjectConst {
  static name = 'UiKitFrontend'
  static slug = 'ui-kit-frontend'
}

// Store Definitions
const storeEntities = [
  ['Test', ['Logic']],
  ['', ['Api', 'Dropdown', 'MenuBadge', 'Option', 'UserDefault', 'UserDws']]
] as const

const createStoreName = (role: string, entity: string) =>
  `store${ProjectConst.name}${role}${entity}`

const storeDefinitions = Object.fromEntries(
  storeEntities.flatMap(([role, entities]) =>
    entities.map((entity) => {
      const key = role
        ? `${role.charAt(0).toLowerCase()}${role.slice(1)}${entity}Store`
        : `${entity.charAt(0).toLowerCase()}${entity.slice(1)}Store`
      return [key, createStoreName(role, entity)]
    })
  )
) as Record<string, string>

export const {
  testLogicStore,
  apiStore,
  dropdownStore,
  menuBadgeStore,
  optionStore,
  userDefaultStore,
  userDwsStore
} = storeDefinitions
