import { mapMultipartRequestOptions, mapRequestOptions } from '@bssn/ui-kit-frontend'

import { useApiStore } from '../stores/apiStore'

export const getRequest = async (url: string, options: Record<string, any> = {}) => {
  const api = useApiStore()
  return await api.request.get(url, mapRequestOptions(options))
}

export const postRequest = async (
  url: string,
  values: Record<string, any>,
  options: Record<string, any> = {}
) => {
  const api = useApiStore()
  return await api.request.post(url, values, mapRequestOptions(options))
}

export const postMultipartRequest = async (
  url: string,
  formData: FormData,
  options: Record<string, any> = {}
) => {
  const api = useApiStore()
  return await api.request.post(url, formData, mapMultipartRequestOptions(options))
}

export const patchRequest = async (
  url: string,
  values: Record<string, any>,
  options: Record<string, any> = {}
) => {
  const api = useApiStore()
  return await api.request.patch(url, values, mapRequestOptions(options))
}

export const patchMultipartRequest = async (
  url: string,
  formData: FormData,
  options: Record<string, any> = {}
) => {
  const api = useApiStore()
  formData.append('_method', 'PATCH')
  return await api.request.post(url, formData, mapMultipartRequestOptions(options))
}

export const putRequest = async (
  url: string,
  values: Record<string, any>,
  options: Record<string, any> = {}
) => {
  const api = useApiStore()
  return await api.request.put(url, values, mapRequestOptions(options))
}

export const putMultipartRequest = async (
  url: string,
  formData: FormData,
  options: Record<string, any> = {}
) => {
  const api = useApiStore()
  formData.append('_method', 'PUT')
  return await api.request.post(url, formData, mapMultipartRequestOptions(options))
}

export const deleteRequest = async (url: string, options: Record<string, any> = {}) => {
  const api = useApiStore()
  return await api.request.delete(url, mapRequestOptions(options))
}
