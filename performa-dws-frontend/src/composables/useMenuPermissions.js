import { computed } from 'vue'

export function useMenuPermissions() {
  // Get user from localStorage
  const getUser = () => {
    try {
      const userStr = localStorage.getItem('user')
      return userStr ? JSON.parse(userStr) : null
    } catch (error) {
      console.error('Error parsing user from localStorage:', error)
      return null
    }
  }

  const user = computed(() => getUser())

  // Check if user has specific role
  const hasRole = (roles) => {
    if (!user.value) return false
    if (typeof roles === 'string') {
      return user.value.role === roles
    }
    return roles.includes(user.value.role)
  }

  // Filter menus based on permissions
  const filterMenusByPermission = (menus) => {
    return menus.filter(menu => {
      // If menu has no permission requirement, show it
      if (!menu.requiredRoles) return true
      
      // Check if user has required role
      return hasRole(menu.requiredRoles)
    }).map(menu => {
      // If menu has submenu, filter submenu as well
      if (menu.submenu) {
        return {
          ...menu,
          submenu: menu.submenu.filter(submenuItem => {
            if (!submenuItem.requiredRoles) return true
            return hasRole(submenuItem.requiredRoles)
          })
        }
      }
      return menu
    })
  }

  return {
    user,
    hasRole,
    filterMenusByPermission
  }
}