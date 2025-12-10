// composables/useMenuPermissions.js
import { computed } from 'vue'

export function useMenuPermissions() {
  // Get user data from localStorage
  const getUserRole = () => {
    const userData = localStorage.getItem('user')
    if (!userData) return null
    
    try {
      const user = JSON.parse(userData)
      return {
        role: user.role || null,
        jabatan_id: user.jabatan_id || null,
        nama_jabatan: user.nama_jabatan || null,
        parent_id: user.parent_id || null,
        // Additional fields that might exist
        kode_jabatan: user.kode_jabatan || null,
        level: user.level || null
      }
    } catch (e) {
      console.error('Error parsing user data:', e)
      return null
    }
  }

  const userRole = computed(() => getUserRole())

  // Helper functions untuk detect role
  const isPKO = (role) => {
    if (!role) return false
    
    // PKO = Pegawai Kantor Operasional (Staff biasa)
    // Deteksi dari:
    // 1. Role field
    if (role.role === 'pko' || role.role === 'staff') {
      return true
    }
    
    // 2. Kode jabatan (jika ada)
    if (role.kode_jabatan === 'PKO') {
      return true
    }
    
    // 3. Level (level terendah)
    if (role.level === 1 || role.level === 'pko') {
      return true
    }
    
    // 4. Punya parent_id (bukan Ka-unit atau PMK)
    // Dan nama jabatan tidak include "Kepala" atau "Ketua"
    if (role.parent_id !== null && role.parent_id !== undefined) {
      const jabatan = role.nama_jabatan?.toLowerCase() || ''
      if (!jabatan.includes('kepala') && !jabatan.includes('ketua')) {
        return true
      }
    }
    
    return false
  }

  const isPMK = (role) => {
    if (!role) return false
    
    // PMK = Pejabat Menengah Kantor (Ketua Tim / Katim)
    // Deteksi dari:
    // 1. Role field
    if (role.role === 'pmk' || role.role === 'katim' || role.role === 'ketua_tim') {
      return true
    }
    
    // 2. Kode jabatan
    if (role.kode_jabatan === 'PMK' || role.kode_jabatan === 'KATIM') {
      return true
    }
    
    // 3. Level
    if (role.level === 2 || role.level === 'pmk') {
      return true
    }
    
    // 4. Nama jabatan
    const jabatan = role.nama_jabatan?.toLowerCase() || ''
    if (jabatan.includes('ketua tim') || jabatan.includes('kepala tim')) {
      return true
    }
    
    // 5. Custom flag
    if (role.is_pmk === true || role.is_katim === true) {
      return true
    }
    
    return false
  }

  const isKaUnit = (role) => {
    if (!role) return false
    
    // Ka-unit = Kepala Unit (Atasan)
    // Deteksi dari:
    // 1. Role field
    if (role.role === 'ka-unit' || role.role === 'kaunit' || role.role === 'atasan' || role.role === 'kepala_unit') {
      return true
    }
    
    // 2. Kode jabatan
    if (role.kode_jabatan === 'KA-UNIT' || role.kode_jabatan === 'KAUNIT') {
      return true
    }
    
    // 3. Level (level tertinggi)
    if (role.level === 3 || role.level === 'ka-unit') {
      return true
    }
    
    // 4. parent_id null + nama jabatan
    if (role.parent_id === null || role.parent_id === undefined) {
      const jabatan = role.nama_jabatan?.toLowerCase() || ''
      if (jabatan.includes('kepala') || jabatan.includes('direktur') || jabatan.includes('kabag')) {
        return true
      }
    }
    
    // 5. Custom flag
    if (role.is_ka_unit === true || role.is_atasan === true) {
      return true
    }
    
    return false
  }

  // Define menu permissions
  const menuPermissions = {
    // RUANG PRIBADI - Semua user bisa akses
    'beranda': () => true,
    'logbook': () => true,
    'kepegawaian': () => true,
    'keuangan': () => true,
    'pengetahuan': () => true,
    'kesehatan': () => true,
    'halo-pusdaik': () => true,

    // RUANG KERJA
    'logbook-katim': () => {
      const role = userRole.value
      if (!role) return false
      
      // Hanya PMK (Ketua Tim) yang bisa akses
      return isPMK(role)
    },

    'logbook-atasan': () => {
      const role = userRole.value
      if (!role) return false
      
      // Hanya Ka-unit yang bisa akses
      return isKaUnit(role)
    }
  }

  // Check if user can access a menu
  const canAccessMenu = (menuId) => {
    const permissionCheck = menuPermissions[menuId]
    if (!permissionCheck) return true // Default allow if no rule defined
    return permissionCheck()
  }

  // Filter menu list based on permissions
  const filterMenusByPermission = (menus) => {
    return menus.filter(menu => {
      const hasAccess = canAccessMenu(menu.id)
      
      // If menu has submenu, filter submenu too
      if (menu.hasSubmenu && menu.submenu) {
        menu.submenu = menu.submenu.filter(subitem => canAccessMenu(subitem.id))
        // Hide parent menu if all submenu items are filtered out
        return hasAccess && menu.submenu.length > 0
      }
      
      return hasAccess
    })
  }

  // Get user role name for display
  const getRoleName = () => {
    const role = userRole.value
    if (!role) return 'Unknown'
    
    if (isKaUnit(role)) return 'Ka-unit'
    if (isPMK(role)) return 'PMK (Ketua Tim)'
    if (isPKO(role)) return 'PKO (Staff)'
    
    return 'User'
  }

  return {
    userRole,
    isPKO: () => isPKO(userRole.value),
    isPMK: () => isPMK(userRole.value),
    isKaUnit: () => isKaUnit(userRole.value),
    getRoleName,
    canAccessMenu,
    filterMenusByPermission
  }
}