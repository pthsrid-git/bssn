import type { AuthData, UserDwsData } from '@bssn/ui-kit-frontend'
import type { RouteRecordRaw } from 'vue-router'

// ================================================================================================
// Functions
// ================================================================================================
export declare function setAuthLogbookDwsFrontend(authData: AuthData): void
export declare function removeAuthLogbookDwsFrontend(): void

export declare function setUserDwsLogbookDwsFrontend(userData: UserDwsData): void
export declare function removeUserDwsLogbookDwsFrontend(): void

// ================================================================================================
// Router
// ================================================================================================
export declare const logbookRuangPribadiKepagawaianSubRoute: RouteRecordRaw[]
export declare const logbookRuangKerjaRoutes: RouteRecordRaw[]
