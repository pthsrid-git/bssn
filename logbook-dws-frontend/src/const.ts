// Project Const
export default class ProjectConst {
  static name = 'LogbookDwsFrontend'
  static slug = 'logbook-dws-frontend'
}

// Store Definitions
const storeEntities = [
  ['Admin', ['Petugas', 'Pendapatan']],
  ['Pegawai', ['Pengajuan', 'Pendapatan', 'Logbook']],
  ['Pengolah', ['Pengajuan', 'LogbookKatim']],
  ['', ['Api', 'Pegawai', 'Periode', 'UserDws']]
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
  adminPetugasStore,
  adminPendapatanStore,
  pegawaiPengajuanStore,
  pegawaiPendapatanStore,
  pengolahPengajuanStore,
  pengolahLogbookKatimStore,
  pegawaiLogbookStore,
  apiStore,
  periodeStore,
  pegawaiStore,
  userDwsStore
} = storeDefinitions
