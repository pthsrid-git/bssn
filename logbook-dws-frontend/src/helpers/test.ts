import {
  authDataFromJson,
  responseDataObjectFromJson,
  userDefaultDataFromJson,
  userDwsDataFromJson,
  type AuthData,
  type UserDefaultData,
  type UserDwsData
} from '@/models'

// ================================================================================================
// Get Test Auth Data
// ================================================================================================
export const getTestAuthData = () => {
  return responseDataObjectFromJson<AuthData>(
    {
      data: {
        access_token: import.meta.env.VITE_TEST_AUTH_ACCESS_TOKEN,
        '2fa_status': import.meta.env.VITE_TEST_AUTH_2FA_STATUS
      }
    },
    authDataFromJson
  ).item
}

// ================================================================================================
// Get Test User Default Data
// ================================================================================================
export const getTestUserDefaultData = () => {
  return responseDataObjectFromJson<UserDefaultData>(
    {
      data: {
        guid: import.meta.env.VITE_TEST_USER_GUID,
        name: import.meta.env.VITE_TEST_USER_NAME,
        email: import.meta.env.VITE_TEST_USER_EMAIL,
        roles: import.meta.env.VITE_TEST_USER_ROLES.split(','),
        permissions: import.meta.env.VITE_TEST_USER_PERMISSIONS.split(',')
      }
    },
    userDefaultDataFromJson
  ).item
}

// ================================================================================================
// Get Test User Dws Data
// ================================================================================================
export const getTestUserDwsData = () => {
  return responseDataObjectFromJson<UserDwsData>(
    {
      data: {
        guid: import.meta.env.VITE_TEST_USER_GUID,
        name: import.meta.env.VITE_TEST_USER_NAME,
        fullname: import.meta.env.VITE_TEST_USER_FULLNAME,
        email: import.meta.env.VITE_TEST_USER_EMAIL,
        fpid: '1234',
        uuid: '9b4c2d8e17a0f34b91ac6e2d5f7a8c10',
        nip: '482716594203857194',
        pangkat: 'Penata Muda Tk. I (III/b)',
        jabatan: 'Pranata Komputer Pertama',
        roles: [...import.meta.env.VITE_TEST_USER_ROLES.split(',')],
        permissions: {
          'ruang-pribadi': [...import.meta.env.VITE_TEST_USER_PERMISSIONS_RUANG_PRIBADI.split(',')],
          'ruang-kerja': [...import.meta.env.VITE_TEST_USER_PERMISSIONS_RUANG_KERJA.split(',')]
        }
      }
    },
    userDwsDataFromJson
  ).item
}
